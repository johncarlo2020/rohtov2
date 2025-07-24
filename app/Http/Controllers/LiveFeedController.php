<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LiveFeedController extends Controller
{
    public function index()
    {
        return view('live-feed.index');
    }
}
