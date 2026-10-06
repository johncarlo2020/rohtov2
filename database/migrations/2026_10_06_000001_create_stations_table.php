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
        if (!Schema::hasTable('stations')) {
            Schema::create('stations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->string('image')->nullable();
                $table->text('description')->nullable();
                $table->text('question')->nullable();
                $table->unsignedBigInteger('answer_id')->nullable();
                $table->boolean('is_redemption')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('station_users')) {
            Schema::create('station_users', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('station_id')->constrained()->onDelete('cascade');
                $table->integer('time_spent')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('station_users');
        Schema::dropIfExists('stations');
    }
};
