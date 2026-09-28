<?php

namespace Tests\Feature;

use App\Models\Complaint;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ComplaintControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_valid_payload_creates_complaint_and_returns_201(): void
    {
        Storage::fake('local');

        $response = $this->postJson(route('complaints.store'), [
            'phone' => '081234567890',
            'email' => 'pelapor@example.com',
            'complaint_type' => 'Dugaan Pelanggaran Gratifikasi',
            'report' => 'Kronologi laporan ini memiliki lebih dari dua puluh karakter.',
            'evidence' => UploadedFile::fake()->create('bukti.pdf', 100, 'application/pdf'),
        ]);

        $response->assertCreated()->assertJsonStructure(['message', 'ticket_number']);
        $complaint = Complaint::firstOrFail();
        $this->assertSame('pelapor@example.com', $complaint->email);
        $this->assertMatchesRegularExpression('/^BHP-GRA-\d{6}-0001$/', $complaint->ticket_number);
        $this->assertSame('complaint-evidence/Bukti-Pengaduan-'.$complaint->ticket_number.'.pdf', $complaint->evidence_path);
        Storage::disk('local')->assertExists($complaint->evidence_path);
    }

    public function test_empty_payload_returns_422_without_creating_complaint(): void
    {
        $this->postJson(route('complaints.store'), [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['phone', 'email', 'complaint_type', 'report', 'evidence']);

        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_evidence_larger_than_ten_megabytes_is_rejected(): void
    {
        Storage::fake('local');

        $this->postJson(route('complaints.store'), [
            'phone' => '081234567890',
            'email' => 'pelapor@example.com',
            'complaint_type' => 'Dugaan Pelanggaran Gratifikasi',
            'report' => 'Kronologi laporan ini memiliki lebih dari dua puluh karakter.',
            'evidence' => UploadedFile::fake()->create('bukti.pdf', 10241, 'application/pdf'),
        ])->assertUnprocessable()->assertJsonValidationErrors('evidence');

        $this->assertDatabaseCount('complaints', 0);
    }

    public function test_browser_submission_redirects_with_ticket_number(): void
    {
        Storage::fake('local');

        $response = $this->from(route('complaints.create'))->post(route('complaints.store'), [
            'phone' => '081234567890',
            'email' => 'pelapor@example.com',
            'complaint_type' => 'Dugaan Pelanggaran Lainnya',
            'report' => 'Laporan pengaduan lengkap dengan kronologi yang jelas.',
            'evidence' => UploadedFile::fake()->image('bukti.jpg'),
        ]);

        $response->assertRedirect(route('complaints.success'))
            ->assertSessionHas('success')
            ->assertSessionHas('ticket_number');
        $this->assertDatabaseCount('complaints', 1);

        $this->get(route('complaints.success'))
            ->assertOk()
            ->assertSee('Pengaduan berhasil dikirim')
            ->assertSee('Kembali ke Beranda')
            ->assertSee(Complaint::firstOrFail()->ticket_number)
            ->assertDontSee('Kirim Pengaduan');
    }

    public function test_ticket_sequence_increments_per_date_and_complaint_type(): void
    {
        Storage::fake('local');
        Carbon::setTestNow('2026-08-31 10:00:00');

        foreach (['Dugaan Pelanggaran Gratifikasi', 'Dugaan Pelanggaran Gratifikasi', 'Dugaan Pelanggaran Calo'] as $index => $type) {
            $this->postJson(route('complaints.store'), [
                'phone' => '08123456789'.$index,
                'email' => "pelapor{$index}@example.com",
                'complaint_type' => $type,
                'report' => 'Kronologi pengaduan ini memiliki lebih dari dua puluh karakter.',
                'evidence' => UploadedFile::fake()->create("bukti-{$index}.pdf", 50, 'application/pdf'),
            ])->assertCreated();
        }

        $this->assertSame([
            'BHP-GRA-260831-0001',
            'BHP-GRA-260831-0002',
            'BHP-CAL-260831-0001',
        ], Complaint::query()->orderBy('id')->pluck('ticket_number')->all());

        Carbon::setTestNow();
    }
}
