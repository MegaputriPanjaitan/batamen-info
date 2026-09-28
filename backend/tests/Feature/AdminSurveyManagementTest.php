<?php

namespace Tests\Feature;

use App\Models\StaffMember;
use App\Models\SurveyRating;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminSurveyManagementTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_admin_can_filter_and_view_survey_details(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $selectedStaff = StaffMember::factory()->create(['name' => 'Petugas Pilihan', 'photo_path' => 'staff/petugas-pilihan.webp']);
        $otherStaff = StaffMember::factory()->create(['name' => 'Petugas Lain']);
        $survey = SurveyResponse::factory()->for($selectedStaff)->create();
        SurveyRating::factory()->for($survey)->create(['question_key' => 'q1', 'score' => 4]);
        $otherSurvey = SurveyResponse::factory()->for($otherStaff)->create();

        $this->actingAs($admin)->get(route('admin.surveys.index', ['staff_member_id' => $selectedStaff->id]))
            ->assertOk()
            ->assertSee('Petugas Pilihan')
            ->assertSee('Peringkat Performa Petugas')
            ->assertSee('Kelola Petugas')
            ->assertSee(route('admin.staff.index'), false)
            ->assertDontSee('Ekspor CSV')
            ->assertDontSee('Grafik Setiap Petugas')
            ->assertDontSee('Data Survei Masuk')
            ->assertSee('Peringkat')
            ->assertSee('storage/staff/petugas-pilihan.webp', false)
            ->assertSee('Foto Petugas Pilihan')
            ->assertSee(route('admin.staff.performance', $selectedStaff), false)
            ->assertDontSee(route('admin.surveys.show', $otherSurvey));

        $this->actingAs($admin)->get(route('admin.surveys.show', $survey))
            ->assertOk()
            ->assertSee('Rincian Penilaian')
            ->assertSee('4 / 5');
    }

    public function test_staff_performance_is_ranked_by_average_score(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $lowerStaff = StaffMember::factory()->create(['name' => 'Petugas Nilai Rendah']);
        $higherStaff = StaffMember::factory()->create(['name' => 'Petugas Nilai Tinggi']);
        $lowerSurvey = SurveyResponse::factory()->for($lowerStaff)->create();
        $higherSurvey = SurveyResponse::factory()->for($higherStaff)->create();
        SurveyRating::factory()->for($lowerSurvey)->create(['score' => 3]);
        SurveyRating::factory()->for($higherSurvey)->create(['score' => 5]);

        $this->actingAs($admin)->get(route('admin.surveys.index'))
            ->assertOk()
            ->assertSeeInOrder(['#1', 'Petugas Nilai Tinggi', '#2', 'Petugas Nilai Rendah']);
    }

    public function test_admin_can_export_surveys_to_csv(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $staff = StaffMember::factory()->create(['name' => 'Petugas Ekspor']);
        $survey = SurveyResponse::factory()->for($staff)->create();
        SurveyRating::factory()->for($survey)->create(['score' => 5]);

        $this->actingAs($admin)->get(route('admin.surveys.export'))
            ->assertOk()
            ->assertDownload('laporan-survei-'.now()->format('Y-m-d').'.csv');
    }

    public function test_guest_cannot_access_survey_management(): void
    {
        $this->get(route('admin.surveys.index'))->assertRedirect(route('login'));
    }
}
