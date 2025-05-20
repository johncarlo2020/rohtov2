<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'number' => ['required', 'string'], // Changed from email to number
            'password' => ['required'],
        ]);

        // Prepare credentials for Auth::attempt
        // Auth::attempt expects an array with 'email' or other unique identifier key that your User model uses for authentication.
        // If your User model uses 'number' as the column for phone numbers, and you want to log in with it:
        $authCredentials = [
            'number' => $credentials['number'],
            'password' => $credentials['password'],
        ];

        if (Auth::attempt($authCredentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'number' => 'The provided credentials do not match our records.', // Changed from email to number
        ])->onlyInput('number'); // Changed from email to number
    }

    public function authenticateAdmin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('admin');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
