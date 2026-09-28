<?php

namespace Database\Seeders;

use App\Models\StaffMember;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (config('admin.name') && config('admin.email') && config('admin.password')) {
            User::query()->updateOrCreate(
                ['email' => config('admin.email')],
                [
                    'name' => config('admin.name'),
                    'password' => config('admin.password'),
                    'is_admin' => true,
                ],
            );
        }

        StaffMember::query()->firstOrCreate(['name' => 'Nama Petugas 1'], ['position' => 'Petugas Pelayanan']);
        StaffMember::query()->firstOrCreate(['name' => 'Nama Petugas 2'], ['position' => 'Petugas Pelayanan']);
        StaffMember::query()->firstOrCreate(['name' => 'Nama Petugas 3'], ['position' => 'Petugas Pelayanan']);
    }
}
