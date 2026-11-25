<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view("auth.register");
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'dob' => ['required', 'date', 'before:today'],
            'number' => ['nullable', 'string', 'max:30'],
            'grc_id' => ['nullable', 'string', 'max:50'],
            'terms_conditions' => ['required', 'accepted'],
            'privacy_policy' => ['required', 'accepted'],
            'biometric_consent' => ['nullable'],
            'guardian_consent' => ['nullable'],
        ]);

        // Age verification check (< 18 years old requires guardian supervision consent)
        $age = Carbon::parse($validated['dob'])->age;
        if ($age < 18 && empty($request->guardian_consent)) {
            return back()->withErrors([
                'guardian_consent' => 'Participants under 18 years of age must have parent or guardian consent.'
            ])->withInput();
        }

        $user = User::create([
            'fname' => $validated['fname'],
            'email' => strtolower($validated['email']),
            'dob' => $validated['dob'],
            'number' => $validated['number'] ?? null,
            'grc_id' => $validated['grc_id'] ?? null,
            'guardian_consent' => $age < 18 ? true : false,
            'terms_accepted_at' => now(),
            'password' => Hash::make('password'),
            'last_login_at' => now(),
        ]);

        $user->assignRole('client');
        Auth::login($user);

        return redirect()->route('instructions');
    }
}
