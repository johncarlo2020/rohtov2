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
            'age_group' => ['required', 'string', 'max:255'],
            'country' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (User::where('number', $value)->exists()) {
                        $fail('This phone number is already registered with another e-mail');
                    }
                }
            ],
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
            'age_group' => $request->age_group,
            'number' => $phoneNumber,
            'email' => $request->email,
            'otp' => $otp,
            'country'=> $country->name,
            'marketing' => $marketing,
            'type'=>'pre-reg',
            'last_login_at' => Carbon::now(),
            'password' => Hash::make('password'),
        ]);


        $user->assignRole('client');
        Auth::login($user);

        // Use the insert method to insert multiple records in one query
        event(new Registered($user));


        // GlobalHelper::sendOtpSms($phoneNumber, $otp);

    // ✅ Step 3: Redirect to OTP verification screen
    return redirect()->route('dashboard')->with('message', 'Registration successful! Welcome to the dashboard.');
    }
}
