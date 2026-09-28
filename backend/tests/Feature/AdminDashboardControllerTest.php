<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\ServiceAccessEvent;
use App\Models\StaffMember;
use App\Models\SurveyRating;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminDashboardControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_non_administrator_is_forbidden(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_administrator_can_view_dashboard_summary(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $staff = StaffMember::factory()->create(['name' => 'Petugas Teladan']);
        $survey = SurveyResponse::factory()->for($staff)->create();
        SurveyRating::factory()->for($survey)->create(['score' => 5]);
        Complaint::factory()->create([
            'ticket_number' => 'BHP-TEST-001',
            'type' => 'Dugaan Pelanggaran Gratifikasi',
        ]);
        ServiceAccessEvent::create(['service' => ServiceAccessEvent::SPKP_SPAK]);
        ServiceAccessEvent::create(['service' => ServiceAccessEvent::INTERNAL_INTEGRITY]);

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Admin')
            ->assertSee('admin-back-button', false)
            ->assertSee('assets/logo-bhp-medan-display.png', false)
            ->assertSeeInOrder(['Dashboard', 'Pengaduan', 'Survei Petugas'])
            ->assertDontSee('Administrator BHP Medan')
            ->assertSee('Jumlah Pengaduan dalam Enam Bulan Terakhir')
            ->assertSee('service-trend-chart', false)
            ->assertSee('service-trend-detail', false)
            ->assertSee('data-chart-complaints', false)
            ->assertDontSee('data-chart-surveys', false)
            ->assertSee('assets/admin-dashboard.js', false)
            ->assertSee('Survei Petugas Terbaru')
            ->assertDontSee('Jenis Pengaduan Terbanyak')
            ->assertDontSee('Ringkasan laporan masyarakat')
            ->assertDontSee('Ringkasan penilaian pelayanan')
            ->assertSee('Total Survei Petugas')
            ->assertSee('Rata-rata Nilai Petugas')
            ->assertSeeInOrder(['Total Pengaduan', 'Total Survei Petugas', 'Total SPKP / SPAK', 'Total Survei Integritas', 'Rata-rata Nilai Petugas'])
            ->assertSee('Survei terkirim')
            ->assertDontSee('Pengaduan Bulan Ini')
            ->assertDontSee('Pengaduan 3 Hari Terakhir')
            ->assertDontSee('Survei Bulan Ini')
            ->assertDontSee('Perlu ditindaklanjuti')
            ->assertDontSee('Pengaduan baru')
            ->assertDontSee('Sedang diproses')
            ->assertDontSee('Status</th>', false)
            ->assertDontSee('Tindakan Cepat')
            ->assertDontSee('Status Pengaduan')
            ->assertSee(route('admin.complaints.index'), false)
            ->assertSee(route('admin.surveys.index'), false)
            ->assertSee('Pengaduan Terbaru')
            ->assertDontSee('Tiga data terakhir')
            ->assertSee('No.')
            ->assertSee('BHP-TEST-001')
            ->assertSee('Petugas Teladan')
            ->assertSee('5,00');
    }

    public function test_dashboard_only_displays_three_latest_surveys(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        foreach (range(1, 4) as $index) {
            $staff = StaffMember::factory()->create(['name' => 'Petugas Urutan '.$index]);
            SurveyResponse::factory()->for($staff)->create(['created_at' => now()->subDays(5 - $index)]);
        }

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk()
            ->assertDontSee('Petugas Urutan 1')
            ->assertSee('Petugas Urutan 2')
            ->assertSee('Petugas Urutan 3')
            ->assertSee('Petugas Urutan 4');
    }
}
