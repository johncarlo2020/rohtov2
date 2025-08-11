<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ImportUsersFromJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:import-json {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from JSON file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON format: ' . json_last_error_msg());
            return 1;
        }

        $this->info('Starting import...');
        $imported = 0;
        $skipped = 0;

        DB::beginTransaction();

        try {
            foreach ($data as $userData) {
                // Check if user already exists by email
                if (isset($userData['email']) && User::where('email', $userData['email'])->exists()) {
                    $skipped++;
                    continue;
                }

                // Create user with available data
                User::create([
                    'fname' => $userData['fname'] ?? '',
                    'lname' => $userData['lname'] ?? '',
                    'email' => $userData['email'] ?? '',
                    'dob' => $userData['dob'] ?? null,
                    'number' => $userData['number'] ?? '',
                    'country' => $userData['country'] ?? '',
                    'utm_source' => $userData['utm_source'] ?? null,
                    'utm_medium' => $userData['utm_medium'] ?? null,
                    'otp' => $userData['otp'] ?? null,
                    'otp_verified' => $userData['otp_verified'] ?? false,
                    'sms_consent' => $userData['sms_consent'] ?? false,
                    'email_consent' => $userData['email_consent'] ?? false,
                    'alliance_bank' => $userData['alliance_bank'] ?? false,
                    'hasRedeemed' => $userData['hasRedeemed'] ?? false,
                    'password' => $userData['password'] ?? null,
                    'created_at' => $userData['created_at'] ?? now(),
                    'updated_at' => $userData['updated_at'] ?? now(),
                ]);

                $imported++;
            }

            DB::commit();

            $this->info("Import completed successfully!");
            $this->info("Imported: {$imported} users");
            $this->info("Skipped: {$skipped} users (duplicates)");

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
