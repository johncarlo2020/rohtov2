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
            if (!Schema::hasColumn('users', 'consent_channels')) {
                $table->json('consent_channels')->nullable()->after('preferred_contact');
            }
            if (!Schema::hasColumn('users', 'newsletter_consent')) {
                $table->boolean('newsletter_consent')->default(false)->after('communication_consent');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('users', 'consent_channels')) {
                $columnsToDrop[] = 'consent_channels';
            }
            if (Schema::hasColumn('users', 'newsletter_consent')) {
                $columnsToDrop[] = 'newsletter_consent';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
