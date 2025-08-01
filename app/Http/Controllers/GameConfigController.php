<?php

namespace App\Http\Controllers;

use App\Models\GameConfig;
use App\Events\LiveFeedEvent;
use Illuminate\Http\Request;
use App\Models\User;

class GameConfigController extends Controller
{
    /**
     * Show the game configuration page
     */
    public function index()
    {
        $config = GameConfig::getActive();
        return view('gameConfig', compact('config'));
    }


    public function resetGame()
    {
       // get all user records with game_status 'active'
        $activeUsers = User::where('game_status', 'active')->get();
        // reset their game_status to 'completed'
        foreach ($activeUsers as $user) {
            $user->game_status = 'completed';
            $user->save();
        }

        return redirect()->route('game.config')->with('success', 'Game has been reset successfully.');
    }

    /**
     * Save the game configuration
     */
    public function store(Request $request)
    {
        $request->validate([
            'max_weight' => 'required|numeric|min:1',
            'increment_grams' => 'required|integer|min:10'
        ]);

        // Get the current active config or create new one
        $config = GameConfig::where('is_active', true)->first();

        if ($config) {
            // Update existing active config
            $config->update([
                'max_weight' => $request->max_weight,
                'increment_grams' => $request->increment_grams
            ]);
        } else {
            // Create new config if none exists
            $config = GameConfig::create([
                'max_weight' => $request->max_weight,
                'increment_grams' => $request->increment_grams,
                'is_active' => true
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Configuration saved successfully!',
            'config' => $config
        ]);
    }

    /**
     * Get the current active configuration (API endpoint)
     */
    public function getActive()
    {
        $config = GameConfig::getActive();

        return response()->json([
            'success' => true,
            'max_weight' => $config->max_weight ?? 4.0,
            'increment_grams' => $config->increment_grams ?? 100
        ]);
    }

    /**
     * Show the game trigger page
     */
    public function trigger()
    {
        $config = GameConfig::getActive();
        return view('gameTrigger', compact('config'));
    }

    /**
     * Trigger a live feed event
     */
    public function triggerLiveFeed(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'data' => 'nullable|array'
        ]);

        // Trigger the live feed event
        event(new LiveFeedEvent($request->action, $request->data));

        return response()->json([
            'success' => true,
            'message' => 'Live feed event triggered successfully'
        ]);
    }
}
