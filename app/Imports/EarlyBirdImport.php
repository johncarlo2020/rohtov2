<?php

namespace App\Imports;

use App\Models\EarlyBird;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EarlyBirdImport implements ToCollection, WithHeadingRow
{
    public $imported = 0;
    public $skipped = 0;
    public $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $data = [
                'email' => strtolower(trim($row['email'] ?? '')),
                'name' => trim($row['name'] ?? ''),
                'source_of_channel' => strtolower($row['source_of_channel'] ?? ''),
            ];

            // ✅ Validate email
            $validator = Validator::make($data, [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $this->skipped++;
                $this->errors[] = "Row " . ($index + 2) . ": Invalid email";
                continue;
            }

            // ✅ Prevent duplicates (DB level)
            $exists = EarlyBird::where('email', $data['email'])->exists();

            if ($exists) {
                $this->skipped++;
                continue;
            }

            // ✅ Insert
            EarlyBird::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'source_of_channel' => $data['source_of_channel'],
                'claimed' => false,
            ]);

            $this->imported++;
        }
    }
}