<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //

         if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Check if user has completed station 6
            $stationCount = $user->stationUser()->where('station_id', 6)->count();

            if ($stationCount > 0 || $user->hasRedeemed === true) {
                return redirect()->route('regCongrats');
            } else {
                return redirect()->intended('appointment');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function authenticateAdmin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->can('full')) {
                return redirect()->intended('admin');
            }

            if ($user->can('view')) {
                return redirect()->intended('admin/scanner');
            }

            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'You do not have the required permissions.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
