<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station;
use App\Models\User;
use App\Models\StationUser;
use App\Models\Brand;
use App\Models\Vote;
use App\Events\babyEvent;
use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Providers\RouteServiceProvider;
use App\Helpers\GlobalHelper;

class StationController extends Controller
{

    public function uploadBaby(Request $request)
    {
        $request->validate([
            'pledge_image' => 'required|image|max:2048', // max 2MB
            'pledge_text' => 'string|max:255',
            'charname' => 'string|max:255',
            'pledge_type' => 'required|string|in:text,coral',
        ]);

        $user = Auth::user();

        // Store the uploaded image in `public/babies`
        $path = $request->file('pledge_image')->store('public/babies');

        // Convert path to URL or relative path for saving
        $publicPath = Storage::url($path); // returns `/storage/babies/filename.gif`

        try {
            DB::beginTransaction();


            $lastStation = StationUser::where('user_id', auth()->id())->orderBy('id', 'desc')->first();

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
            $stationUser->station_id = 4;
            $stationUser->time_spent = $secondsSpent;
            $stationUser->save();

            $user->pledge_image = $publicPath;
            $user->pledge_text = $request->pledge_text;
            if ($request->has('charname')) {
                $user->charname = $request->input('charname');
            }

            $user->save();
        // Fire the event
        broadcast(new babyEvent($publicPath, $user->pledge_text,$request->pledge_type,$user->charname))->toOthers();


            DB::commit();
            // Success response
            return response()->json(['message' => 'Station ID updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();

            // Handle the error, log it, or return an appropriate response
            return response()->json(['error' => $e], 500);
        }

        // Save to user


    }

    public function uploadBabyIpad(Request $request)
    {
        $request->validate([
            'pledge_image' => 'required|image|max:2048', // max 2MB
            'pledge_text' => 'string|max:255',
            'charname' => 'string|max:255',
            'pledge_type' => 'required|string|in:text,coral',
        ]);


        // Store the uploaded image in `public/babies`
        $path = $request->file('pledge_image')->store('public/babies');

        // Convert path to URL or relative path for saving
        $publicPath = Storage::url($path); // returns `/storage/babies/filename.gif`

        //check if image is uploaded
        if (!$publicPath) {
            return response()->json(['error' => 'Image upload failed.'], 500);
        }

        // Get charname from request or use default
        $charName = $request->charname ?? 'Baby';

        // Fire the event (use correct fields)
        broadcast(new babyEvent($publicPath, $request->pledge_text, $request->pledge_type, $charName))->toOthers();

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Baby uploaded successfully',
            'imgPath' => $publicPath,
            'name' => $charName
        ]);
    }

    function workshopCongrats() {
        return view('workshopCongrats');
    }

    public function index(Station $station)
    {
        $user = StationUser::where('user_id', auth()->id())
            ->where('station_id', $station->id)
            ->exists();
        if ($station->id == 9 && $user == true) {
            return view('congrats');
        }

        if($station->id == 2 && $user == true) {
            return view('station', compact('station', 'user'));
        }

         return view('station', compact('station', 'user'));

    }


    public function extension(Station $station)
    {
        return view('extension');
    }

    public function congratsVote()
    {
        return view('congratsVote');
    }

    public function brand(Station $station)
    {
        $brands = Brand::get();
        return view('brand', compact('brands'));
    }
    public function puzzle(Station $station)
    {
        $user = User::with('stationUser')->where('id', auth()->id())->first();
        // dd($user->stationUser->count());

        $stationDone = $user->stationUser->count();
        $stations = Station::get();

        // Loop through each station and append a flag indicating if the user has it
        foreach ($stations as $station) {
            $userHasStation = $user
                ->StationUser()
                ->where('station_id', $station->id)
                ->exists();
            $station->status = $userHasStation;
        }

        $userId = Auth::id();

        $required = DB::table('stations')
            ->leftJoin('station_users', function ($join) use ($userId) {
                $join->on('stations.id', '=', 'station_users.station_id')->where('station_users.user_id', '=', $userId);
            })
            ->select('stations.id as station_id', 'stations.name as station_name', DB::raw('IF(station_users.station_id IS NULL, false, true) as is_gotten'))
            ->distinct()
            ->orderByRaw('is_gotten DESC')
            ->where('required', 1)
            ->get();
        $puzzleRequired = DB::table('stations')
            ->leftJoin('station_users', function ($join) use ($userId) {
                $join->on('stations.id', '=', 'station_users.station_id')->where('station_users.user_id', '=', $userId);
            })
            ->select('stations.id as station_id', 'stations.name as station_name', DB::raw('IF(station_users.station_id IS NULL, false, true) as is_gotten'))
            ->distinct()
            ->where('required', 1)
            ->orderBy('station_id', 'asc')
            ->get();
        // dd($required);
        $notRequired = DB::table('stations')
            ->leftJoin('station_users', function ($join) use ($userId) {
                $join->on('stations.id', '=', 'station_users.station_id')->where('station_users.user_id', '=', $userId);
            })
            ->select('stations.id as station_id', 'stations.name as station_name', DB::raw('IF(station_users.station_id IS NULL, false, true) as is_gotten'))
            ->where('stations.required', 0) // Prioritize is_gotten=true, then order by station_id
            ->orderByRaw('is_gotten DESC')
            ->limit(2)
            ->get();
        $puzzleNotRequired = DB::table('stations')
            ->leftJoin('station_users', function ($join) use ($userId) {
                $join->on('stations.id', '=', 'station_users.station_id')->where('station_users.user_id', '=', $userId);
            })
            ->select('stations.id as station_id', 'stations.name as station_name', DB::raw('IF(station_users.station_id IS NULL, false, true) as is_gotten'))
            ->where('stations.required', 0)
            ->orderByRaw('is_gotten DESC, station_id ASC') // Prioritize is_gotten=true, then order by station_id
            ->limit(2)
            ->get();

        $giftRequired = DB::table('stations')
            ->leftJoin('station_users', function ($join) use ($userId) {
                $join->on('stations.id', '=', 'station_users.station_id')->where('station_users.user_id', '=', $userId);
            })
            ->select('stations.id as station_id', 'stations.name as station_name', DB::raw('IF(station_users.station_id IS NULL, false, true) as is_gotten'))
            ->distinct()
            ->orderByRaw('is_gotten DESC')
            ->where('required', 1)
            ->having('is_gotten', true)
            ->get();
        $giftNotRequired = DB::table('stations')
            ->leftJoin('station_users', function ($join) use ($userId) {
                $join->on('stations.id', '=', 'station_users.station_id')->where('station_users.user_id', '=', $userId);
            })
            ->select('stations.id as station_id', 'stations.name as station_name', DB::raw('IF(station_users.station_id IS NULL, false, true) as is_gotten'))
            ->distinct()
            ->orderByRaw('is_gotten DESC')
            ->where('required', 0)
            ->limit(2)
            ->having('is_gotten', true)
            ->get();
        $claim = count($giftRequired) + count($giftNotRequired);

        $nurse = DB::table('stations')
            ->leftJoin('station_users', function ($join) use ($userId) {
                $join->on('stations.id', '=', 'station_users.station_id')->where('station_users.user_id', '=', $userId);
            })
            ->select('stations.id as station_id', 'stations.name as station_name', 'stations.nurse as station_nurse', DB::raw('IF(station_users.station_id IS NULL, false, true) as is_gotten'))
            ->distinct()
            ->orderBy('stations.id', 'asc')
            ->get();

        return view('puzzle', compact('stations', 'stationDone', 'required', 'notRequired', 'puzzleRequired', 'puzzleNotRequired', 'nurse', 'claim'));
    }

    public function castVote(Request $request)
    {
        $vote = new Vote();
        $vote->brand_id = $request->brand_id;
        $vote->save();

        return $vote;
    }

    public function brands()
    {
        $brands = DB::table('brands')->leftJoin('users', 'brands.id', '=', 'users.brand_id')->select('brands.id as brand_id', 'brands.name as brand_name', DB::raw('COUNT(users.id) as count'))->groupBy('brands.id', 'brands.name')->get();
        // dd($brands);
        return view('brands', compact('brands'));
    }

    public function vote()
    {
        $brands = Brand::get();
        // dd($brands);
        return view('vote', compact('brands'));
    }

    public function voteData()
    {
        $brands = DB::table('brands')->leftJoin('votes', 'brands.id', '=', 'votes.brand_id')->select('brands.id as brand_id', 'brands.name as brand_name', DB::raw('COUNT(votes.id) as count'))->groupBy('brands.id', 'brands.name')->get();
        //dd($brands);
        return view('votes', compact('brands'));
    }

    public function welcome()
    {
        $userId = Auth::id();

        $user = User::with('stationUser')->where('id', $userId)->first();
    
        $stationDone = $user->stationUser->count();
        $stations = Station::get();

        $completedStationIds = $user->stationUser->pluck('id')->toArray();

        // Add status flag to each station
        foreach ($stations as $station) {
            $station->status = $user->stationUser->contains('station_id', $station->id);
        }

        // Determine if stations 1-4 are all completed
        $canAccessStation6 = $stations->filter(fn($s) => $s->id <= 5)->every(fn($s) => $s->status == true);

        $nextStation = $stations->firstWhere(function ($station) use ($user) {
            return !$user->stationUser()->where('station_id', $station->id)->exists();
        });

        return view('dashboard', compact('stations', 'stationDone', 'canAccessStation6', 'completedStationIds', 'nextStation'));

    }

    public function pledgeDj()
    {
        // get details of station 4
        $station = Station::find(4);
        return view('pledgeDj', compact('station'));
    }

    public function scanner()
    {
        // dd('asdasd');
        return view('scanner');
    }


    public function scan(Request $request)
    {
        // Parse the URL to get the query string

        $qrCodeMessage = trim($request->qrCodeMessage);

        // Get the last character of the QR code message
        $station_id = substr($qrCodeMessage, -1);

        if ($request->has('brand')) {
            // Fetch the authenticated user
            $user = User::with('stationUser')->find(auth()->id());

            if ($user) {
                // Update the user's brand_id
                $user->brand_id = $request->brand;
                $user->save();
            } else {
                // Handle case where user is not found (optional)
                return response()->json(['error' => 'User not found.'], 404);
            }
        }

        // Assume that `$station_id` is validated before this point

        try {
            DB::beginTransaction();

            if ($station_id != $request->station) {
                return response()->json(['message' => 'Invalid Qr', 'status' => 'error'], 400);
            }

            $lastStation = StationUser::where('user_id', auth()->id())->orderBy('id', 'desc')->first();

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
            DB::commit();
            // Success response
            return response()->json(['message' => 'Station ID updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();

            // Handle the error, log it, or return an appropriate response
            return response()->json(['error' => $e], 500);
        }
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);

        // Delete related station user entries
        $user->stationUser()->delete(); // ✅ Correct for hasMany

        // Delete the user
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }




    public function admin()
    {
        $admin = User::find(auth()->id());
        $permission = $admin->getPermissionNames()->first();
        $today = Carbon::today();
        $startDate = Carbon::create(2025, 7, 11);

        $data['users'] = User::with('stationUser')->take(4)->orderBy('id', 'desc')->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '>=', $startDate->toDateString())
            ->get();


        $data['usersCount'] = User::whereDate('created_at', '>=', $startDate->toDateString())->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '>=', $startDate->toDateString())
            ->count();
        $data['userToday'] = User::whereDate('created_at', $today)->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '>=', $startDate->toDateString())
            ->count();
        $data['country'] = User::selectRaw('country , COUNT(*) as count')->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '>=', $startDate->toDateString())
            ->groupBy('country')->where('country' ,'!=','admin')->get();


        $data['where'] = User::selectRaw('find , COUNT(*) as count')->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '>=', $startDate->toDateString())
            ->groupBy('find')->where('find' ,'!=','admin')->get();
        //  dd($data['where']);
        $data['age'] = User::selectRaw('dob , COUNT(*) as count')->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '>=', $startDate->toDateString())
            ->groupBy('dob')->where('dob', '!=', 'admin')->get();
          // dd($data['age']);


        //   dd($data['where']);

        $usersWithSixStationUsers = User::with('stationUser')->whereDate('created_at', '>=', $startDate->toDateString())->has('stationUser', '>=', 5)->count();
        // dd($usersWithSixStationUsers);
        $data['completedUsers'] = $usersWithSixStationUsers;
        // dd($usersWithSixStationUsers);

        if ($data['usersCount'] > 0) {
            $data['percentage'] = number_format(($usersWithSixStationUsers / $data['usersCount']) * 100, 2);
        } else {
            $data['percentage'] = 0; // Avoid division by zero
        }
        $userCounts = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')->groupBy('date')->orderBy('date')->get()->toArray();

        $userCountsArray = [];
        $data['dates'] = User::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'))->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), '>=', $startDate->toDateString())->groupBy('date')->get();

        $data['registrationsPerHour'] = User::select(
            DB::raw('DATE(DATE_ADD(created_at, INTERVAL 8 HOUR)) as date'),
            DB::raw('LOWER(DATE_FORMAT(DATE_ADD(created_at, INTERVAL 8 HOUR), "%l%p")) as hour'),
            DB::raw('COUNT(*) as registrations')
        )
            ->whereNotNull('created_at')
            ->where(DB::raw('DATE(DATE_ADD(created_at, INTERVAL 8 HOUR))'), '>=', $startDate->toDateString())
            ->groupBy('date', 'hour')
            ->havingRaw('hour IS NOT NULL AND hour <> \'\'')
            ->get()
            ->groupBy('hour');

        foreach ($userCounts as $userCount) {
            if ($userCount['date'] >= $startDate->toDateString()) {
                $userCountsArray[$userCount['date']] = $userCount['count'];
            }
        }
        $data['usersDaily'] = $userCountsArray;
        // $completed = StationUser::w

        $averageTimespentByStation = StationUser::select('station_id', \DB::raw('AVG(time_spent) as average_timespent'))->groupBy('station_id')->get()->keyBy('station_id');

        $stations = Station::pluck('name', 'id');

        $count = 0;

      foreach ($data['users'] as $user) {
            $userStations = $user->stationUser->pluck('station_id')->toArray();
            $numStations = count($userStations);

            $user->stations = $stations->map(function ($name, $id) use ($userStations, $averageTimespentByStation) {
                return [
                    'name' => $name,
                    'value' => in_array($id, $userStations),
                    'id' => $id,
                ];
            });

            // Add completed_count to the user
            $user->completed_count = $numStations;
        }

        $data['stations'] = $stations->map(function ($name, $id) use ($userStations, $averageTimespentByStation) {
            return [
                'name' => $name,
                'average_timespent' => number_format(($averageTimespentByStation->get($id)['average_timespent'] ?? 0) / 60, 2),
                'id' => $id,
            ];
        });


        $averagePlaytimeByUser = StationUser::select('user_id', DB::raw('SUM(time_spent) / 60 as total_playtime'))->groupBy('user_id')->get();

        $totalAveragePlaytime = $averagePlaytimeByUser->avg('total_playtime');


        // get all users race column for pie chart
        $data['race'] = User::where('race', '!=', 'admin')
            ->whereDate('created_at', '>=', $startDate->toDateString())
            ->selectRaw('race, COUNT(*) as count')
            ->groupBy('race')
            ->get()
            ->map(function($item) {
                return [
                    'race' => $item->race,
                    'count' => $item->count
                ];
            })
            ->values()
            ->toArray();

        return view('dashboardadmin', compact('data', 'permission'));
    }

    public function users()
    {
        $today = Carbon::today();
        $permission = auth()->user()->getPermissionNames()->first();

        $startDate = Carbon::create(2025, 6, 17);
        $data['users'] = User::whereDate('created_at', '>=', $startDate->toDateString())->with('stationUser')->orderBy('id', 'desc')->get();

        $data['usersCount'] = User::whereDate('created_at', '>=', $startDate->toDateString())->count();
        $data['userToday'] = User::whereDate('created_at', $today)->count();

        $usersWithSixStationUsers = User::whereDate('created_at', '>=', $startDate->toDateString())->has('stationUser', '>=', 5)->count();
        $data['completedUsers'] = $usersWithSixStationUsers;

        if ($data['usersCount'] > 0) {
            $data['percentage'] = number_format(($usersWithSixStationUsers / $data['usersCount']) * 100, 2);
        } else {
            $data['percentage'] = 0; // Avoid division by zero
        }

        $averageTimespentByStation = StationUser::select('station_id', \DB::raw('AVG(time_spent) as average_timespent'))->groupBy('station_id')->get()->keyBy('station_id');

        $stations = Station::pluck('name', 'id');

        foreach ($data['users'] as $user) {
            $userStations = $user->stationUser->pluck('station_id')->toArray();
            $user->stations = $stations->map(function ($name, $id) use ($userStations, $averageTimespentByStation) {
                return [
                    'name' => $name,
                    'value' => in_array($id, $userStations),
                ];
            });
        }

        $data['stations'] = $stations->map(function ($name, $id) use ($averageTimespentByStation) {
            return [
                'name' => $name,
                'average_timespent' => number_format(($averageTimespentByStation->get($id)['average_timespent'] ?? 0) / 60, 2),
            ];
        });

        return view('users', compact('data', 'permission'));
    }

    public function userData(User $user)
    {
        $averagePlaytimeByUser = StationUser::where('user_id', $user->id)->avg('time_spent');
        $permission = auth()->user()->getPermissionNames()->first();

        $stations = Station::pluck('name', 'id');

        $averageTimespentByStation = StationUser::where('user_id', $user->id)
            ->orderBy('id', 'asc')
            ->get();
        $total = StationUser::where('user_id', $user->id)
            ->orderBy('id', 'asc')
            ->sum('time_spent');
        $totalMinutes = $total / 60;
        $totalMinutes = number_format($totalMinutes, 2);

        $userStations = $user->stationUser->pluck('station_id')->toArray();
        $numStations = count($userStations);

        $user->stations = $stations->map(function ($name, $id) use ($userStations, $user) {
            $spent = StationUser::where('user_id', $user->id)
                ->where('station_id', $id)
                ->first();
            if (!$spent) {
                $minute = 0;
            } else {
                $seconds = $spent->time_spent;
                $minute = $seconds / 60;
                $minute = number_format($minute, 2);
            }
            return [
                'name' => $name,
                'value' => in_array($id, $userStations),
                'time_spent' => $minute,
                'id' => $id,
            ];
        });

        return view('userData', compact('user', 'totalMinutes', 'permission'));
    }

       public function editUser(Request $request)
    {
        $user = User::find($request->id);

        if ($user) {
            $user->email = $request->email;
            $user->save();

            return response()->json(['success' => true, 'message' => 'User email updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    public function check(Request $request)
    {
        $check = StationUser::where('user_id', $request->user_id)
            ->where('station_id', $request->station_id)
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
        $otp = implode('', $request->input('otp'));
        // dd(auth()->user());
        if ($otp == auth()->user()->otp) {

            // Success: Clear session OTP
            Session::forget(['otp', 'otp_sent_at']);
            $user= auth()->user();
            $user->otp_verified = 1;
            $user->email_verified_at = Carbon::now();
            $user->save();

            // $data = GlobalHelper::createSampleProfile();
            //  dd($data);

            // return redirect(RouteServiceProvider::HOME);
            return redirect()->route('register.welcome');
        }

        return back()->withErrors(['otp' => 'Invalid OTP']);
    }

    public function resend(Request $request)
    {
        $user = auth()->user();

        $otp = rand(100000, 999999);

         GlobalHelper::sendOtpSms($user->number, $otp);

        $user->otp = $otp;
        $user->save();


        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully.'
        ]);
    }

    public function getValue()
    {

        // Count all image files in storage/app/public/babies
        $imageCount = collect(\Storage::files('public/babies'))
            ->filter(function($file) {
                return preg_match('/\\.(jpg|jpeg|png|gif|webp)$/i', $file);
            })
            ->count();

            $imageCount = $imageCount + 3000;
        return response()->json(['count' => $imageCount]);
    }

    public function verifyAdmin(Request $request)
    {
        $otp = $request->input('otp');
        $userId = $request->input('user_id'); // Get user ID from the request

        $user = User::find($userId); // Find the user by ID

        if (!$user) {
            return back()->withErrors(['user' => 'User not found']);
        }

        if ($otp == $user->otp) {
            // Success: Clear session OTP
            Session::forget(['otp', 'otp_sent_at']);
            $user->otp_verified = 1;
            $user->email_verified_at = Carbon::now();
            $user->save();

            //  $data = GlobalHelper::createSampleProfile();
              return back()->with('success', 'OTP verified successfully!');
        }

        return back()->withErrors(['otp' => 'Invalid OTP']);
    }

}
