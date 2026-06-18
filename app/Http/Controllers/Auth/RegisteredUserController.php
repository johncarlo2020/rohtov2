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

    //April 30 - May 11
      $default = [
        "Gelugor, The Light Waterfront",
        "Bukit Jambul",
        "Jawi, Seberang Perai Selatan",
        "Andaman Island",
        "Batu Kawan",
        "Bukit Mertajam",
        "Tasek Gelugor",
        "Daerah Seberang Perai Utara",
        "Daerah Seberang Perai Tengah",
        "Parit Buntar, Perak",
        "Nibong Tebal",
        "Kulim, Kedah",
        "Ara Kuda, Tasek Gelugor",
        "Sungai Petani",
        "Tanjong Tokong, Daerah Timur Laut",
        "Persiaran Bayan Indah",
        "Taman Cassia Cempaka, Bandar Cassia Batu Kawan",
        "Bertam Kepala Batas",
        "Lunas, Kulim, Kedah",
        // "Jalan Telaga Air",
        // "Jalan Residensi Harmoni",
        // "Jalan Tan Sai Gin, Butterworth",
        // "Butterworth",
    ];

  
     $allowedLocations = collect($default)
    ->shuffle()
    ->all();

    $locations = Project::select("address")
      ->distinct()
      ->whereIn("address", $allowedLocations)
      ->orderByRaw(
        "FIELD(address, " .
          implode(",", array_fill(0, count($allowedLocations), "?")) .
          ")",
        $allowedLocations
      )
      ->pluck("address");

    return view("auth.register", compact("locations"));
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): RedirectResponse
  {
     $today = Carbon::today();

      //April 30 - May 11
      $default = [
        "Gelugor, The Light Waterfront",
        "Bukit Jambul",
        "Jawi, Seberang Perai Selatan",
        "Andaman Island",
        "Batu Kawan",
        "Bukit Mertajam",
        "Tasek Gelugor",
        "Daerah Seberang Perai Utara",
        "Daerah Seberang Perai Tengah",
        "Parit Buntar, Perak",
        "Nibong Tebal",
        "Kulim, Kedah",
        "Ara Kuda, Tasek Gelugor",
        "Sungai Petani",
        "Tanjong Tokong, Daerah Timur Laut",
        "Persiaran Bayan Indah",
        "Taman Cassia Cempaka, Bandar Cassia Batu Kawan",
        "Bertam Kepala Batas",
        "Lunas, Kulim, Kedah",
        // "Jalan Telaga Air",
        // "Jalan Residensi Harmoni",
        // "Jalan Tan Sai Gin, Butterworth",
        // "Butterworth",
        
    ];

  
      $allowedLocations = $default;

    $validated = $request->validate([
      "fname" => ["required", "string", "max:255"],
      "email" => ["required", "email", "max:255", "unique:users,email"],
      "locations" => ["required", "array", "max:3"],
      "locations.*" => ["required", "string", Rule::in($allowedLocations)],
      "property_budget" => ["required", "string"],
      "marketing" => ["required", "string"],
    ]);

    //check if user is an early bird
    $earlyBird = EarlyBird::where(
      "email",
      strtolower($validated["email"])
    )->first();

    $user = User::create([
      "fname" => $validated["fname"],
      "email" => $validated["email"],
      "property_budget" => $validated["property_budget"],
      "marketing" => $validated["marketing"],
      "password" => Hash::make("password"),
      "is_early_bird" => !!$earlyBird,
    ]);

    if ($earlyBird) {
      $earlyBird->update(["claimed" => true]);
    }

    $locations = $validated["locations"];

    $assignedDevelopers = collect();

    foreach ($locations as $location) {
      // 🎯 Developers for this location
      $developerIds = Developer::whereHas("projects", function ($q) use (
        $location
      ) {
        $q->where("address", $location);
      })
        ->where("id", "!=", 5) // 👈 exclude
        ->pluck("id")
        ->toArray();

      if (empty($developerIds)) {
        continue;
      }

      // 🧠 Already used developers
      $usedDeveloperIds = DB::table("developer_user")
        ->whereIn("developer_id", $developerIds)
        ->pluck("developer_id")
        ->toArray();

      // 🔍 Available developers
      $available = array_diff($developerIds, $usedDeveloperIds);

      // 🎲 Pick developer
      $selected = !empty($available)
        ? collect($available)->random()
        : collect($developerIds)->random();

      $assignedDevelopers->push($selected);
    }

    // ✅ Ensure unique + max 3
    $finalDevelopers = $assignedDevelopers
      ->unique()
      ->take(3)
      ->values()
      ->toArray();

    $finalDevelopers = collect($finalDevelopers)
      ->reject(fn($id) => $id == 5)
      ->values()
      ->toArray();


    // ⚠️ Fill if less than 3
    if (count($finalDevelopers) < 3) {
      $extra = Developer::whereHas("projects", function ($q) use ($allowedLocations) {
            $q->whereIn("address", $allowedLocations);
        })
        ->whereNotIn("id", array_merge($finalDevelopers, [5]))
        ->inRandomOrder()
        ->take(3 - count($finalDevelopers))
        ->pluck("id")
        ->toArray();

      $finalDevelopers = array_merge($finalDevelopers, $extra);
    }

    // 💾 Save
    $user->developers()->sync($finalDevelopers);

    $user->assignRole("client");
    Auth::login($user);

    return redirect()->route("dashboard");

    // ✅ Auto login (optional but standard)
    // auth()->login($user);

    // // ✅ Redirect to dashboard
    // return redirect()->route('dashboard')
    //     ->with('success', 'Registration successful!');

    // $marketing = false;

    // if($request->has('marketing')){
    //     $marketing = true;
    // }

    // After validation, fetch country by phone number
    // $phoneNumber = $request->input('code');
    // $dialCode = $request->input('dialCode');
    // $countryIso = $request->input('countryIso');

    // Extract the phone prefix
    // $phonePrefix = '+' . substr($phoneNumber, 1, 2); // This assumes the prefix is always 2 characters after the '+'

    // Query the country based on the phone prefix
    // $country = Countries::where('phone_code', $dialCode)
    //     ->whereRaw('LOWER(code) = ?', [strtolower($countryIso)])
    //     ->first();
    // $otp = rand(100000, 999999);

    // $user = User::create([
    //     'number' => $phoneNumber,
    //     'country'=> $country->name,
    //     'marketing' => $marketing,
    //     'last_login_at' => Carbon::now(),
    //     'password' => Hash::make('password'),
    // ]);

    // $request->session()->flash('showWelcomeModal', true);
    // Use the insert method to insert multiple records in one query

    // GlobalHelper::sendOtpSms($phoneNumber, $otp);
  }
}
