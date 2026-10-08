<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        $request->validate([
            'fname' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = strtolower(trim($request->input('email')));
        $fname = $request->input('fname') ?? $request->input('name') ?? 'Guest';
        $otp = (string) rand(100000, 999999);

        // Check if email already exists
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            if ($existingUser->otp_verified) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'email' => 'Looks like you already have an account! Please log in to continue.',
                    ]);
            }

            // If not yet verified, update OTP and name
            $existingUser->update([
                'fname' => $fname,
                'otp' => $otp,
            ]);
            $user = $existingUser;
        } else {
            // Create user in database immediately with OTP
            $user = User::create([
                'fname' => $fname,
                'email' => $email,
                'password' => Hash::make('password'),
                'otp' => $otp,
                'otp_verified' => 0,
                'created_at' => Carbon::now(),
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('client');
            }
        }

        // Store session data
        session([
            'login_user_id' => $user->id,
            'otp' => $otp,
            'email' => $email,
            'otp_email' => $email,
            'is_login' => false,
        ]);

        // Attempt sending email (catch exceptions to avoid breaking dev environment)
        try {
            Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($email) {
                $message->to($email)->subject('Maison Margiela - OTP Verification');
            });
        } catch (\Throwable $e) {
            \Log::info("Maison Margiela OTP for {$email}: {$otp}");
        }

        return redirect()->route('otp');
    }
}
