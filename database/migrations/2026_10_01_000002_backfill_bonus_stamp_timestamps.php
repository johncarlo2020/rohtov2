<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Existing stamped users have no timestamp yet; backfill with now() so today's daily-limit count includes them.
        DB::table('users')
            ->where('redemption_stamped', true)
            ->whereNull('redemption_stamped_at')
            ->update(['redemption_stamped_at' => now()]);

        DB::table('users')
            ->where('in_store_stamped', true)
            ->whereNull('in_store_stamped_at')
            ->update(['in_store_stamped_at' => now()]);
    }

    public function down(): void
    {
        // No-op: backfilled timestamps are not reversible.
    }
};
