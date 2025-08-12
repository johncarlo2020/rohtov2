<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateUsersCreatedDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-created-date {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing users created_at field from JSON file';

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

        $this->info('Starting created_at date update...');
        $updated = 0;
        $notFound = 0;
        $noCreatedAt = 0;
        $invalidDate = 0;

        DB::beginTransaction();

        try {
            foreach ($data as $userData) {
                if (!isset($userData['email'])) {
                    continue;
                }

                // Find user by email and update created_at
                $user = User::where('email', $userData['email'])->first();

                if ($user) {
                    if (isset($userData['created_at'])) {
                        try {
                            // Parse the date from JSON
                            $createdAt = Carbon::parse($userData['created_at']);
                            $updatedAt = isset($userData['updated_at']) ? Carbon::parse($userData['updated_at']) : $createdAt;

                            // Use DB update to bypass fillable restrictions and timestamps
                            DB::table('users')
                                ->where('email', $userData['email'])
                                ->update([
                                    'created_at' => $createdAt,
                                    'updated_at' => $updatedAt
                                ]);

                            $updated++;

                            if ($updated % 100 == 0) {
                                $this->info("Updated: {$updated} users...");
                            }
                        } catch (\Exception $e) {
                            $invalidDate++;
                            $this->error("Invalid date format for user {$userData['email']}: {$userData['created_at']}");
                        }
                    } else {
                        $noCreatedAt++;
                    }
                } else {
                    $notFound++;
                }
            }

            DB::commit();

            $this->info("Created date update completed successfully!");
            $this->info("Updated: {$updated} users");
            $this->info("Not found: {$notFound} users");
            $this->info("No created_at field: {$noCreatedAt} users");
            $this->info("Invalid date format: {$invalidDate} users");

        } catch (\Exception $e) {
            DB::rollback();
            $this->error('Update failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
