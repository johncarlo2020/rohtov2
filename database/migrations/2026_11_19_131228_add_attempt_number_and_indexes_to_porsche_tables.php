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
        if (Schema::hasTable('porsche_quiz_attempts') && !Schema::hasColumn('porsche_quiz_attempts', 'attempt_number')) {
            Schema::table('porsche_quiz_attempts', function (Blueprint $table) {
                $table->unsignedInteger('attempt_number')->default(1)->after('porsche_registration_id');
                $table->index(['porsche_registration_id', 'attempt_number'], 'idx_pqa_reg_attempt');
                $table->index('result_type', 'idx_pqa_result_type');
                $table->index('completed_at', 'idx_pqa_completed_at');
            });

            // Backfill sequential attempt numbers for any existing records
            $registrationIds = DB::table('porsche_quiz_attempts')->distinct()->pluck('porsche_registration_id');
            foreach ($registrationIds as $regId) {
                $attempts = DB::table('porsche_quiz_attempts')
                    ->where('porsche_registration_id', $regId)
                    ->orderBy('id', 'asc')
                    ->get();
                $seq = 1;
                foreach ($attempts as $attempt) {
                    DB::table('porsche_quiz_attempts')
                        ->where('id', $attempt->id)
                        ->update(['attempt_number' => $seq++]);
                }
            }
        }

        if (Schema::hasTable('porsche_registrations')) {
            Schema::table('porsche_registrations', function (Blueprint $table) {
                $table->index('email', 'idx_pr_email');
                $table->index('created_at', 'idx_pr_created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('porsche_quiz_attempts') && Schema::hasColumn('porsche_quiz_attempts', 'attempt_number')) {
            Schema::table('porsche_quiz_attempts', function (Blueprint $table) {
                $table->dropIndex('idx_pqa_reg_attempt');
                $table->dropIndex('idx_pqa_result_type');
                $table->dropIndex('idx_pqa_completed_at');
                $table->dropColumn('attempt_number');
            });
        }

        if (Schema::hasTable('porsche_registrations')) {
            Schema::table('porsche_registrations', function (Blueprint $table) {
                $table->dropIndex('idx_pr_email');
                $table->dropIndex('idx_pr_created_at');
            });
        }
    }
};
