<?php

namespace Database\Factories;

use App\Models\Complaint;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Complaint>
 */
class ComplaintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_number' => (string) Str::uuid(),
            'phone' => '081234567890',
            'email' => fake()->safeEmail(),
            'type' => 'Dugaan Pelanggaran Lainnya',
            'report' => fake()->paragraph(),
            'evidence_path' => 'complaint-evidence/example.pdf',
            'evidence_original_name' => 'example.pdf',
        ];
    }
}
