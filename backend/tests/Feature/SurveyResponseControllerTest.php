<?php

namespace Tests\Feature;

use App\Models\StaffMember;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class SurveyResponseControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_staff_survey_page_displays_only_active_database_staff(): void
    {
        $activeStaff = StaffMember::factory()->create(['name' => 'Petugas Aktif Database', 'is_active' => true]);
        $inactiveStaff = StaffMember::factory()->create(['name' => 'Petugas Nonaktif Database', 'is_active' => false]);

        $this->get(route('staff-surveys.create'))
            ->assertOk()
            ->assertSee($activeStaff->name)
            ->assertDontSee($inactiveStaff->name);
    }

    public function test_complete_ratings_create_response_and_return_201(): void
    {
        $staffMember = StaffMember::factory()->create();
        $ratings = collect(range(1, 14))->map(fn (int $number): array => [
            'question_key' => 'q'.$number,
            'score' => 5,
        ])->all();

        $this->postJson(route('survey-responses.store'), [
            'staff_member_id' => $staffMember->id,
            'ratings' => $ratings,
        ])->assertCreated()->assertJsonStructure(['message', 'response_id']);

        $this->assertDatabaseHas('survey_responses', ['staff_member_id' => $staffMember->id]);
        $this->assertDatabaseCount('survey_ratings', 14);
    }

    public function test_incomplete_ratings_return_422_without_creating_response(): void
    {
        $staffMember = StaffMember::factory()->create();

        $this->postJson(route('survey-responses.store'), [
            'staff_member_id' => $staffMember->id,
            'ratings' => [['question_key' => 'q1', 'score' => 5]],
        ])->assertUnprocessable()->assertJsonValidationErrors(['ratings']);

        $this->assertDatabaseCount('survey_responses', 0);
    }

    public function test_inactive_staff_member_returns_422(): void
    {
        $staffMember = StaffMember::factory()->create(['is_active' => false]);
        $ratings = collect(range(1, 14))->map(fn (int $number): array => [
            'question_key' => 'q'.$number,
            'score' => 5,
        ])->all();

        $this->postJson(route('survey-responses.store'), [
            'staff_member_id' => $staffMember->id,
            'ratings' => $ratings,
        ])->assertUnprocessable()->assertJsonValidationErrors(['staff_member_id']);

        $this->assertDatabaseCount('survey_responses', 0);
    }

    public function test_browser_submission_redirects_with_success_message(): void
    {
        $staffMember = StaffMember::factory()->create();
        $ratings = collect(range(1, 14))->map(fn (int $number): array => [
            'question_key' => 'q'.$number,
            'score' => 4,
        ])->all();

        $this->post(route('survey-responses.store'), [
            'staff_member_id' => $staffMember->id,
            'ratings' => $ratings,
        ])->assertRedirect(route('staff-surveys.success'))->assertSessionHas('success');

        $this->assertDatabaseCount('survey_responses', 1);
        $this->assertDatabaseCount('survey_ratings', 14);

        $this->get(route('staff-surveys.success'))
            ->assertOk()
            ->assertSee('Survei berhasil dikirim')
            ->assertSee('Kembali ke Beranda')
            ->assertDontSee('Kirim Penilaian');
    }

    public function test_success_page_without_submission_returns_to_home(): void
    {
        $this->get(route('staff-surveys.success'))->assertRedirect(route('home'));
        $this->get(route('complaints.success'))->assertRedirect(route('home'));
    }
}
