<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('stations')->where('name', 'Maybank Cafe')->update(['name' => 'Cafe']);
    }

    public function down(): void
    {
        DB::table('stations')->where('name', 'Cafe')->update(['name' => 'Maybank Cafe']);
    }
};
