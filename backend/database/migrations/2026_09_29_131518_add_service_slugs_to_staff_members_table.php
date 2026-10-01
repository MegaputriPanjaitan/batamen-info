<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('staff_members', function (Blueprint $table) {
            $table->json('service_slugs')->nullable()->after('position');
        });

        DB::table('staff_members')->update([
            'service_slugs' => json_encode(array_keys(config('public_services')), JSON_THROW_ON_ERROR),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_members', function (Blueprint $table) {
            $table->dropColumn('service_slugs');
        });
    }
};
