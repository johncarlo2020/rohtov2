<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('redemption_stamped_at')->nullable()->after('redemption_stamped');
            $table->timestamp('in_store_stamped_at')->nullable()->after('in_store_stamped');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['redemption_stamped_at', 'in_store_stamped_at']);
        });
    }
};
