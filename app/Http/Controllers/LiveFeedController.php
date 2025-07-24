<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class LiveFeedController extends Controller
{
    public function index()
    {
        $totalUsersCount = User::count();
        $users = User::latest()
            ->limit(30)
            ->get();
        return view('live-feed.index', compact('users', 'totalUsersCount'));
    }

    public function getData()
    {

    }

    public function trigger()
    {
        return view('gameTrigger');
    }
}
