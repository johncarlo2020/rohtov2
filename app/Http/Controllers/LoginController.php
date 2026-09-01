<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Countries;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
      
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required'],
        ]);


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $otp = random_int(100000, 999999);

            $user->update([
                'otp' => $otp,
                'otp_verified' => 0,
                'last_login_at' => \Carbon\Carbon::now(),
            ]);

            // Send OTP email
            try {
                \App\Helpers\GlobalHelper::sendOtpEmail($user->email, $otp, $user->fname ?? $user->name,$otpType='Login');
            } catch (\Throwable $e) {
                \Log::error('Failed to send login OTP email: ' . $e->getMessage());
            }

            return redirect()->route('otp', ['user' => $user->id]);
        }

        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->onlyInput('email');
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
