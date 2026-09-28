<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminComplaintManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_filter_and_view_complaints(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        Complaint::factory()->create(['ticket_number' => '11111111-1111-4111-8111-111111111111', 'type' => 'Dugaan Pelanggaran Gratifikasi']);
        Complaint::factory()->create(['ticket_number' => '22222222-2222-4222-8222-222222222222', 'type' => 'Dugaan Pelanggaran Calo']);

        $this->actingAs($admin)->get(route('admin.complaints.index', [
            'type' => 'Dugaan Pelanggaran Gratifikasi',
        ]))
            ->assertOk()
            ->assertSee('Semua jenis pengaduan')
            ->assertDontSee('Semua status')
            ->assertDontSee('Seluruh Pengaduan')
            ->assertSee('No.')
            ->assertSee('11111111-1111-4111-8111-111111111111')
            ->assertDontSee('22222222-2222-4222-8222-222222222222');
    }

    public function test_admin_can_download_private_evidence(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['is_admin' => true]);
        $complaint = Complaint::factory()->create();
        Storage::disk('local')->put($complaint->evidence_path, 'evidence');

        $this->actingAs($admin)->get(route('admin.complaints.evidence', $complaint))
            ->assertOk()
            ->assertDownload('Bukti-Pengaduan-'.$complaint->ticket_number.'.'.pathinfo($complaint->evidence_path, PATHINFO_EXTENSION));
    }

    public function test_admin_can_download_filled_complaint_form(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $complaint = Complaint::factory()->create(['report' => "Kronologi pengaduan untuk dokumen.\nBaris kedua <script>alert('x')</script>."]);

        $this->actingAs($admin)->get(route('admin.complaints.form', $complaint))
            ->assertOk()
            ->assertDownload('Form-Pengaduan-'.$complaint->ticket_number.'.doc')
            ->assertSee('FORMULIR PENGADUAN MASYARAKAT')
            ->assertSeeInOrder(['A.', 'Informasi Pengaduan', 'B.', 'Data Kontak Pelapor', 'C.', 'Uraian Pengaduan'])
            ->assertDontSee('Lampiran Bukti')
            ->assertDontSee('Nama Berkas')
            ->assertDontSee('Dokumen ini dibuat secara otomatis')
            ->assertSee('Kronologi pengaduan untuk dokumen.')
            ->assertSee('Baris kedua &lt;script&gt;', false)
            ->assertDontSee("<script>alert('x')</script>", false)
            ->assertDontSee('border:1px solid #b9cbd6', false);

        $this->actingAs($admin)->get(route('admin.complaints.show', $complaint))
            ->assertOk()
            ->assertSee('Unduh Form Pengaduan')
            ->assertDontSee('Detail Pengaduan')
            ->assertDontSee('Tindak Lanjut');
    }

    public function test_non_admin_cannot_manage_complaints(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.complaints.index'))
            ->assertForbidden();
    }
}
