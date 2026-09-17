<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Utm;

use Carbon\Carbon;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

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
        $number = preg_replace('/[\s()\-]+/', '', (string) $request->input('number'));
        if (str_starts_with($number, '0')) {
            $number = '+60' . substr($number, 1);
        } elseif (str_starts_with($number, '60')) {
            $number = '+' . $number;
        }
        $request->merge(['number' => $number]);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'number' => ['required', 'string', 'regex:/^\+601[0-9]{8,9}$/', 'unique:users,number'],
            'terms' => ['accepted'],
            'age_confirmed' => ['accepted'],
            'marketing' => ['sometimes', 'boolean'],
        ], [
            'number.regex' => 'Please enter a valid Malaysian mobile number.',
            'number.unique' => 'This mobile number is already registered. Please log in instead.',
            'terms.accepted' => 'Please agree to the Terms and Conditions and Privacy Policy.',
            'age_confirmed.accepted' => 'You must confirm that you are 21 or above to register.',
        ]);

        // Keep the existing name columns compatible with the rest of the application.
        $name = preg_split('/\s+/', trim($validated['full_name']), 2);
        $marketing = $request->boolean('marketing');

        $user = DB::transaction(function () use ($request, $validated, $name, $marketing) {
            $user = User::create([
                'fname' => $name[0],
                'lname' => $name[1] ?? '',
                'dob' => '', // Date of birth is no longer collected.
                'number' => $validated['number'],
                'email' => $request->email,
                'country' => 'Malaysia',
                'terms' => $request->boolean('terms'),
                'age_confirmed' => $request->boolean('age_confirmed'),
                'marketing' => $marketing,
                'email_consent' => $marketing,
                'sms_consent' => $marketing,
                'last_login_at' => Carbon::now(),
                'password' => Hash::make('password'),
            ]);

            $utm = new Utm();

            if ($request->filled('utm_source')) {
                $utm->utm_source = $request->input('utm_source');
                $utm->save();

                $user->utm_source = $request->input('utm_source');
                $user->type = 'pre-reg';
                $user->save();
            }

            if ($request->filled('utm_medium')) {
                $utm->utm_medium = $request->input('utm_medium');
                $utm->save();

                $user->utm_medium = $request->input('utm_medium');
                $user->save();
            }

            $user->assignRole(Role::findOrCreate('client', 'web'));

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        // Use the insert method to insert multiple records in one query
        event(new Registered($user));


        return redirect()->route('map');
    }
}
