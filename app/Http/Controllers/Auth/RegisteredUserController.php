<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Models\EarlyBird;
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

use Spatie\Permission\Models\Role;

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
          'title' => ['required', 'string', 'max:20'],
          'lname' => ['required', 'string', 'max:255'],
          'fname' => ['required', 'string', 'max:255'],
          'email' => ['required', 'email', 'unique:users,email'],
          'preferred_contact' => ['required', 'string', 'max:50'],
          'privacy_policy' => ['required'],
      ]);

      $communicationConsent = $request->has('communication_consent');
      $otp = random_int(100000, 999999);

      $user = User::create([
          'title' => $request->input('title'),
          'lname' => $request->input('lname'),
          'fname' => $request->input('fname'),
          'email' => $request->input('email'),
          'preferred_contact' => $request->input('preferred_contact'),
          'communication_consent' => $communicationConsent,
          'marketing' => $communicationConsent,
          'otp' => $otp,
          'created_at' => Carbon::now(),
          'last_login_at' => Carbon::now(),
          'password' => Hash::make('password'),
      ]);

      $user->assignRole('client');

      Auth::login($user);

      GlobalHelper::sendOtpEmail(
          $user->email,
          $otp,
          trim(($user->fname ?? '') . ' ' . ($user->lname ?? '')),
          $otpType = 'Registration'
      );

      // Optional SMS OTP
      // GlobalHelper::sendOtpSms($phoneNumber, $otp);

      return redirect()
          ->route('otp', ['user' => $user->id])
          ->with('success', 'A verification code has been sent to your email.');
  }
}
