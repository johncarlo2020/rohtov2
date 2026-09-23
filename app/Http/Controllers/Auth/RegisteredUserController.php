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
          'email' => ['required', 'email', 'max:255', 'unique:users,email'],
          'number' => ['nullable', 'string', 'max:30'],
          'consent_channels' => ['nullable', 'array'],
          'consent_channels.*' => ['string', 'in:email,sms,whatsapp,phone,none'],
          'newsletter_consent' => ['nullable', 'in:0,1,true,false'],
          'communication_consent' => ['nullable', 'in:0,1,true,false'],
          'preferred_contact' => ['nullable', 'string', 'max:50'],
          'privacy_policy' => ['nullable'],
      ]);

      // Normalize phone number
      $number = $request->input('number');
      if ($number) {
          $number = trim($number);
          $clean = preg_replace('/[^\d+]/', '', $number);
          if (!str_starts_with($clean, '+')) {
              if (str_starts_with($clean, '60')) {
                  $clean = '+' . $clean;
              } elseif (str_starts_with($clean, '0')) {
                  $clean = '+60' . substr($clean, 1);
              } else {
                  $clean = '+60' . $clean;
              }
          }
          $number = $clean;
      }

      // Process consent channels
      $consentChannels = $request->input('consent_channels', []);
      if (!is_array($consentChannels)) {
          $consentChannels = [];
      }

      // Determine preferred contact for backwards compatibility
      if ($request->filled('preferred_contact')) {
          $preferredContact = $request->input('preferred_contact');
      } elseif (in_array('none', $consentChannels)) {
          $preferredContact = 'None';
      } elseif (!empty($consentChannels)) {
          $preferredContact = implode(', ', array_map(function($c) {
              return match($c) {
                  'email' => 'Email',
                  'sms' => 'SMS',
                  'whatsapp' => 'WhatsApp',
                  'phone' => 'Phone',
                  default => ucfirst($c)
              };
          }, $consentChannels));
      } else {
          $preferredContact = 'Email';
      }

      // Process boolean consents
      $communicationConsent = $request->has('communication_consent')
          ? filter_var($request->input('communication_consent'), FILTER_VALIDATE_BOOLEAN)
          : false;

      $newsletterConsent = $request->has('newsletter_consent')
          ? filter_var($request->input('newsletter_consent'), FILTER_VALIDATE_BOOLEAN)
          : false;

      $marketing = $newsletterConsent || $communicationConsent;

      $user = User::create([
          'title' => $request->input('title'),
          'lname' => $request->input('lname'),
          'fname' => $request->input('fname'),
          'email' => $request->input('email'),
          'number' => $number,
          'consent_channels' => $consentChannels,
          'newsletter_consent' => $newsletterConsent,
          'preferred_contact' => $preferredContact,
          'communication_consent' => $communicationConsent,
          'marketing' => $marketing,
          'otp_verified' => 1,
          'created_at' => Carbon::now(),
          'last_login_at' => Carbon::now(),
          'password' => Hash::make('password'),
      ]);

      $user->assignRole('client');

      Auth::login($user);

      return redirect()->intended(route('dashboard'));
  }
}
