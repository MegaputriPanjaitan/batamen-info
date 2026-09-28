<?php

namespace Database\Factories;

use App\Models\SurveyRating;
use App\Models\SurveyResponse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SurveyRating>
 */
class SurveyRatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'survey_response_id' => SurveyResponse::factory(),
            'question_key' => 'q'.fake()->numberBetween(1, 14),
            'score' => fake()->numberBetween(1, 5),
        ];
    }
}
