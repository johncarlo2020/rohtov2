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
        Schema::table('porsche_registrations', function (Blueprint $table) {
            try {
                $table->dropIndex('idx_pr_email');
            } catch (\Throwable $e) {
                // Ignore if index doesn't exist
            }

            $table->unique('email', 'porsche_registrations_email_unique');
            $table->unique('contact_number', 'porsche_registrations_contact_number_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('porsche_registrations', function (Blueprint $table) {
            $table->dropUnique('porsche_registrations_email_unique');
            $table->dropUnique('porsche_registrations_contact_number_unique');
            $table->index('email', 'idx_pr_email');
        });
    }
};
