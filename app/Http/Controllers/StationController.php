<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\UserAppointment;
use Illuminate\Http\Request;
use App\Models\Station;
use App\Models\User;
use App\Models\StationUser;
use App\Models\Brand;
use App\Models\Vote;
use App\Models\Task;
use App\Models\UserTask;
use App\Models\Staff;
use App\Models\Products; // Added for product selection
use App\Models\UserProducts; // Added for saving to user_products table
use Illuminate\Support\Str;
use App\Models\Appointment;
use App\Events\babyEvent;

use DB;
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

    public function map(){
        $user = User::with('stationUser')->where('id', auth()->id())->first();
        $stationDone = $user->stationUser()->where('station_id', '!=', 7)->count();
        $canStation6 = $user->stationUser()->where('station_id', '!=', 7)
            ->where('station_id', '!=', 6)->count() == 5;
            // dd($canStation6);
        $stations = Station::where('id', '!=', 7)->get();


        // Loop through each station and append a flag indicating if the user has it
        foreach ($stations as $station) {
            $userHasStation = $user
                ->stationUser()
                ->where('station_id', $station->id)
                ->exists();
            $station->status = $userHasStation;
        }

           $appointments = Appointment::where('status', '1')->withCount('userAppointments')
        ->get()
        ->map(function ($appointment) {
            $available = max(0, $appointment->total - $appointment->user_appointments_count);
            $appointment->available_slots = $available;
            $appointment->status = $available === 0 ? 'full' : 'available';
            return $appointment;
        });

        $claimed = StationUser::where('user_id', auth()->id())
            ->where('station_id', 7)
            ->exists();
        if ($claimed) {
            $is2000 = false; // User has already claimed the station, so they are not in the first 2000
        }else{
            $is2000 = StationUser::where('station_id', 7)
            ->whereBetween('created_at', ['2025-06-24 00:00:00', '2025-06-30 23:59:59'])
            ->count() != 500;
        }

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

        if (is_null(Auth::user()->staff_id)) {
            $selectedStaff = 'no staff id selected';
        } else {
            $selectedStaff = Staff::find(Auth::user()->staff_id)->name;
        }

        // find the next station that the user has not completed
        $nextStation = $stations->firstWhere(function ($station) use ($user) {
            return !$user->stationUser()->where('station_id', $station->id)->exists();
        });

        return view('map', compact('canStation6','stations', 'stationDone', 'appointments', 'is2000', 'userAppointment', 'selectedAppointment', 'convertedDate', 'user', 'selectedStaff', 'nextStation'));
    }

    public function editUser(Request $request)
    {
        $user = User::find($request->id);

        if ($user) {
            $user->email = $request->email;
            $user->alliance_bank = $request->alliance_bank;
            if ($request->has('dob')) {
                $user->dob = $request->dob;
            }
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User email, DOB, and Alliance Bank status updated successfully',
                'data' => [
                    'email' => $user->email,
                    'dob' => $user->dob,
                    'alliance_bank' => $user->alliance_bank
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }

    public function tasksComplete(Request $request)
    {
        $task = UserTask::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'task_id' => $request->task_id
            ],
            [
                'status' => 'completed'
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Task marked as completed',
            'data' => $task
        ]);
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

    public function submitPledge(Request $request)
    {
        UserTask::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'task_id' => 4,
                'images' => $request->pledge
            ],
            [
                'status' => 'completed'
            ]
        );

        return back()->with('success', 'Thank you for your response!');
    }


    public function appointment()
    {
        $user = Auth::user();

        if($user->otp_verified == 0){
            return redirect()->route('otp');
        }


        $appointments = Appointment::withCount('userAppointments')->where('status',1)
        ->get()
        ->map(function ($appointment) {
            $available = max(0, $appointment->total - $appointment->user_appointments_count);
            $appointment->available_slots = $available;
            $appointment->status = $available === 0 ? 'full' : 'available';
            return $appointment;
        });



        // Check if all appointment slots have been occupied
        $is2000 = Appointment::where('status', 1)
            ->get()
            ->every(function ($appointment) {
                $occupiedSlots = $appointment->userAppointments()->count();
                return $occupiedSlots < $appointment->total;
            });

        // $claimed = StationUser::where('user_id', auth()->id())
        //     ->where('station_id', 7)
        //     ->exists();
        // if ($claimed) {
        //     $is2000 = false; // User has already claimed the station, so they are not in the first 2000
        // }else{
        //     $is2000 = StationUser::where('station_id', 7)
        //     ->whereBetween('created_at', ['2025-06-24 00:00:00', '2025-06-30 23:59:59'])
        //     ->count() != 500;
        // }

        // dd($is2000);



        //  dd($is2000);

        $userAppointment = $user->userAppointments()
            ->whereHas('appointment', function ($q) {
                $q->where('status', 1);
            })
            ->count();
        $selectedAppointment = $user->userAppointments()
            ->whereHas('appointment', function ($q) {
                $q->where('status', 1);
            })->with('appointment')->first() ?? '';

        $convertedDate = '';


        if ($selectedAppointment && isset($selectedAppointment->appointment->name)) {
            try {
                $convertedDate = Carbon::createFromFormat('m-d-Y', $selectedAppointment->appointment->name)->format('l');
            } catch (\Exception $e) {
                // Handle potential parsing errors, e.g., log or set a default
                $convertedDate = 'Invalid Date';
            }
        }
        //  dd($selectedAppointment);

        //check if user is on first 2000 verified users


        return view('appointment', compact('appointments','user','is2000','userAppointment','selectedAppointment','convertedDate'));
    }

    public function regCongrats()
    {
        $user = Auth::user();
        return view('tempCongrats', compact('user'));
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
        $data = UserTask::where('user_id', auth()->id())->where('task_id', $station->id)->first();
        // dd($data);
        // dd($check);

        return view('embarkStation', compact('station','status','check','data'));
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

             $data = GlobalHelper::createSampleProfile();
              return back()->with('success', 'OTP verified successfully!');
        }


        return back()->withErrors(['otp' => 'Invalid OTP']);
    }

    public function verifyUserInAdmin(Request $request){
        $user = User::find($request->id);

        if ($user->otp_verified == 0) {
            return response()->json(['error' => 'User is not verified'], 403);
        }

        return response()->json(['message' => 'User is verified'], 200);
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

    public function index(Station $station)
    {

        $stationDescription = [
            1 => 'Experience the ocean’s wonder—and the urgent reality beneath the waves',
            2 => 'Drop off your used plastics here and discover how they’re transformed — not just recycled, but upcycled',
            3 => 'Experience a moment of indulgence — nourishing your skin with rich almond oil for a smoother body, softer skin, and a soothed sense',
            4 => 'Experience a personalised skin consultation and begin your journey to radiant skin',
            5 => 'Experience a personalised hair and scalp analysis designed to uncover your unique needs',
            6 => 'Redeem your complimentary 5-piece sample kit— beauty essentials crafted with care for a conscious choice',
        ];


        $selectedStationDescription = $stationDescription[$station->id];

        $user = StationUser::where('user_id', auth()->id())
            ->where('station_id', $station->id)
            ->exists();


        // It seems there was a logic issue here. If station is 2 and user is true,
        // we still need to pass all relevant data for the station view.
        // The original code would only pass station and user, missing descriptions, staff, products etc.
        // Let's ensure all necessary data is passed regardless of this specific condition if it renders the same 'station' view.

        $stafs = Staff::all();
        $selectedStaff = Staff::find(Auth::user()->staff_id);


        // Fetch user's selected product from user_products table
        // The user might have multiple entries in UserProducts if they change their selection.
        // We'll take the latest one based on creation order.
        $userProductEntries = UserProducts::where('user_id', Auth::id())->latest()->get();

        $selectedProduct = Products::whereIn('id', $userProductEntries->pluck('products_id'))->get();
      //  dd($selectedProduct);

        $products = Products::whereNotIn('id', $userProductEntries->pluck('products_id'))->get(); // Fetch all products


        // dd($selectedProduct); // Original debug line, commented out as part of the fix

        return view('station', compact(
            'station',
            'user',
            'selectedStationDescription',
            'stafs',
            'selectedStaff',
            'products',         // Pass products to the view
            'selectedProduct'   // Pass selected product to the view
        ));
    }

    public function saveStaff(Request $request)
    {
       // save staff_id on user table
        $user = Auth::user();
        $user->staff_id = $request->staff_id;
        $user->save();

        return response()->json(['message' => 'Staff saved successfully']);
    }

    // New method to save product selection
    public function saveProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();

        // Save to user_products table
        UserProducts::create([
            'user_id' => $user->id,
            'products_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Product saved successfully']);
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

        if($user->otp_verified == 0){
            return redirect()->route('otp');
        }

        if ($user->userAppointments()->count() == 0) {
            return redirect()->route('appointment');
        }

        $stationDone = $user->stationUser->count();
        $stations = Station::where('id','!=','7')->get();

        $completedStationIds = $user->stationUser->pluck('id')->toArray();

        // Add status flag to each station
        foreach ($stations as $station) {
            $station->status = $user->stationUser->contains('station_id', $station->id);
        }

        // Determine if stations 1-4 are all completed
        $canAccessStation5 = $stations->filter(fn($s) => $s->id <= 4)->every(fn($s) => $s->status == true);

        //check if user complete atlist one station


        if ($stationDone < 5) {
            return view('dashboard', compact('stations', 'stationDone', 'canAccessStation5'));
        } else {
            return redirect()->route('congrats');
        }
    }

    public function scanner()
    {
        return view('scanner');
    }



    public function scan(Request $request)
    {
        // Parse the URL to get the query string

        $qrCodeMessage = trim($request->qrCodeMessage);

        // Get the last character of the QR code message
        $station_id = substr($qrCodeMessage, -1);


        // Assume that `$station_id` is validated before this point

        try {
            DB::beginTransaction();

            if($request->station == 7){

                $qrMessage = $request->qrCodeMessage;


                $expectedBase = 'https://oceanorplastic.experienceloccitane.com/' . 'user?id=';
                if (Str::startsWith($qrMessage, $expectedBase)) {
                    $id = Str::after($qrMessage, $expectedBase);
                }

                // if (!Str::startsWith($qrMessage, $expectedBase)) {
                //     return response()->json([
                //         'message' => 'Invalid QR code. Please try again.',
                //         'status' => 'invalid'
                //     ], 200);
                // }

                $check = StationUser::where('user_id', $id)->where('station_id', 7)->exists();
                if ($check) {
                    return response()->json([
                        'message' => 'You have already redeemed this QR code.',
                        'status' => 'already_redeemed'
                    ], 200);
                }

                $stationUser = new StationUser();
                $stationUser->user_id = $id;
                $stationUser->station_id = $request->station;
                $stationUser->time_spent = 0;
                $stationUser->save();

                // $userAppointment = UserAppointment::where('user_id', $id)->where('is_attended', 0)->first();
                // $userAppointment->is_attended = 1;
                // $userAppointment->save();
                DB::commit();

                return response()->json([
                    'message' => 'Successfully attended.',
                    'status' => 'success'
                ], 200);
            }

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
            // Success response


            //logger
            $logData = [
                'user_id' => auth()->id(),
                'station_id' => $station_id,
                'time_spent' => $secondsSpent,
                'created_at' => now(),
            ];
            \Log::info('Station ID updated from user', $logData);

            return response()->json(['message' => 'Station ID updated successfully'], 200);
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
        $startDate = Carbon::create(2024, 9, 24);
        $data['users'] = User::with('stationUser')->take(4)->orderBy('id', 'desc')->get();
        $data['usersCount'] = User::whereDate('created_at', '>=', $startDate->toDateString())->count();
        $data['userToday'] = User::whereDate('created_at', $today)->count();
        $data['country'] = User::selectRaw('country , COUNT(*) as count')->groupBy('country')->where('country' ,'!=','admin')->get();



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

        //   dd($data['where']);
        $data['registrationsPerHour'] = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('LOWER(DATE_FORMAT(created_at, "%l%p")) as hour'),
                DB::raw('COUNT(*) as registrations')
            )
                ->whereNotNull('created_at')
                ->where(DB::raw('DATE(created_at)'), '>=', $startDate->toDateString())
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
        // dd($totalAveragePlaytime);
        //dd($data['users'][0]['stations']);
        //  dd($data);

        return view('dashboardadmin', compact('data', 'permission'));
    }


    public function logUser(){
        // get all user with station user appointments and apointment names
        $users = User::with(['stationUser', 'userAppointments.appointment:id,name'])
            ->orderBy('id', 'desc')
            ->get();
        $averageTimespentByStation = StationUser::select('station_id', \DB::raw('AVG(time_spent) as average_timespent'))
            ->groupBy('station_id')
            ->get()
            ->keyBy('station_id');
        $stations = Station::pluck('name', 'id');



        foreach ($users as $user) {
            $userStations = $user->stationUser->pluck('station_id')->toArray();
            $user->stations = $stations->map(function ($name, $id) use ($userStations, $averageTimespentByStation) {
                return [
                    'name' => $name,
                    'value' => in_array($id, $userStations),
                ];
            });
        }


        dd($users);
    }

    public function users(Request $request)
    {
        $today = Carbon::today();
        $permission = auth()->user()->getPermissionNames()->first();
        // Retrieve filter inputs as date range
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $keyword = $request->get('keyword');

        $startDate = Carbon::create(2025, 6, 24);
        $query = User::query();
        // Apply date range filters
        $query->whereDate('created_at', '>=', $startDate->toDateString());
        if ($start_date) {
            $query->whereDate('created_at', '>=', $start_date);
        }
        if ($end_date) {
            $query->whereDate('created_at', '<=', $end_date);
        }
        $data['users'] = $query
         ->with([
             'stationUser',
             'userAppointments.appointment:id,name'
         ])
        ->orderBy('id', 'desc')
        ->get();

        $averageTimespentByStation = StationUser::select('station_id', \DB::raw('AVG(time_spent) as average_timespent'))->groupBy('station_id')->get()->keyBy('station_id');

        $stations = Station::select('id', 'name', 'created_at')->get();

        foreach ($data['users'] as $user) {
            $userStationsWithData = $user->stationUser->keyBy('station_id');

            $user->stations = $stations->map(function ($station) use ($userStationsWithData, $averageTimespentByStation) {
                $userStationData = $userStationsWithData->get($station->id);
                $value = !is_null($userStationData);
                $displayValue = '';

                if ($value) {
                    if ($station->id == 6 || $station->id == 7) {
                        $displayValue = $userStationData->created_at ? \Carbon\Carbon::parse($userStationData->created_at)->format('M d') : 'N/A';
                    } else {
                        $displayValue = 'Yes';
                    }
                } else {
                    $displayValue = 'No';
                }

                return [
                    'name' => $station->name,
                    'id' => $station->id,
                    'display_value' => $displayValue,
                    'value' => $value,
                ];
            });

            // Pre-process appointment dates into a simple string
            $appointmentDates = collect($user->userAppointments)->map(function($ua) {
                try {
                    return \Carbon\Carbon::createFromFormat('m-d-Y', $ua->appointment->name)->format('d M');
                } catch (\Exception $e) {
                    return null;
                }
            })->filter()->implode(', ');

            $user->appointment_dates_string = !empty($appointmentDates) ? $appointmentDates : 'No dates are selected here';

            // Unset relationships to avoid passing complex objects
            unset($user->userAppointments);
            unset($user->stationUser);
        }

        //  dd($data['users'][0]['stations']);

        $data['stations'] = $stations->map(function ($station) use ($averageTimespentByStation) {
            $avgData = $averageTimespentByStation->get($station->id);
            return [
                'name' => $station->name,
                'average_timespent' => number_format(($avgData->average_timespent ?? 0) / 60, 2),
            ];
        });
         dd($data);
        // Provide date options and default filter values
        $data['dates'] = User::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
        // Render view with selected filter values
        return view('users', compact('data', 'permission', 'start_date', 'end_date', 'keyword'));
    }

      public function usersFilter(Request $request, $date, $keyword = null)
    {
        $permission = auth()->user()->getPermissionNames()->first();
        $selectedDate = $date ? Carbon::parse($date) : null;

        $query = User::query();

        if ($keyword) {
            // If keyword is present, search by keyword and ignore date filter
            $query->where(function ($q) use ($keyword) {
                $q->where('fname', 'like', "%{$keyword}%")
                  ->orWhere('lname', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('number', 'like', "%{$keyword}%");
            });
        } elseif ($date) {
            // Apply date filter only when no keyword
            // $query->whereDate('created_at', '=', $date);
        }

        $eloquent_users = $query->with([
            'stationUser',
            'userAppointments.appointment:id,name'
        ])
        ->orderBy('id', 'desc')
        ->get();

        $averageTimespentByStation = StationUser::select('station_id', \DB::raw('AVG(time_spent) as average_timespent'))->groupBy('station_id')->get()->keyBy('station_id');

        $stations = Station::select('id', 'name', 'created_at')->get();

        $plain_users = [];
        foreach ($eloquent_users as $user) {
            $userStationsWithData = $user->stationUser->keyBy('station_id');

            $user_stations = $stations->map(function ($station) use ($userStationsWithData) {
                $userStationData = $userStationsWithData->get($station->id);
                $value = !is_null($userStationData);
                $displayValue = '';

                if ($value) {
                    if ($station->id == 6 || $station->id == 7) {
                        $displayValue = $userStationData->created_at ? \Carbon\Carbon::parse($userStationData->created_at)->format('M d') : 'N/A';
                    } else {
                        $displayValue = 'Yes';
                    }
                } else {
                    $displayValue = 'No';
                }

                return [
                    'name' => $station->name,
                    'id' => $station->id,
                    'display_value' => $displayValue,
                    'value' => $value,
                ];
            })->toArray();

            // Pre-process appointment dates into a simple string
            $appointmentDates = collect($user->userAppointments)->map(function($ua) {
                try {
                    return \Carbon\Carbon::createFromFormat('m-d-Y', $ua->appointment->name)->format('d M');
                } catch (\Exception $e) {
                    return null;
                }
            })->filter()->implode(', ');

            $appointment_dates_string = !empty($appointmentDates) ? $appointmentDates : 'No dates are selected here';

            $station6Data = $userStationsWithData->get(6);
            if ($station6Data) {
                $redeem_date_string = \Carbon\Carbon::parse($station6Data->created_at)->format('d M h:i A');
            } else {
                $redeem_date_string = 'not redeemed';
            }

            $plain_users[] = [
                'id' => $user->id,
                'fname' => $user->fname,
                'lname' => $user->lname,
                'dob' => $user->dob,
                'email' => $user->email,
                'number' => $user->number,
                'country' => $user->country,
                'utm_source' => $user->utm_source,
                'sms_consent' => $user->sms_consent,
                'email_consent' => $user->email_consent,
                'alliance_bank' => $user->alliance_bank,
                'created_at' => $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M h:i A') : 'N/A',
                'appointment_dates_string' => $appointment_dates_string,
                'stations' => $user_stations,
                'redeem_date' => $redeem_date_string,
            ];
        }

        $data['users'] = $plain_users;
        // dd($data['users']);

        //  dd($data['users'][0]['stations']);

        $data['stations'] = $stations->map(function ($station) use ($averageTimespentByStation) {
            $avgData = $averageTimespentByStation->get($station->id);
            return [
                'name' => $station->name,
                'average_timespent' => number_format(($avgData->average_timespent ?? 0) / 60, 2),
            ];
        });
        // dd($data);

        // get all dates that have data
        $data['dates'] = User::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as date'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return view('users', compact('data', 'permission', 'selectedDate', 'keyword'));
    }

    public function ambient()
    {
        $startDate = Carbon::create(2025, 5,15);
        $permission = auth()->user()->getPermissionNames()->first();


        $data['users'] = User::whereDate('created_at', '>=', $startDate->toDateString())->with('stationUser')->orderBy('id', 'desc')->get();
        return view('ambient', compact('data'   , 'permission'));
    }

    public function embark()
    {
        // dd('embark');
        $tasks = Task::all(); // Get all tasks

        $users = User::with(['tasks' => function ($query) {
            $query->withPivot('status', 'images', 'created_at', 'updated_at'); // Eager load pivot fields
        }])->where('id','>',3000)->orderBy('id','desc')->get();

        $users = $users->map(function ($user) use ($tasks) {
            // Key user's tasks by task id for fast lookup
            $userTasks = $user->tasks->keyBy('id');

            // Map all tasks and attach user-specific status or default to 'pending'
            $user->all_tasks = $tasks->map(function ($task) use ($userTasks) {
                $clonedTask = clone $task; // Avoid mpodifying the original task object
                $userTaskPivot = $userTasks->get($task->id); // Get the specific user task pivot data

                $clonedTask->status = $userTaskPivot->pivot->status ?? 'pending';
                $clonedTask->image = $userTaskPivot->pivot->images ?? '';
                $clonedTask->submission_date = $userTaskPivot->pivot->updated_at ?? null; // Use updated_at or created_at

                return $clonedTask;
            });

            $user->can_redeem = $user->all_tasks->where('status', 'completed')->count() >= 3;

            // dd($user);

            return $user;
        });

        // count of users who can redeem
        $redeemableUsersCount = $users->filter(function ($user) {
            return $user->can_redeem;
        })->count();

        // Retrieve IDs of users who can redeem
        $redeemableUserIds = $users->filter(function ($user) {
            return $user->can_redeem;
        })->pluck('id');
        // dd(count($users));

        // Log or display the IDs of redeemable users
        // dd($redeemableUserIds);

        return view('embark', compact('users'));
    }

    public function redeem(Request $request)
    {
        $user = User::findOrFail($request->user_id);


            // Example: Update a flag or create a redemption record
            $user->redeem_date = Carbon::now();
            $user->save();

            return response()->json(['success' => true, 'message' => 'Redemption successful']);
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

        $hasStation6 = $user->stations->contains('id', 6);
        // user has hasredeemed == 1 or has station 6
        $isRedeemed = $user->hasRedeemed == 1 || $hasStation6;
        return view('userData', compact('user', 'totalMinutes', 'permission', 'isRedeemed'));
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

        // add logger
        \Log::info('Station user checked from admin', [
            'user_id' => $request->user_id,
            'station_id' => $request->station_id,
            'exists' => (bool)$check,
        ]);

        return $check;
    }

    public function dumpDetails(Request $request)
    {
    // get all appointments data that is enabled
        $appointments = Appointment::where('status', '1')->get();

        dd($appointments);
    // Return the processed data
     return response()->json($data);
    }
}
