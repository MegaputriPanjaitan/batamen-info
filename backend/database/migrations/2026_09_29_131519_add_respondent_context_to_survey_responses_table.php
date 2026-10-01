<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->char('respondent_phone_hash', 64)->nullable()->after('staff_member_id');
            $table->string('service_slug', 80)->nullable()->after('respondent_phone_hash');
            $table->date('survey_date')->nullable()->after('service_slug');
            $table->unique(
                ['respondent_phone_hash', 'service_slug', 'staff_member_id', 'survey_date'],
                'survey_response_daily_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropUnique('survey_response_daily_unique');
            $table->dropColumn(['respondent_phone_hash', 'service_slug', 'survey_date']);
        });
    }
};
