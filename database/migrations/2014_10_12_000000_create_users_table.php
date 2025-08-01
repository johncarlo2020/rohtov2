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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('find')->nullable();;
            $table->string('dob')->nullable();;
            $table->string('password')->nullable();;
            $table->string('number')->nullable();;
            $table->string('country')->nullable();;
            $table->string('email')->unique()->nullable();;
            $table->string('pledge_image')->nullable();
            $table->string('game_status')->nullable();
            $table->text('pledge_text')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
