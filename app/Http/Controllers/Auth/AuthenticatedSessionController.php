<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /** 
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $otp = random_int(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_verified' => 0,
            'last_login_at' => Carbon::now(),
        ]);

        // Send OTP email
        try {
            \App\Helpers\GlobalHelper::sendOtpEmail($user->email, $otp, $user->fname ?? $user->name);
        } catch (\Throwable $e) {
            \Log::error('Failed to send login OTP email: ' . $e->getMessage());
        }

        return redirect()->route('otp', ['user' => $user->id]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
