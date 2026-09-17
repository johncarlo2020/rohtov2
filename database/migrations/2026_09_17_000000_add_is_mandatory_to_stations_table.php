<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('stations', 'is_mandatory')) {
            Schema::table('stations', function (Blueprint $table) {
                $table->boolean('is_mandatory')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('stations', 'is_mandatory')) {
            Schema::table('stations', function (Blueprint $table) {
                $table->dropColumn('is_mandatory');
            });
        }
    }
};
