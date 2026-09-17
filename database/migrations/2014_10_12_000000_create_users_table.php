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
            $table->string('lname');
            $table->string('password');
            $table->string('dob');
            $table->string('number');
            $table->string('country');
            $table->string('email')->unique();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('marketing')->default(false);
            $table->string('guess')->nullable();
            $table->string('task_2_image')->nullable();
            $table->string('task_3_image')->nullable();
            $table->string('type')->nullable(); // user, admin, super-admin
            $table->string('utm_source')->default('walkin');
            $table->string('utm_medium')->default('walkin');
            $table->boolean('sms_consent')->default(true);
            $table->boolean('email_consent')->default(true);
            $table->string('redeem_date')->nullable();
            $table->boolean('alliance_bank')->default(false);
            $table->boolean('hasRedeemed')->default(false);
            $table->boolean('terms')->default(false);
            $table->boolean('age_confirmed')->default(false);
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
