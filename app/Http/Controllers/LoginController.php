<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt via OTP.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($request->input('email')));

        $user = User::where('email', $email)->first();

        $otp = (string) rand(100000, 999999);

        if (!$user) {
            // If user doesn't exist, create automatically
            $user = User::create([
                'fname' => explode('@', $email)[0],
                'email' => $email,
                'password' => Hash::make('password'),
                'otp' => $otp,
                'otp_verified' => 0,
                'created_at' => Carbon::now(),
            ]);
            $user->assignRole('client');
        } else {
            $user->update([
                'otp' => $otp,
            ]);
        }

        session([
            'login_user_id' => $user->id,
            'otp' => $otp,
            'email' => $email,
            'otp_email' => $email,
            'is_login' => true,
        ]);

        try {
            Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($email) {
                $message->to($email)->subject('Maison Margiela - OTP Verification');
            });
        } catch (\Throwable $e) {
            \Log::info("Maison Margiela OTP for {$email}: {$otp}");
        }

        return redirect()->route('otp');
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

    public function authenticateConcierge(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/concierge/scanner');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
