<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffIds = [
            'YLF645', 'YLF262', 'YLF683', 'YLF637', 'YLF612',
            'YLF553', 'YLF628', 'YLF648', 'YLF531', 'YLF207',
            'YLF599', 'YLF651', 'YLF651', 'YLF620', 'YLF681',
            'YLF610', 'YLF686', 'YLF571', 'YLF516', 'YLF006',
            'YLF682', 'YLF653', 'YLF618', 'YLF500', 'YLF526',
            'YLF240', 'YLF016', 'YLF673', 'YLF533', 'YLF176',
            'YLF660', 'YLF038', 'YLF770', 'YLF684',
        ];

        foreach ($staffIds as $staffId) {
            DB::table('staff')->insert([
                'staff_id' => $staffId,
            ]);
        }
    }
}
