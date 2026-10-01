<?php

namespace Tests\Feature;

use App\Models\StaffMember;
use App\Models\SurveyResponse;
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
            ->assertSee('name="respondent_phone"', false)
            ->assertSee('name="service_slug"', false)
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
            'respondent_phone' => '+62 812-3456-7890',
            'service_slug' => 'perwalian',
            'staff_member_id' => $staffMember->id,
            'ratings' => $ratings,
        ])->assertCreated()->assertJsonStructure(['message', 'response_id']);

        $this->assertDatabaseHas('survey_responses', [
            'staff_member_id' => $staffMember->id,
            'service_slug' => 'perwalian',
            'respondent_phone_hash' => hash_hmac('sha256', '081234567890', (string) config('app.key')),
        ]);
        $this->assertSame('081234567890', SurveyResponse::query()->firstOrFail()->respondent_phone);
        $this->assertDatabaseCount('survey_ratings', 14);
    }

    public function test_incomplete_ratings_return_422_without_creating_response(): void
    {
        $staffMember = StaffMember::factory()->create();

        $this->postJson(route('survey-responses.store'), [
            'respondent_phone' => '081234567891',
            'service_slug' => 'perwalian',
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
            'respondent_phone' => '081234567892',
            'service_slug' => 'perwalian',
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
            'respondent_phone' => '081234567893',
            'service_slug' => 'perwalian',
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

    public function test_same_phone_service_and_staff_cannot_submit_twice_on_same_day(): void
    {
        $staff = StaffMember::factory()->create(['service_slugs' => ['perwalian']]);
        $payload = [
            'respondent_phone' => '0812 3456 7894',
            'service_slug' => 'perwalian',
            'staff_member_id' => $staff->id,
            'ratings' => $this->ratings(),
        ];

        $this->postJson(route('survey-responses.store'), $payload)->assertCreated();
        $this->postJson(route('survey-responses.store'), $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['respondent_phone']);

        $this->assertDatabaseCount('survey_responses', 1);
        $this->assertDatabaseCount('survey_ratings', 14);
    }

    public function test_availability_marks_only_staff_already_rated_for_same_phone_service_and_day(): void
    {
        $ratedStaff = StaffMember::factory()->create(['service_slugs' => ['perwalian']]);
        $availableStaff = StaffMember::factory()->create(['service_slugs' => ['perwalian']]);
        $otherServiceStaff = StaffMember::factory()->create(['service_slugs' => ['pengampuan']]);
        $phoneHash = hash_hmac('sha256', '081234567898', (string) config('app.key'));

        SurveyResponse::factory()->create([
            'staff_member_id' => $ratedStaff->id,
            'respondent_phone_hash' => $phoneHash,
            'service_slug' => 'perwalian',
            'survey_date' => today(),
        ]);
        SurveyResponse::factory()->create([
            'staff_member_id' => $otherServiceStaff->id,
            'respondent_phone_hash' => $phoneHash,
            'service_slug' => 'pengampuan',
            'survey_date' => today(),
        ]);

        $this->postJson(route('survey-responses.availability'), [
            'respondent_phone' => '+62 812-3456-7898',
            'service_slug' => 'perwalian',
        ])->assertOk()
            ->assertExactJson(['rated_staff_ids' => [$ratedStaff->id]])
            ->assertJsonMissing(['rated_staff_ids' => [$availableStaff->id]])
            ->assertJsonMissing(['rated_staff_ids' => [$otherServiceStaff->id]]);
    }

    public function test_same_phone_can_rate_different_staff_for_same_service_on_same_day(): void
    {
        $firstStaff = StaffMember::factory()->create(['service_slugs' => ['perwalian']]);
        $secondStaff = StaffMember::factory()->create(['service_slugs' => ['perwalian']]);
        $payload = [
            'respondent_phone' => '081234567895',
            'service_slug' => 'perwalian',
            'ratings' => $this->ratings(),
        ];

        $this->postJson(route('survey-responses.store'), $payload + ['staff_member_id' => $firstStaff->id])->assertCreated();
        $this->postJson(route('survey-responses.store'), $payload + ['staff_member_id' => $secondStaff->id])->assertCreated();

        $this->assertDatabaseCount('survey_responses', 2);
    }

    public function test_staff_must_be_assigned_to_selected_service(): void
    {
        $staff = StaffMember::factory()->create(['service_slugs' => ['pengampuan']]);

        $this->postJson(route('survey-responses.store'), [
            'respondent_phone' => '081234567896',
            'service_slug' => 'perwalian',
            'staff_member_id' => $staff->id,
            'ratings' => $this->ratings(),
        ])->assertUnprocessable()->assertJsonValidationErrors(['staff_member_id']);

        $this->assertDatabaseCount('survey_responses', 0);
    }

    public function test_same_combination_can_submit_again_on_another_day(): void
    {
        $staff = StaffMember::factory()->create(['service_slugs' => ['perwalian']]);
        $payload = [
            'respondent_phone' => '081234567897',
            'service_slug' => 'perwalian',
            'staff_member_id' => $staff->id,
            'ratings' => $this->ratings(),
        ];

        $this->postJson(route('survey-responses.store'), $payload)->assertCreated();
        $this->travel(1)->day();
        $this->postJson(route('survey-responses.store'), $payload)->assertCreated();

        $this->assertDatabaseCount('survey_responses', 2);
    }

    /** @return array<int, array{question_key: string, score: int}> */
    private function ratings(int $score = 5): array
    {
        return collect(range(1, 14))->map(fn (int $number): array => [
            'question_key' => 'q'.$number,
            'score' => $score,
        ])->all();
    }
}
