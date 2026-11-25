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
            if (!Schema::hasColumn('users', 'grc_id')) {
                $table->string('grc_id')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'guardian_consent')) {
                $table->boolean('guardian_consent')->default(false)->after('dob');
            }
            if (!Schema::hasColumn('users', 'terms_accepted_at')) {
                $table->timestamp('terms_accepted_at')->nullable()->after('guardian_consent');
            }
        });

        Schema::table('station_users', function (Blueprint $table) {
            $table->unique(['user_id', 'station_id'], 'station_users_user_station_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('station_users', function (Blueprint $table) {
            $table->dropUnique('station_users_user_station_unique');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['grc_id', 'guardian_consent', 'terms_accepted_at']);
        });
    }
};
