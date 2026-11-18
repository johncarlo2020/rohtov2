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
        Schema::create('porsche_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('contact_number');
            $table->string('email');
            $table->boolean('consent_given')->default(false);
            $table->timestamp('consent_timestamp')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('porsche_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('porsche_registration_id')->constrained('porsche_registrations')->onDelete('cascade');
            $table->string('question_1_answer', 5);
            $table->string('question_2_answer', 5);
            $table->string('question_3_answer', 5);
            $table->string('question_4_answer', 5);
            $table->unsignedTinyInteger('score_a')->default(0);
            $table->unsignedTinyInteger('score_b')->default(0);
            $table->unsignedTinyInteger('score_c')->default(0);
            $table->string('result_type', 10); // 'A', 'B', 'C'
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('porsche_quiz_attempts');
        Schema::dropIfExists('porsche_registrations');
    }
};
