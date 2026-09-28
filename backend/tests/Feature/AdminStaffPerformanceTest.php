<?php

namespace Tests\Feature;

use App\Models\StaffMember;
use App\Models\SurveyRating;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminStaffPerformanceTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_view_individual_staff_performance_charts(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $staff = StaffMember::factory()->create(['name' => 'Petugas Grafik', 'photo_path' => 'staff/petugas-grafik.webp']);
        $survey = SurveyResponse::factory()->for($staff)->create();

        foreach (range(1, 14) as $questionNumber) {
            SurveyRating::factory()->for($survey)->create([
                'question_key' => 'q'.$questionNumber,
                'score' => 4,
            ]);
        }

        $this->actingAs($admin)->get(route('admin.staff.performance', $staff))
            ->assertOk()
            ->assertSee('Petugas Grafik')
            ->assertSee('storage/staff/petugas-grafik.webp', false)
            ->assertSee('Foto Petugas Grafik')
            ->assertSee('Tren Nilai Petugas')
            ->assertSee('Nilai per Kategori')
            ->assertSee('Total Survei')
            ->assertSee('Rata-rata Keseluruhan')
            ->assertDontSee('Status Data')
            ->assertSee('staff-trend-chart', false)
            ->assertSee('staff-category-chart', false)
            ->assertSee('assets/staff-performance.js', false)
            ->assertSee('4,00');
    }

    public function test_guest_cannot_view_staff_performance(): void
    {
        $staff = StaffMember::factory()->create();

        $this->get(route('admin.staff.performance', $staff))->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_view_staff_performance(): void
    {
        $staff = StaffMember::factory()->create();

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.staff.performance', $staff))
            ->assertForbidden();
    }
}
