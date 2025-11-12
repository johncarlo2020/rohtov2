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
        // Validate the input first
        $credentials = $request->validate([
            'number' => ['required'],
            'password' => ['required'],
        ]);

        // Get the raw number from input
       $rawNumber = $request->input('number');
       $countryInput = $request->input('country');


        // Determine country code dynamically if user provided full number
        // e.g., input: +60123456788 or 0123456788
        $phonePrefix = '+' . substr($countryInput, 1, 2); // adjust if needed

        // Fetch country based on phone prefix
        $country = Countries::where('phone_code', $phonePrefix)->first();

        // Prepend the country code if it's missing
        if ($country) {
            $number = $country->phone_code . ltrim($rawNumber, '0');
        } else {
            // Fallback: assume the user input already includes country code
            $number = $rawNumber;
        }

        // Attempt login
        if (Auth::attempt([
            'number' => $number,
            'password' => $credentials['password'],
        ])) {
            $request->session()->regenerate();
            $request->session()->flash('showWelcomeModal', true);

            return redirect()->intended('/dashboard');
        }

        $request->session()->flash('error', 'The provided credentials do not match our records.');

        // Return back with error
        return redirect()->back()->withInput([
            'number' => $request->input('number'),
        ]);
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

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
