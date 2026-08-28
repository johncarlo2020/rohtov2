<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Models\Developer;
use App\Models\EarlyBird;
use App\Models\Project;
use App\Models\Regime;
use App\Models\RegimeUser;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Rules\InternationalPhoneNumber;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
  /**
   * Display the registration view.
   */
  public function create(): View
  {
    $today = Carbon::today();
    return view("auth.register");
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): RedirectResponse
  {
      $request->validate([
          'fname' => ['required', 'string', 'max:255'],
          'email' => ['required', 'email', 'unique:users,email'],
          'privacy_policy' => ['required'],
      ]);

      $marketing = $request->has('marketing');

      // Get phone/country information
      // $phoneNumber = $request->input('code');
      // $dialCode = $request->input('dialCode');
      // $countryIso = $request->input('countryIso');

      // Find country
      // $country = Countries::where('phone_code', $dialCode)
      //     ->whereRaw('LOWER(code) = ?', [strtolower($countryIso)])
      //     ->first();

      // if (!$country) {
      //     return back()
      //         ->withInput()
      //         ->withErrors([
      //             'countryIso' => 'Country not found.',
      //         ]);
      // }

      // Generate OTP
      $otp = random_int(100000, 999999);

      // Create user FIRST
      $user = User::create([
          'fname' => $request->input('fname'),
          'email' => $request->input('email'),
          'otp' => $otp,
          'marketing' => $marketing,
          'last_login_at' => Carbon::now(),
          'password' => Hash::make('password'),
      ]);

      // Assign role AFTER user is created
      $user->assignRole('client');

      // Log the user in
      Auth::login($user);


      // Send OTP via Brevo/Mailtrap
      GlobalHelper::sendOtpEmail(
          $user->email,
          $otp,
          $user->name
      );

      // Optional SMS OTP
      // GlobalHelper::sendOtpSms($phoneNumber, $otp);

      return redirect()
          ->route('otp', ['user' => $user->id])
          ->with('success', 'A verification code has been sent to your email.');
  }
}
