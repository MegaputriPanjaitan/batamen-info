<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaint_ticket_sequences', function (Blueprint $table): void {
            $table->string('prefix')->primary();
            $table->unsignedBigInteger('next_number')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_ticket_sequences');
    }
};
