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
        return [
            'staff_member_id' => StaffMember::factory(),
            'respondent_ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}
