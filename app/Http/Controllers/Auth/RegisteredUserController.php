<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Countries;
use App\Models\Regime;
use App\Models\Utm;

use Carbon\Carbon;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Rules\InternationalPhoneNumber;
use App\Helpers\GlobalHelper;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        if ($request->has('utm_source')) {
            session(['utm.source' => $request->get('utm_source')]);
        }

        if ($request->has('utm_medium')) {
            session(['utm.medium' => $request->get('utm_medium')]);
        }
        return view('auth.register');
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
            'lname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'dob' => ['required', 'date', function ($attribute, $value, $fail) {
                if (Carbon::parse($value)->age < 18) {
                    $fail('You must be at least 18 years old to register.');
                }
            }],
        ]);

        $marketing = false;

        if($request->has('marketing')){
            $marketing = true;
        }

        // After validation, fetch country by phone number
        $phoneNumber = $request->input('country');

      // Extract the phone prefix
        $phonePrefix = '+' . substr($phoneNumber, 1, 2); // This assumes the prefix is always 2 characters after the '+'

        // Query the country based on the phone prefix
        $country = Countries::where('phone_code', $phonePrefix)->first();
        $otp = rand(100000, 999999);

        $user = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'dob' => $request->dob,
            'number' => $phoneNumber,
            'email' => $request->email,
            'otp' => $otp,
            'country'=> $country->name,
            'marketing' => $marketing,
            'type'=>'pre-reg',
            'last_login_at' => Carbon::now(),
            'password' => Hash::make('password'),
        ]);

        if ($request->filled('utm_source')) {
            $utm = new Utm();
            $utm->utm_source = $request->input('utm_source');
            $utm->save();

            $user->utm_source = $request->input('utm_source');
            $user->save();
        }

        if ($request->filled('utm_medium')) {
            $utm->utm_medium = $request->input('utm_medium');
            $utm->save();

            $user->utm_medium = $request->input('utm_medium');
            $user->save();
        }

        $user->assignRole('client');
        Auth::login($user);

        // Use the insert method to insert multiple records in one query
        event(new Registered($user));


        GlobalHelper::sendOtpSms($phoneNumber, $otp);

    // ✅ Step 3: Redirect to OTP verification screen
    return redirect()->route('otp')->with('message', 'OTP has been sent to your phone.');
    }
}
