<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'is_vip')) {
                $table->boolean('is_vip')->default(false)->after('venue');
            }
            if (!Schema::hasColumn('bookings', 'pax')) {
                $table->integer('pax')->default(1)->after('is_vip');
            }
            if (!Schema::hasColumn('bookings', 'vip_name')) {
                $table->string('vip_name')->nullable()->after('customer_name');
            }
        });

        // Change status column to string to accommodate 'Not Yet Attended', 'Attended', 'Missed', etc.
        try {
            DB::statement("ALTER TABLE `bookings` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'Not Yet Attended'");
        } catch (\Throwable $e) {
            // Ignore if already string
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['is_vip', 'pax', 'vip_name']);
        });
    }
};
