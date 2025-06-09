<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Station;
use App\Models\User;
use App\Models\StationUser;
use App\Models\Brand;
use App\Models\Vote;
use App\Models\Task;
use App\Models\UserTask;

use App\Models\Appointment;
use App\Events\babyEvent;

use DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use App\Providers\RouteServiceProvider;
use App\Helpers\GlobalHelper;

class StationController extends Controller
{

    public function receipt(Request $request)
    {
        $link = $request->qrCodeMessage;

        // Get the query part from the URL
        $query = parse_url($link, PHP_URL_QUERY); // returns "purchase=1"

        // Parse query string into an array
        parse_str($query, $params);

        // Access the 'purchase' value
        $purchase = $params['purchase'] ?? null;

        if($purchase == 1){
            $task = UserTask::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'task_id' => 4
                ],
                [
                    'status' => 'completed'
                ]
            );
        }

        return $task;
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/uploads', $filename);

            // Create or update the user task record
            UserTask::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'task_id' => $request->task_id
                ],
                [
                    'status' => 'in-progress',
                    'images' => $filename
                ]
            );

            // Update the appropriate image field in users table
            $user = User::find(auth()->id());

            if ($request->task_id == 2) {
                $user->task_2_image = $filename;
            } elseif ($request->task_id == 3) {
                $user->task_3_image = $filename;
            }

            $user->save();

            return response()->json(['success' => true, 'filename' => $filename]);
        }

        return response()->json(['success' => false, 'message' => 'No image found.']);
    }

    public function consent(Request $request)
    {
        // dd($request->all());
        if($request->consent == 1)
        {
            UserTask::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'task_id' => 5
                ],
                [
                    'status' => 'completed'
                ]
            );
        }

        return back()->with('success', 'Consent updated successfully.');
    }


    public function appointment()
    {
        $user = Auth::user();

        if($user->otp_verified == 0){
            return redirect()->route('otp');
        }

        $appointments = Appointment::withCount('userAppointments')
        ->get()
        ->map(function ($appointment) {
            $available = max(0, $appointment->total - $appointment->user_appointments_count);
            $appointment->available_slots = $available;
            $appointment->status = $available === 0 ? 'full' : 'available';
            return $appointment;
        });

        $is2000 = User::where('otp_verified', 1)
        ->orderBy('email_verified_at', 'asc')
        ->take(2000)
        ->pluck('id')
        ->contains(auth()->id());

        $userAppointment = $user->userAppointments()->count();
        $selectedAppointment = $user->userAppointments()->with('appointment')->first() ?? '';
        $convertedDate = '';
        if ($selectedAppointment && isset($selectedAppointment->appointment->name)) {
            try {
                $convertedDate = Carbon::createFromFormat('m-d-Y', $selectedAppointment->appointment->name)->format('l');
            } catch (\Exception $e) {
                // Handle potential parsing errors, e.g., log or set a default
                $convertedDate = 'Invalid Date';
            }
        }

        //check if user is on first 2000 verified users

        return view('appointment', compact('appointments','user','is2000','userAppointment','selectedAppointment','convertedDate'));
    }

    public function appointmentSubmit(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $user = Auth::user();
        $appointment = Appointment::find($request->appointment_id);


        if ($appointment->userAppointments()->count() >= $appointment->total) {
            return response()->json(['error' => 'No available slots for this appointment.'], 400);
        }


        $existing = $user->userAppointments()->first();

        if ($existing) {

            if ($existing->rescheduled) {
                return response()->json(['error' => 'You can only reschedule once.'], 400);
            }


            $existing->update([
                'appointment_id' => $appointment->id,
                'rescheduled' => true,
            ]);
        } else {

            $user->userAppointments()->create([
                'appointment_id' => $appointment->id,
                'rescheduled' => false,
            ]);
        }

        return response()->json(['message' => 'Appointment booked successfully.','appointment' => $existing]);
    }

    public function guessSubmit(Request $request)
    {
        $request->validate([
            'number' => 'required',
        ]);

        $user = Auth::user();
        $user->guess = $request->number;
        $user->save();

        return response()->json(['message' => 'Appointment booked successfully.','appointment' => $user]);
    }

    public function guestAndWin(Request $request)
    {
        $user = Auth::user();
        return view('guestAndWin', compact('user'));
    }

public function embarckJourney()
    {
        $user = Auth::user();


    // Get user tasks with pivot 'status'
    $userTasks = $user->tasks()->withPivot('status')->get()->keyBy('id'); // task_id as key

    // Get all tasks and attach status
    $tasks= Task::all()->map(function ($task) use ($userTasks) {
        $task->status = $userTasks[$task->id]->pivot->status ?? 'pending';
        return $task;
    });
    // dd($tasks);

    $userDone = UserTask::where('user_id', auth()->id())->where('status','!=','pending')->count();
    $totalTasks = Task::count();
    $percentage = $totalTasks > 0 ? round(($userDone / $totalTasks) * 100) : 0;


        //  dd(vars: $tasks);
        return view('embarkJourney',compact('user','tasks','userDone','totalTasks','percentage'));
    }
    public function embarckStation(Task $station)
    {
       $status = '';
        if($station->id == 1){
            $check = UserTask::where('user_id', auth()->id())->where('task_id', 1)->exists();
            $status = 'exists';
            if(empty($check)){
                $data = GlobalHelper::checkOrRegisterUser([
                    'mobile' => auth()->user()->number,
                    // 'mobile' => '+60123456786',
                    'country_code' => '60',
                    'first_name' => auth()->user()->fname,
                    'last_name' => auth()->user()->lname,
                    'email' => auth()->user()->email,
                    'subscription' => ['sms', 'email'],
                    'password' => 'Loccitane2025',
                ]);

                if($data['status'] == 'registered'){

                    $status = 'registered';
                }
                if($data['status'] == 'exists'){
                    $status = 'exists';
                }

                $task = UserTask::updateOrCreate(
                        [
                            'user_id' => auth()->id(),
                            'task_id' => 1
                        ],
                        [
                            'status' => 'completed'
                        ]
                    );

            }

        }

        $check = UserTask::where('user_id', auth()->id())->where('task_id', $station->id)->exists();


        return view('embarkStation', compact('station','status','check'));
    }

    public function preRegEvent(Request $request)
    {
        $user = Auth::user();
        $userAppointment = $user->userAppointments()->count();

        $selectedAppointment = $user->userAppointments()->with('appointment')->first() ?? '';


        return view('preRegisterView', compact('userAppointment', 'selectedAppointment'));
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

             $data = GlobalHelper::createSampleProfile();
            //  dd($data);

            return redirect(RouteServiceProvider::HOME);
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


        return $user;
    }


    public function uploadBaby(Request $request)
    {
        $request->validate([
            'baby_img' => 'required|image|max:2048', // max 2MB
            'baby_name' => 'string|max:255',
            'charname' => 'string|max:255',
        ]);

        $user = Auth::user();

        // Store the uploaded image in `public/babies`
        $path = $request->file('baby_img')->store('public/babies');

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
            $stationUser->station_id = 2;
            $stationUser->time_spent = $secondsSpent;
            $stationUser->save();

            $user->baby_img = $publicPath;
            $user->baby_name = $request->baby_name;
            if ($request->has('charname')) {
                $user->charname = $request->input('charname');
            }

            $user->save();
        // Fire the event
        broadcast(new babyEvent($publicPath, $user->baby_name,'dj',$user->charname))->toOthers();


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
            'baby_img' => 'required|image|max:2048',
        ]);


        // Store the uploaded image in `public/babies`
        $path = $request->file('baby_img')->store('public/babies');

        // Convert path to URL or relative path for saving
        $publicPath = Storage::url($path); // returns `/storage/babies/filename.gif`

        //check if image is uploaded
        if (!$publicPath) {
            return response()->json(['error' => 'Image upload failed.'], 500);
        }

        // Fire the event
        broadcast(new babyEvent($publicPath, 'ipad','ipad','ipad'))->toOthers();

        return redirect()->back();
    }

    public function getCount()
    {
        $user = User::with('stationUser')->where('id', auth()->id())->first();
        $stationDone = $user->stationUser->count();

        dd($stationDone);

        return response()->json(['count' => $stationDone]);

    }


    public function index(Station $station)
    {
        $user = StationUser::where('user_id', auth()->id())
            ->where('station_id', $station->id)
            ->exists();

        //get station count

        $completedStationCount = StationUser::where('user_id', auth()->id())->count();

        if ($station->id == 9 && $user == true) {
            return view('congrats');
        }


        if ($station->id != 3 ) {
            return view('station', compact('station', 'user', 'completedStationCount'));
        }else {
            return view('station2', compact('station', 'user','completedStationCount'));
        }

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

    public function addVote( Request $request)
    {
       $vote = $request->input('vote');

       // update  users vote_id
         $user = User::find(auth()->id());
            $user->vote_id = $vote;
            $user->save();


        // add entry for station_users station is station_id 3
        $stationUser = StationUser::where('user_id', auth()->id())->where('station_id', 3)->first();
        if ($stationUser) {
            $stationUser->save();
        } else {
            StationUser::create([
                'user_id' => auth()->id(),
                'station_id' => 3,
            ]);
        }

        $stationCompletedCount = StationUser::where('user_id', auth()->id())->count();

        // return response
        return response()->json(['message' => 'Vote added successfully.', 'stationCompletedCount' => $stationCompletedCount], 200);
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
        $canAccessStation5 = $stations->filter(fn($s) => $s->id <= 4)->every(fn($s) => $s->status == true);

        //check if user complete atlist one station


        // if ($stationDone < $stations->count()) {

        // } else {
        //     return redirect()->route('dashbord');
        // }

         return view('dashboard', compact('stations', 'stationDone', 'canAccessStation5'));
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

        $completedStationCount = StationUser::where('user_id', auth()->id())->count();

        // Assume that `$station_id` is validated before this point

        try {
            DB::beginTransaction();

            if ($station_id != $request->station) {
                return response()->json(['message' => 'Invalid Qr', 'status' => 'error'], 401);
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

            // Recalculate count after saving and committing
            $completedStationCount = StationUser::where('user_id', auth()->id())->count();

            // Success response
            return response()->json(['message' => 'Station ID updated successfully', 'completedStationCount' => $completedStationCount], 200);
        } catch (\Exception $e) {
            DB::rollback();

            // Handle the error, log it, or return an appropriate response
            return response()->json(['error' => $e], 500);
        }
    }



    public function admin()
    {
        $admin = User::find(auth()->id());
        $permission = $admin->getPermissionNames()->first();
        $today = Carbon::today();
        $startDate = Carbon::create(2025, 5, 26);
        $data['users'] = User::with('stationUser')->take(4)->orderBy('id', 'desc')->get();
        $data['usersCount'] = User::whereDate('created_at', '>=', $startDate->toDateString())->count();
        $data['userToday'] = User::whereDate('created_at', $today)->count();
        $data['country'] = User::selectRaw('country , COUNT(*) as count')->groupBy('country')->where('country' ,'!=','admin')->get();



        $usersWithSixStationUsers = User::with('stationUser')->whereDate('created_at', '>=', $startDate->toDateString())->has('stationUser', '>=', 4)->count();
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

        //   dd($data['where']);

        $data['registrationsPerHour'] = User::select(
             DB::raw('DATE(created_at) as date'),
            DB::raw('DATE_FORMAT(created_at, "%l%p") as hour'),
            DB::raw('COUNT(*) as registrations')
        )
        ->whereDate('created_at', '>=', $startDate->toDateString())
        ->groupBy('date', 'hour')
        ->get()
        ->groupBy('date');


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
                ];
            });
        }

        $data['stations'] = $stations->map(function ($name, $id) use ($userStations, $averageTimespentByStation) {
            return [
                'name' => $name,
                'average_timespent' => number_format(($averageTimespentByStation->get($id)['average_timespent'] ?? 0) / 60, 2),
            ];
        });

        $averagePlaytimeByUser = StationUser::select('user_id', DB::raw('SUM(time_spent) / 60 as total_playtime'))->groupBy('user_id')->get();

        $totalAveragePlaytime = $averagePlaytimeByUser->avg('total_playtime');
        // dd($totalAveragePlaytime);
        //dd($data['users'][0]['stations']);
        //  dd($data);

        return view('dashboardadmin', compact('data', 'permission'));
    }
    public function users()
    {
        $bottles = [
            'Chill Zone',
            'Energy Drive',
            'Full Recharge',
            'Get Comfy',
            'Happy Feels',
            'Spark Up',
        ];

        $today = Carbon::today();
        $permission = auth()->user()->getPermissionNames()->first();

        $startDate = Carbon::create(2024, 5, 24);
        $data['users'] = User::whereDate('created_at', '>=', $startDate->toDateString())->with('stationUser')->orderBy('id', 'desc')->get();

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

        $data['stations'] = $stations->map(function ($name, $id) use ($userStations, $averageTimespentByStation) {
            return [
                'name' => $name,
                'average_timespent' => number_format(($averageTimespentByStation->get($id)['average_timespent'] ?? 0) / 60, 2),
            ];
        });
        //dd($data['users'][0]['stations']);
        //  dd($data);

        return view('users', compact('data', 'permission','bottles', 'today'));
    }

    public function userVote()
    {
        $startDate = Carbon::create(2025, 5, 15);
        $permission = auth()->user()->getPermissionNames()->first(); // Preserving this line as it was in your original code

        $allBottleIds = collect(range(1, 6)); // Define the 6 bottle IDs

        // Get users who voted for bottles 1-6 and meet other criteria
        $usersWhoVoted = User::whereNotNull('vote_id')
            ->whereIn('vote_id', $allBottleIds->all()) // Filter for vote_ids 1 through 6
            ->whereDate('created_at', '>=', $startDate->toDateString())
            ->orderBy('id', 'desc')
            ->get();

        // Group these users by their vote_id
        $groupedVotes = $usersWhoVoted->groupBy('vote_id');

        // Prepare the final structure, ensuring all bottles from 1 to 6 are present
        $voteCounts = $allBottleIds->mapWithKeys(function ($bottleId) use ($groupedVotes) {
            $usersForThisBottle = $groupedVotes->get($bottleId, collect()); // Get users for this bottleId, or an empty collection if none
            return [
                $bottleId => [
                    'count' => $usersForThisBottle->count()
                ]
            ];
        });

        // Data to be passed to the view
        $data['userVote'] = $voteCounts;

        // dd($voteCounts); // This will display the structured data - commented out for view rendering

        // Original commented out lines, preserved
        // $data['users'] = User::whereDate('created_at', '>=', $startDate->toDateString())->with('stationUser')->orderBy('id', 'desc')->get();
        return view('ambient', compact('data'   , 'permission'));
    }

    public function embark()
    {
        $tasks = Task::all(); // Get all tasks

$users = User::with('tasks')->get(); // Eager load tasks for all users

$users = $users->map(function ($user) use ($tasks) {
    // Key user's tasks by task id for fast lookup
    $userTasks = $user->tasks->keyBy('id');

    // Map all tasks and attach user-specific status or default to 'pending'
    $user->all_tasks = $tasks->map(function ($task) use ($userTasks) {
        $clonedTask = clone $task; // Avoid modifying the original task object
        $clonedTask->status = $userTasks[$task->id]->pivot->status ?? 'pending';
        $clonedTask->image = $userTasks[$task->id]->pivot->images ?? '';

        return $clonedTask;
    });

    return $user;
});


        dd($users);
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
}
