<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['terms', 'age_confirmed'] as $column) {
            if (! Schema::hasColumn('users', $column)) {
                Schema::table('users', function (Blueprint $table) use ($column) {
                    // Existing users have not supplied these new confirmations.
                    $table->boolean($column)->default(false);
                });
            }
        }

        foreach (['otp', 'otp_verified'] as $column) {
            if (Schema::hasColumn('users', $column)) {
                Schema::table('users', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'otp')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('otp')->nullable();
            });
        }
        if (! Schema::hasColumn('users', 'otp_verified')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('otp_verified')->default(false);
            });
        }

        foreach (['terms', 'age_confirmed'] as $column) {
            if (Schema::hasColumn('users', $column)) {
                Schema::table('users', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
