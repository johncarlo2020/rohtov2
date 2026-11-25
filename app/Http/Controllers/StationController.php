<?php

namespace App\Http\Controllers;

use App\Events\babyEvent;
use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use App\Imports\EarlyBirdImport;
use App\Models\Brand;
use App\Models\Developer;
use App\Models\EarlyBird;
use App\Models\Gifts;
use App\Models\GiftStockLog;
use App\Models\Perfume;
use App\Models\Question;
use App\Models\Station;
use App\Models\StationUser;
use App\Models\User;
use App\Models\UserGift;
use App\Models\UserPerfume;
use App\Models\Vote;
use App\Models\Voucher;
use App\Models\VoucherClaim;
use App\Providers\RouteServiceProvider;
use App\Services\UserDatatableService;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StationController extends Controller
{
  public function index(Station $station)
  {
    $user = StationUser::where("user_id", auth()->id())
      ->where("station_id", $station->id)
      ->exists();

    return view("station", compact("station", "user"));
  }

  public function developer(Request $request, $developerId)
  {
    $user = StationUser::where("user_id", auth()->id())
      ->where("station_id", $request->id)
      ->exists();

    // ❗ REMOVE or FIX this if not needed
    // $station = Station::findOrFail($request->developer);

    $developer = auth()
      ->user()
      ->developers()
      ->where("developers.id", $developerId)
      ->firstOrFail();

    return view("developer", compact("user", "developer"));
  }

  public function quiz(Request $request)
  {
    $user = StationUser::where("user_id", auth()->id())
      ->where("station_id", $request->id)
      ->exists();

    // ✅ developer from route (clean)
    $developer = auth()
      ->user()
      ->developers->firstWhere("id", request()->route("developer"));
    $question = Question::where("questions.developer_id", $developer->id)

      // ❌ exclude already answered by THIS user
      ->whereNotIn("questions.id", function ($q) use ($user) {
        $q->select("user_question.question_id")
          ->from("user_question")
          ->where("user_question.user_id", auth()->id());
      })

      // 🧠 join for usage count
      ->leftJoin(
        "user_question",
        "questions.id",
        "=",
        "user_question.question_id"
      )

      // 📊 select + count
      ->select("questions.*", DB::raw("COUNT(user_question.id) as usage_count"))

      // ⚠️ group properly (important for MySQL strict mode)
      ->groupBy(
        "questions.id",
        "questions.developer_id",
        "questions.question",
        "questions.created_at",
        "questions.updated_at"
      )

      // 🎯 least used first
      ->orderBy("usage_count", "asc")

      ->first();

    // ⚠️ fallback if all answered
    if (!$question) {
      $question = Question::where("developer_id", $developer->id)
        ->inRandomOrder()
        ->first();
    }

    // 🔀 load + shuffle answers
    $question->load("answers");
    $question->answers = $question->answers->shuffle()->values();

    return view("quiz", compact("user", "developer", "question"));
  }

  public function welcome()
  {
    $userId = Auth::id();
    $user = User::with(["stationUser"])->findOrFail($userId);

    // Show instructions page first before the dashboard for stations
    if (request()->has('start')) {
      session(['instructions_seen' => true]);
    } elseif (!session('instructions_seen') && $user->stationUser->isEmpty()) {
      return redirect()->route('instructions');
    }

    $stations = Station::orderBy('id')->get();

    // Mark completion status for each station
    $completedStationIds = $user->stationUser->pluck("station_id")->toArray();
    foreach ($stations as $station) {
      $station->status = in_array($station->id, $completedStationIds);
    }

    $completedCount = count($completedStationIds);
    $totalStations = $stations->count();
    $allCompleted = $completedCount >= 6;

    // Check if user has redeemed gift
    $isRedeemed = \App\Models\UserGift::where("user_id", $userId)
      ->where("is_redeemed", true)
      ->exists();

    // Check today's redemption limit (300)
    $today = \Carbon\Carbon::today();
    $todayRedeemedCount = \App\Models\UserGift::whereDate('redeemed_at', $today)
      ->where('is_redeemed', true)
      ->count();
    $dailyLimit = 300;
    $dailyQuotaRemaining = max(0, $dailyLimit - $todayRedeemedCount);
    $isDailyQuotaFull = $dailyQuotaRemaining <= 0;

    return view("dashboard", compact(
      "user",
      "stations",
      "completedStationIds",
      "completedCount",
      "totalStations",
      "allCompleted",
      "isRedeemed",
      "todayRedeemedCount",
      "dailyLimit",
      "dailyQuotaRemaining",
      "isDailyQuotaFull"
    ));
  }

  public function scanner()
  {
    // dd('asdasd');
    return view("scanner");
  }

  public function scanDeveloper(Request $request)
  {
    $qrCodeMessage = trim($request->qrCodeMessage);
    $developer_id = (int) basename($qrCodeMessage);

    if ($developer_id != $request->developer) {
      return response()->json(
        ["message" => "Invalid Qr", "status" => "error"],
        400
      );
    }

    return response()->json([
      "success" => true,
      "type" => "developer",
      "redirect_url" => route("developer.quiz", $developer_id),
    ]);
  }

  public function scan(Request $request)
  {
    $qrCodeMessage = trim((string) $request->qrCodeMessage);
    $station_id = (int) $request->station;

    // Validate station exists
    $station = Station::find($station_id);
    if (!$station) {
      return response()->json([
        "status" => "error",
        "message" => "Station not found.",
      ], 404);
    }

    // Token validation:
    // QR can be: full URL matching this station (e.g. /station/1), "RWG_STATION_1", "station/1", "1", or token matching the station.
    $isValidToken = str_contains($qrCodeMessage, "station/{$station_id}")
      || str_contains($qrCodeMessage, "station={$station_id}")
      || str_contains($qrCodeMessage, "STATION_{$station_id}")
      || str_contains($qrCodeMessage, "ST{$station_id}")
      || $qrCodeMessage === (string) $station_id;

    if (!$isValidToken) {
      return response()->json([
        "status" => "error",
        "message" => "Invalid QR code for this station.",
      ], 400);
    }

    $userId = auth()->id();

    // Check if already completed this station
    $alreadyCompleted = StationUser::where("user_id", $userId)
      ->where("station_id", $station_id)
      ->exists();

    if ($alreadyCompleted) {
      return response()->json([
        "status" => "error",
        "message" => "You have already collected the stamp for this station.",
      ], 400);
    }

    try {
      DB::beginTransaction();

      $lastStation = StationUser::where("user_id", $userId)
        ->orderBy("id", "desc")
        ->first();

      $lastTime = $lastStation ? $lastStation->created_at : (Auth::user()->last_login_at ?? Carbon::now());
      $secondsSpent = max(1, Carbon::now()->diffInSeconds($lastTime));

      $stationUser = StationUser::firstOrCreate(
        ['user_id' => $userId, 'station_id' => $station_id],
        ['time_spent' => (string) $secondsSpent]
      );

      DB::commit();

      $user = User::with('stationUser')->find($userId);
      $allDone = $user->hasCompletedAllStations();

      return response()->json([
        "status" => "success",
        "type" => "station",
        "message" => "Stamp collected successfully for {$station->name}!",
        "station_id" => $station_id,
        "all_completed" => $allDone,
        "redirect_url" => route('dashboard'),
      ], 200);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        "status" => "error",
        "message" => "Could not record check-in. Please try again.",
        "debug" => $e->getMessage(),
      ], 500);
    }
  }

  public function userDelete($id)
  {
    $user = User::findOrFail($id);

    // Check if user is protected admin
    if ($user->isProtectedAdmin()) {
      return redirect()
        ->back()
        ->with("error", "This admin user is protected and cannot be deleted.");
    }

    // Delete related station user entries
    $user->stationUser()->delete(); // ✅ Correct for hasMany

    // Delete the user
    $user->delete();

    return redirect()->back()->with("success", "User deleted successfully.");
  }

  public function admin()
  {
    $admin = User::find(auth()->id());
    $permission = $admin->getPermissionNames()->first();
    $today = Carbon::today();
    $startDate = Carbon::create(2025, 11, 17);

    $data["users"] = User::with("stationUser")
      ->orderBy("id", "desc")
      ->whereDoesntHave("roles", function ($q) {
        $q->where("name", "admin");
      })
      ->where(
        DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
        ">=",
        $startDate->toDateString()
      )
      ->get();

    $data["usersCount"] = User::whereDate(
      "created_at",
      ">=",
      $startDate->toDateString()
    )
      ->whereDoesntHave("roles", function ($q) {
        $q->where("name", "admin");
      })
      ->where(
        DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
        ">=",
        $startDate->toDateString()
      )
      ->count();
    $data["userToday"] = User::whereDate("created_at", $today)
      ->whereDoesntHave("roles", function ($q) {
        $q->where("name", "admin");
      })
      ->where(
        DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
        ">=",
        $startDate->toDateString()
      )
      ->count();
    $data["country"] = User::selectRaw("country , COUNT(*) as count")
      ->whereDoesntHave("roles", function ($q) {
        $q->where("name", "admin");
      })
      ->where(
        DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
        ">=",
        $startDate->toDateString()
      )
      ->groupBy("country")
      ->where("country", "!=", "admin")
      ->get();

    //   dd($data['where']);

    $usersWithSixStationUsers = User::with("stationUser")
      ->whereDoesntHave("roles", function ($q) {
        $q->where("name", "admin");
      })
      ->whereDate("created_at", ">=", $startDate->toDateString())
      ->has("stationUser", ">=", 3)
      ->count();
    // dd($usersWithSixStationUsers);
    $data["completedUsers"] = $usersWithSixStationUsers;
    // dd($usersWithSixStationUsers);

    if ($data["usersCount"] > 0) {
      $data["percentage"] = number_format(
        ($usersWithSixStationUsers / $data["usersCount"]) * 100,
        2
      );
    } else {
      $data["percentage"] = 0; // Avoid division by zero
    }
    $userCounts = User::selectRaw("DATE(created_at) as date, COUNT(*) as count")
      ->whereDoesntHave("roles", function ($q) {
        $q->where("name", "admin");
      })
      ->groupBy("date")
      ->orderBy("date")
      ->get()
      ->toArray();

    $userCountsArray = [];
    $data["dates"] = User::select(
      DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date')
    )
      ->whereDoesntHave("roles", function ($q) {
        $q->where("name", "admin");
      })
      ->where(
        DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
        ">=",
        $startDate->toDateString()
      )
      ->groupBy("date")
      ->get();

    $data["registrationsPerHour"] = User::select(
      DB::raw("DATE(created_at) as date"),
      DB::raw("HOUR(created_at) as hour_24"), // numeric sorting key
      DB::raw('LOWER(DATE_FORMAT(created_at, "%l%p")) as hour'),
      DB::raw("COUNT(*) as registrations")
    )
      ->whereDoesntHave("roles", function ($q) {
        $q->where("name", "admin");
      })
      ->whereNotNull("created_at")
      ->whereDate("created_at", ">=", $startDate->toDateString())
      ->groupBy("date", "hour_24", "hour")
      ->orderBy("hour_24")
      ->havingRaw('hour IS NOT NULL AND hour <> ""')
      ->get()
      ->groupBy("hour");

    foreach ($userCounts as $userCount) {
      if ($userCount["date"] >= $startDate->toDateString()) {
        $userCountsArray[$userCount["date"]] = $userCount["count"];
      }
    }
    $data["usersDaily"] = $userCountsArray;
    // $completed = StationUser::w

    $averageTimespentByStation = StationUser::select(
      "station_id",
      \DB::raw("AVG(time_spent) as average_timespent")
    )
      ->groupBy("station_id")
      ->get()
      ->keyBy("station_id");

    $stations = Station::pluck('name', 'id');

    $count = 0;

    foreach ($data["users"] as $user) {
      $userStations = $user->stationUser->pluck("station_id")->toArray() ?? [];
      $numStations = count($userStations);

      $user->stations = $stations->map(function ($name, $id) use (
        $userStations,
        $averageTimespentByStation
      ) {
        return [
          "name" => $name,
          "value" => in_array($id, $userStations),
          "id" => $id,
        ];
      });

      // Add completed_count to the user
      $user->completed_count = $numStations;
    }

    $stationCounts = collect($data['users'])
    ->flatMap(function ($user) {
        return $user->stationUser->pluck('station_id');
    })
    ->countBy();

    $data["stations"] = $stations->map(function ($name, $id) use (
      $averageTimespentByStation,
      $stationCounts
    ) {
      return [
        "name" => $name,
        "average_timespent" => number_format(
          ($averageTimespentByStation->get($id)["average_timespent"] ?? 0) / 60,
          2
        ),
        "id" => $id,
         "total_users" => $stationCounts->get($id, 0),
      ];
    });

    $developerCounts = collect($data['users'])
        ->flatMap(function ($user) {
            return $user->developers->pluck('id');
        })
        ->countBy();

    $developers = \App\Models\Developer::pluck('name', 'id');

    $data["developers"] = $developers->map(function ($name, $id) use ($developerCounts) {
        return [
            "id" => $id,
            "name" => $name,
            "total_users" => $developerCounts->get($id, 0),
        ];
    });


    $averagePlaytimeByUser = StationUser::select(
      "user_id",
      DB::raw("SUM(time_spent) / 60 as total_playtime")
    )
      ->groupBy("user_id")
      ->get();

    $totalAveragePlaytime = $averagePlaytimeByUser->avg("total_playtime");

    // get all users race column for pie chart
    $data["race"] = User::where("race", "!=", "admin")
      ->whereDate("created_at", ">=", $startDate->toDateString())
      ->selectRaw("race, COUNT(*) as count")
      ->groupBy("race")
      ->get()
      ->map(function ($item) {
        return [
          "race" => $item->race,
          "count" => $item->count,
        ];
      })
      ->values()
      ->toArray();

    $voucherSessions = Voucher::where('name', 'CHAGEE')
        ->orderBy('session')
        ->get()
        ->map(function ($voucher) {
            $claimedCount = VoucherClaim::where('voucher_id', $voucher->id)->count();

            if (now()->lt($voucher->starts_at)) {
                $status = 'Upcoming';
            } elseif ($voucher->ends_at && now()->gt($voucher->ends_at)) {
                $status = 'Ended';
            } else {
                $status = 'Active';
            }

            return [
                'session' => $voucher->session,
                'status' => $status,
                'claimed' => "{$claimedCount}/{$voucher->quota}",
                'starts_at' => $voucher->starts_at,
                'ends_at' => $voucher->ends_at,
            ];
        });

    return view("dashboardadmin", compact("data", "permission","voucherSessions"));
  }

  public function import(Request $request)
  {
    $request->validate([
      "csv_file" => "required|mimes:csv,xlsx,txt",
    ]);

    $import = new EarlyBirdImport();

    Excel::import($import, $request->file("csv_file"));

    return back()->with("success", [
      "imported" => $import->imported,
      "skipped" => $import->skipped,
      "errors" => $import->errors,
    ]);
  }

  public function users(Request $request, UserDatatableService $userTableService)
  {
    if ($request->ajax() || $request->wantsJson() || $request->has('draw')) {
      try {
        return response()->json($userTableService->getDataTable($request));
      } catch (\Throwable $e) {
        \Log::error('Users datatable error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        return response()->json([
          'draw' => (int) $request->input('draw', 1),
          'recordsTotal' => 0,
          'recordsFiltered' => 0,
          'data' => [],
          'error' => $e->getMessage(),
        ]);
      }
    }

    $permission = auth()->user()?->getPermissionNames()?->first();
    $data = $userTableService->getViewData();

    return view("users", compact("data", "permission"));
  }

  public function exportUsers(Request $request, UserDatatableService $userTableService)
  {
    return $userTableService->export($request);
  }

  public function gifts(Request $request)
  {
    $gifts = Gifts::get();
    return view("gifts",compact("gifts"));
  }

  public function earlybird()
  {
    $earlyBirds = EarlyBird::all();

    return view("earlybird", compact("earlyBirds"));
  }

  public function userData(User $user)
  {
    $averagePlaytimeByUser = StationUser::where("user_id", $user->id)->avg(
      "time_spent"
    );
    $permission = auth()->user()?->getPermissionNames()?->first();

    $stations = Station::pluck("name", "id");

    $averageTimespentByStation = StationUser::where("user_id", $user->id)
      ->orderBy("id", "asc")
      ->get();
    $total = StationUser::where("user_id", $user->id)
      ->orderBy("id", "asc")
      ->sum("time_spent");
    $totalMinutes = $total / 60;
    $totalMinutes = number_format($totalMinutes, 2);

    $userStations = $user->stationUser->pluck("station_id")->toArray();
    $numStations = count($userStations);

    $user->stations = $stations->map(function ($name, $id) use (
      $userStations,
      $user
    ) {
      $spent = StationUser::where("user_id", $user->id)
        ->where("station_id", $id)
        ->first();
      if (!$spent) {
        $minute = 0;
      } else {
        $seconds = $spent->time_spent;
        $minute = $seconds / 60;
        $minute = number_format($minute, 2);
      }
      return [
        "name" => $name,
        "value" => in_array($id, $userStations),
        "time_spent" => $minute,
        "id" => $id,
      ];
    });

    return view("userData", compact("user", "totalMinutes", "permission"));
  }

  public function editUser(Request $request)
  {
    $user = User::find($request->id);

    if ($user) {
      $user->email = $request->email;
      $user->save();

      return response()->json([
        "success" => true,
        "message" => "User email updated successfully",
      ]);
    }

    return response()->json(
      ["success" => false, "message" => "User not found"],
      404
    );
  }

  public function check(Request $request)
  {
    $check = StationUser::where("user_id", $request->user_id)
      ->where("station_id", $request->station_id)
      ->first();

    if (!$check) {
      $stationUser = new StationUser();
      $stationUser->user_id = $request->user_id;
      $stationUser->station_id = $request->station_id;
      $stationUser->time_spent = 60;
      $stationUser->save();
    } else {
      $check->delete();
    }

    return $check;
  }

  public function verify(Request $request)
  {
    $otp = implode("", $request->input("otp"));
    // dd(auth()->user());
    if ($otp == auth()->user()->otp) {
      // Success: Clear session OTP
      Session::forget(["otp", "otp_sent_at"]);
      $user = auth()->user();
      $user->otp_verified = 1;
      $user->email_verified_at = Carbon::now();
      $user->save();

      // $data = GlobalHelper::createSampleProfile();
      //  dd($data);

      // return redirect(RouteServiceProvider::HOME);
      return redirect()->route("register.welcome");
    }

    return back()->withErrors(["otp" => "Invalid OTP"]);
  }

  public function resend(Request $request)
  {
    $user = auth()->user();

    $otp = rand(100000, 999999);

    GlobalHelper::sendOtpSms($user->number, $otp);

    $user->otp = $otp;
    $user->save();

    return response()->json([
      "success" => true,
      "message" => "OTP resent successfully.",
    ]);
  }

  public function getValue()
  {
    // Count all image files in storage/app/public/babies
    $imageCount = collect(\Storage::files("public/babies"))
      ->filter(function ($file) {
        return preg_match('/\\.(jpg|jpeg|png|gif|webp)$/i', $file);
      })
      ->count();

    $imageCount = $imageCount + 3000;
    return response()->json(["count" => $imageCount]);
  }

  public function verifyAdmin(Request $request)
  {
    $otp = $request->input("otp");
    $userId = $request->input("user_id"); // Get user ID from the request

    $user = User::find($userId); // Find the user by ID

    if (!$user) {
      return back()->withErrors(["user" => "User not found"]);
    }

    if ($otp == $user->otp) {
      // Success: Clear session OTP
      Session::forget(["otp", "otp_sent_at"]);
      $user->otp_verified = 1;
      $user->email_verified_at = Carbon::now();
      $user->save();

      //  $data = GlobalHelper::createSampleProfile();
      return back()->with("success", "OTP verified successfully!");
    }

    return back()->withErrors(["otp" => "Invalid OTP"]);
  }

  public function giftSelection(Request $request)
  {
    $userId = auth()->id();

    $isRedeemed = \App\Models\UserGift::where("user_id", $userId)
      ->where("is_redeemed", true)
      ->exists();

    return view("giftSelection", compact("isRedeemed"));
  }

  public function stamping(Station $station)
  {
    $user = StationUser::where("user_id", auth()->id())
      ->where("station_id", $station->id)
      ->exists();

    $redirectUrl = auth()->user()?->hasCompletedAllStations()
      ? route('congrats')
      : route('dashboard');

    return view("stamping", compact("station", "user", "redirectUrl"));
  }

  public function discover()
  {
    return view("discover");
  }

  public function userGifts()
  {
    try {
      $userGifts = \App\Models\UserGift::with(["user", "gift"])
        ->orderBy("created_at", "desc")
        ->paginate(20);

      return view("admin.user-gifts", compact("userGifts"));
    } catch (\Exception $e) {
      return redirect()
        ->route("admin")
        ->with("error", "Error loading user gifts: " . $e->getMessage());
    }
  }

  public function adminGifts()
  {
    // Debug: Test if method is being called
    logger("adminGifts method called");

    try {
      $gifts = \App\Models\Gifts::withCount("userGifts")
        ->orderBy("created_at", "desc")
        ->get();

      $totalGifts = $gifts->count();
      $enabledGifts = $gifts->where("enabled", true)->count();
      $disabledGifts = $gifts->where("enabled", false)->count();
      $totalSelectedGifts = \App\Models\UserGift::count();

      $stats = [
        "total_gifts" => $totalGifts,
        "enabled_gifts" => $enabledGifts,
        "disabled_gifts" => $disabledGifts,
        "total_selected" => $totalSelectedGifts,
      ];

      return view("admin.gifts", compact("gifts", "stats"));
    } catch (\Exception $e) {
      logger("Error in adminGifts: " . $e->getMessage());
      return response("Error: " . $e->getMessage(), 500);
    }
  }

  public function toggleGift(\App\Models\Gifts $gift)
  {
    $gift->enabled = !$gift->enabled;
    $gift->save();

    $status = $gift->enabled ? "enabled" : "disabled";
    return redirect()
      ->back()
      ->with(
        "success",
        "Gift '{$gift->name}' has been {$status} successfully."
      );
  }



  public function stamp(Request $request)
  {
    // Get the last character of the QR code message
    $station_id = $request->station;

    // if ($station_id == 5) {
    //     auth()->user()->update([
    //         'chagee_redeemed' => 1,
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'CHAGEE voucher redeemed.',
    //     ]);
    // }
    if ($station_id == 5) {

    $activeVoucher = Voucher::where('name', 'CHAGEE')
        ->where('starts_at', '<=', now())
        ->where(function ($q) {
            $q->whereNull('ends_at')
                ->orWhere('ends_at', '>=', now());
        })
        ->orderBy('starts_at', 'desc')
        ->first();

    if (!$activeVoucher) {
        return response()->json([
            'success' => false,
            'message' => 'Voucher redemption is not available yet.',
        ]);
    }

    $alreadyClaimed = VoucherClaim::where(
        'user_id',
        auth()->id()
    )->exists();

    if ($alreadyClaimed) {
        return response()->json([
            'success' => false,
            'message' => 'Voucher already redeemed.',
        ]);
    }

    $claimedCount = VoucherClaim::where(
        'voucher_id',
        $activeVoucher->id
    )->count();

    if ($claimedCount >= $activeVoucher->quota) {
        return response()->json([
            'success' => false,
            'message' => 'Voucher quota exhausted.',
        ]);
    }

    VoucherClaim::create([
        'voucher_id' => $activeVoucher->id,
        'user_id' => auth()->id(),
        'claimed_at' => now(),
    ]);

    return response()->json([
        'success' => true,
        'message' => 'CHAGEE voucher redeemed.',
    ]);
}
    // Assume that `$station_id` is validated before this point

    try {
      DB::beginTransaction();

      $lastStation = StationUser::where("user_id", auth()->id())
        ->orderBy("id", "desc")
        ->first();

      if (empty($lastStation)) {
        $lastLoginTime = Auth::user()->last_login_at;
        $currentDateTime = Carbon::now();
        $timeSpent = $currentDateTime->diff($lastLoginTime);
        $minutesSpent = $timeSpent->i; // Minutes spent
        $secondsDifference = $timeSpent->s; // Seconds

        // Convert minutes to seconds
        $secondsSpent = $minutesSpent * 60 + $secondsDifference;
      } else {
        $lastLoginTime = $lastStation->created_at;
        $currentDateTime = Carbon::now();
        $timeSpent = $currentDateTime->diff($lastLoginTime);
        $minutesSpent = $timeSpent->i; // Minutes spent
        $secondsDifference = $timeSpent->s; // Seconds
        // Convert minutes to seconds
        $secondsSpent = $minutesSpent * 60 + $secondsDifference;
      }

      $stationUser = new StationUser();
      $stationUser->user_id = auth()->id();
      $stationUser->station_id = $station_id;
      $stationUser->time_spent = $secondsSpent;
      $stationUser->save();

      $user = auth()->user();

      DB::commit();

      return response()->json([
        "redirect_url" => auth()->user()->hasCompletedAllStations()
            ? route("congrats")
            : route("dashboard"),
      ]);

    } catch (\Exception $e) {
      DB::rollback();

      // Handle the error, log it, or return an appropriate response
      return response()->json(["error" => $e], 500);
    }
  }

  public function redeemGift(Request $request)
  {
    $userId = auth()->id();
    $user = User::with('stationUser')->findOrFail($userId);

    // Rule 1: All 6 stations must be completed
    if (!$user->hasCompletedAllStations()) {
      return response()->json([
        'success' => false,
        'message' => 'You must complete all 6 stations before unlocking gift redemption.',
      ], 403);
    }

    // Rule 2: Cannot redeem more than once
    $alreadyRedeemed = UserGift::where('user_id', $userId)
      ->where('is_redeemed', true)
      ->exists();

    if ($alreadyRedeemed) {
      return response()->json([
        'success' => false,
        'message' => 'You have already redeemed your gift.',
      ], 400);
    }

    // Rule 3: Concurrency-safe daily allocation of 300 gifts
    return DB::transaction(function () use ($userId) {
      $today = Carbon::today();

      $gift = Gifts::where('id', 1)->lockForUpdate()->first();

      $todayRedeemedCount = UserGift::whereDate('redeemed_at', $today)
        ->where('is_redeemed', true)
        ->lockForUpdate()
        ->count();

      $dailyLimit = 300;
      if ($todayRedeemedCount >= $dailyLimit || ($gift && $gift->stock_level <= 0)) {
        return response()->json([
          'success' => false,
          'quota_full' => true,
          'message' => "Today's daily allocation of 300 gifts has been fully redeemed. Please try again tomorrow!",
        ], 200);
      }

      if ($gift) {
        $gift->decrement('stock_level');
      }

      UserGift::create([
        'user_id' => $userId,
        'gift_id' => $gift ? $gift->id : 1,
        'is_redeemed' => true,
        'redeemed_at' => now(),
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Gift redeemed successfully!',
        'redirect' => route('congrats'),
      ], 200);
    });
  }

  public function checkExisting(Request $request)
  {
    $code = $request->code;
    $check = User::where("number", $code)->exists();
    return $check;
  }

  public function submitAnswer(Request $request)
  {
    $user = auth()->user();

    $question = Question::with("answers")->findOrFail($request->question_id);

    $isCorrect = $question->answers
      ->where("id", $request->answer_id)
      ->where("is_correct", 1)
      ->isNotEmpty();

    $user->questions()->syncWithoutDetaching([
      $question->id => [
        "is_correct" => $isCorrect,
      ],
    ]);

    if ($isCorrect) {
      $user
        ->developers()
        ->updateExistingPivot($question->developer_id, ["isCompleted" => 1]);
    }

    return response()->json([
      "success" => true,
      "correct" => $isCorrect,
    ]);
  }

  public function submitQuiz(Request $request)
  {
    $user = auth()->user();

    if (!$user) {
      return response()->json(
        [
          "success" => false,
          "message" => "Unauthorized",
        ],
        401
      );
    }

    $request->validate([
      "perfume_id" => "required|exists:perfumes,id",
      "station_id" => "required|exists:stations,id",
      "time_spent" => "required|integer|min:0",
    ]);

    // ✅ Save user perfume (only 1 per user)
    UserPerfume::updateOrCreate(
      ["user_id" => $user->id],
      ["perfume_id" => $request->perfume_id]
    );

    // ✅ Mark station as completed
    StationUser::updateOrCreate(
      [
        "user_id" => $user->id,
        "station_id" => $request->station_id,
        "time_spent" => $request->time_spent,
      ],
      [
        "status" => true,
      ]
    );

    return response()->json([
      "success" => true,
      "message" => "Quiz submitted successfully",
    ]);
  }

  public function prize($prize_id)
  {
      // Example: get prize info (optional, if you have a Prize model)
      $prize = \App\Models\Gifts::find($prize_id);

      return view('prize', [
          'prize_id' => $prize_id,
          'prize' => $prize
      ]);
  }
  public function prizeDone()
  {
      return auth()->user()->hasCompletedAllStations()
          ? redirect()->route('congrats')
          : redirect()->route('dashboard');
  }

  public function updateStock(Request $request, $id)
  {
      $request->validate([
          'stock_level' => 'required|integer|min:1',
          'action' => 'required|in:add,deduct'
      ]);

      $gift = Gifts::find($id);

      if (!$gift) {
          return response()->json([
              'status' => 'error',
              'message' => 'Gift not found'
          ], 404);
      }

      try {
          DB::beginTransaction();

          $beforeStock = $gift->stock_level;

          // 🔥 ADD STOCK
          if ($request->action === 'add') {
              $gift->increment('stock_level', $request->stock_level);
          }

          // 🔥 DEDUCT STOCK
          if ($request->action === 'deduct') {
              if ($gift->stock_level < $request->stock_level) {
                  return response()->json([
                      'status' => 'error',
                      'message' => 'Not enough stock to deduct'
                  ], 400);
              }

              $gift->decrement('stock_level', $request->stock_level);
          }

          // 🔥 REFRESH to get latest value
          $gift->refresh();

          // 🔥 LOG STOCK CHANGE
          GiftStockLog::create([
              'gift_id' => $gift->id,
              'user_id' => Auth::id(),
              'action' => $request->action,
              'quantity' => $request->stock_level,
              'stock_before' => $beforeStock,
              'stock_after' => $gift->stock_level,
          ]);

          DB::commit();

          return response()->json([
              'status' => 'success',
              'data' => [
                  'name' => $gift->name,
                  'current_stock' => $gift->stock_level
              ]
          ]);

      } catch (\Exception $e) {

          DB::rollback();

          return response()->json([
              'status' => 'error',
              'message' => $e->getMessage()
          ], 500);
      }
  }

  public function giftReport(Request $request,$id)
  {
    $gift = Gifts::findOrFail($id);

    // 🔥 Stock Logs
    $logs = GiftStockLog::with('user')
        ->where('gift_id', $id)
        ->when($request->action, fn($q) => $q->where('action', $request->action))
        ->when($request->date_from, fn($q) =>
            $q->whereDate('created_at', '>=', $request->date_from)
        )
        ->when($request->date_to, fn($q) =>
            $q->whereDate('created_at', '<=', $request->date_to)
        )
        ->latest()
        ->paginate(10);

    // 🔥 Redeemed users
    $redeemedUsers = UserGift::with('user')
        ->where('gift_id', $id)
        ->latest()
        ->get();
        
    return view('admin.gift-report', compact(
        'gift',
        'logs',
        'redeemedUsers'
    ));
  }

}
