<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GameConfig;
use App\Events\LiveFeedEvent;

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

    public function start()
    {
        event(new LiveFeedEvent('enable-increase', null));
        return response()->json(['success' => true, 'message' => 'Game started successfully!']);
    }
}
