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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'title')) {
                $table->string('title')->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'preferred_contact')) {
                $table->string('preferred_contact')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'communication_consent')) {
                $table->boolean('communication_consent')->default(false)->after('preferred_contact');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['title', 'preferred_contact', 'communication_consent']);
        });
    }
};
