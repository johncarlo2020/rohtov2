<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\StationUser;

class Redemption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:redemption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::where('id','<',732)->has('stationUser', '=', 4)->get();
        foreach ($users as $user) {
            $stationUser = new StationUser();
            $stationUser->user_id = $user->id;
            $stationUser->station_id = 5;
            $stationUser->time_spent = 120;
            $stationUser->save();
            $this->info("User ID: {$user->id}, Station User Count: " . $user->stationUser->count());
        }
    }
}
