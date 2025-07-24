<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GameConfig;

class LiveFeedController extends Controller
{
    public function index()
    {
        $totalUsersCount = User::whereNotNull('avatar_id')->count();
        $users = User::whereNotNull('avatar_id')
            ->latest()
            ->limit(30)
            ->get();

        // Get game configuration for the live feed
        $gameConfig = GameConfig::getActive();

        return view('live-feed.index', compact('users', 'totalUsersCount', 'gameConfig'));
    }

    public function getData()
    {

    }

    public function trigger()
    {
        return view('gameTrigger');
    }
}
