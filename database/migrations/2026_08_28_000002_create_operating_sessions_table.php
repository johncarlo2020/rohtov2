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
        Schema::create('operating_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operating_hour_id')->constrained('operating_hours')->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('capacity')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operating_sessions');
    }
};
