<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RewardController extends Controller
{
    //
    public function index()
    {
        return view('rewards.index');
    }

    public function store(Request $request)
    {

    }

    public function edit(Reward $reward)
    {

    }

    public function update(Request $request, Reward $reward)
    {

    }

    public function destroy(Reward $reward)
    {

    }
}
