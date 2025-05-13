<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Countries;
use App\Models\Regime;
use App\Models\RegimeUser;

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

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],

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
            'last_login_at' => Carbon::now(),
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('client');
        Auth::login($user);

        // Use the insert method to insert multiple records in one query
        event(new Registered($user));

           // ✅ Step 1: Generate and store OTP
            session(['otp' => $otp, 'otp_user_id' => $user->id]);

            // ✅ Step 2: Send OTP using your SMS API (Etracker)
            $content = "L'OCCITANE: OTP code: $otp. NEVER share this code with others.";
            $smsUrl = "http://www.etracker.cc/bulksms/mesapi.aspx?user=davino&pass=Wowsome%40820%23%23%23%23%21&type=0&to=" . urlencode($phoneNumber) . "&from=Loccitane&text=" . urlencode($content) . "&servid=MES01&title=EnDemande_MY_OceanOrPlastic2025";

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $smsUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                ],
            ]);

            $server_respond = curl_exec($ch);
            curl_close($ch);

    // ✅ Step 3: Redirect to OTP verification screen
    return redirect()->route('otp')->with('message', 'OTP has been sent to your phone.');
    }
}
