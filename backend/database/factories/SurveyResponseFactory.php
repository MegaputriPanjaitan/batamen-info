<?php

namespace Database\Factories;

use App\Models\StaffMember;
use App\Models\SurveyResponse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyResponse>
 */
class SurveyResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $phone = fake()->unique()->numerify('08##########');

        return [
            'staff_member_id' => StaffMember::factory(),
            'respondent_phone' => $phone,
            'respondent_phone_hash' => hash_hmac('sha256', $phone, (string) config('app.key')),
            'service_slug' => 'perwalian',
            'survey_date' => today(),
            'respondent_ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}
