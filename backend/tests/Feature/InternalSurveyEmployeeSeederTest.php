<?php

namespace Tests\Feature;

use App\Models\InternalSurveyEmployee;
use App\Models\StaffMember;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class InternalSurveyEmployeeSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_employee_nips_are_seeded_and_can_access_internal_survey(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(34, InternalSurveyEmployee::query()->count());
        $this->assertSame(34, InternalSurveyEmployee::query()->where('is_active', true)->count());
        $this->assertSame(0, StaffMember::query()->count());

        $this->post(route('internal-surveys.access'), ['nip' => '198205112005011001'])
            ->assertRedirect(config('services.internal_survey.url'));

        $this->post(route('internal-surveys.access'), ['nip' => '111111111111111111'])
            ->assertSessionHasErrors('nip', null, 'internalSurvey');

        $this->seed(DatabaseSeeder::class);

        $this->assertSame(34, InternalSurveyEmployee::query()->count());
    }
}
