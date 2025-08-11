<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUsersType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-type {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing users type field from JSON file';

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

        $this->info('Starting type update...');
        $updated = 0;
        $notFound = 0;
        $noType = 0;

        DB::beginTransaction();
        
        try {
            foreach ($data as $userData) {
                if (!isset($userData['email'])) {
                    continue;
                }

                // Find user by email and update type
                $user = User::where('email', $userData['email'])->first();
                
                if ($user) {
                    if (isset($userData['type'])) {
                        $user->update(['type' => $userData['type']]);
                        $updated++;
                        
                        if ($updated % 100 == 0) {
                            $this->info("Updated: {$updated} users...");
                        }
                    } else {
                        $noType++;
                    }
                } else {
                    $notFound++;
                }
            }

            DB::commit();
            
            $this->info("Type update completed successfully!");
            $this->info("Updated: {$updated} users");
            $this->info("Not found: {$notFound} users");
            $this->info("No type field: {$noType} users");
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->error('Update failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
