<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Station;
use App\Models\UserAppointment;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function users()
    {
        $stations = Station::all();
        $permission = 'default'; // Replace with actual permission logic if available
        return view('users-datatable', ['data' => ['stations' => $stations], 'permission' => $permission]);
    }

    public function getUsersForDataTable(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $totalRecords = User::count();

        $query = User::query()->select('users.*');

        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('fname', 'like', '%' . $searchValue . '%')
                  ->orWhere('lname', 'like', '%' . $searchValue . '%')
                  ->orWhere('email', 'like', '%' . $searchValue . '%')
                  ->orWhere('number', 'like', '%' . $searchValue . '%');
            });
        }

        $totalRecordswithFilter = $query->count();

        if ($columnName == 'name') {
            $query->orderBy('fname', $columnSortOrder)->orderBy('lname', $columnSortOrder);
        } else {
            $query->orderBy($columnName, $columnSortOrder);
        }

        $records = $query->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $stations = Station::all();

        foreach($records as $record){
            $appointment_dates = $record->userAppointments->map(function($ua) {
                return $ua->appointment->name;
            })->implode(', ');

            if (empty($appointment_dates)) {
                $appointment_dates = 'n/a';
            }

            $user_stations = [];
            foreach ($stations as $station) {
                $user_station_value = $record->stations()->where('station_id', $station->id)->first();
                $display_value = 'No';
                if ($user_station_value) {
                    $date = \Carbon\Carbon::parse($user_station_value->pivot->created_at)->format('F j');
                    $display_value = 'Yes (' . $date . ')';
                }
                $user_stations[] = [
                    'name' => $station->name,
                    'value' => $user_station_value ? $user_station_value->pivot->time_spent : null,
                    'display_value' => $display_value,
                ];
            }

            $data_arr[] = array(
                "id" => $record->id,
                "name" => $record->fname . ' ' . $record->lname,
                "fname" => $record->fname,
                "lname" => $record->lname,
                "dob" => $record->dob,
                "email" => $record->email,
                "number" => $record->number,
                "country" => $record->country,
                "utm_source" => $record->utm_source,
                "sms_consent" => $record->sms_consent ? 'Yes' : 'No',
                "email_consent" => $record->email_consent ? 'Yes' : 'No',
                "alliance_bank" => $record->alliance_bank ? 'Yes' : 'No',
                "created_at" => $record->created_at->format('Y-m-d H:i:s'),
                "appointment_dates_string" => $appointment_dates,
                "stations" => $user_stations,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function export(Request $request)
    {
        $stations = Station::all();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users.csv"',
        ];

        return new StreamedResponse(function () use ($stations) {
            $handle = fopen('php://output', 'w');

            // Add CSV headers
            $csv_headers = [
                'ID', 'Name', 'Date of Birth', 'Email', 'Number', 'Country', 'UTM Source',
                'SMS Consent', 'Email Consent', 'Alliance Bank', 'Created At', 'Appointments'
            ];
            foreach ($stations as $station) {
                $csv_headers[] = $station->name;
            }
            fputcsv($handle, $csv_headers);

            User::cursor()->each(function ($user) use ($handle, $stations) {
                $appointment_dates_string = $user->userAppointments->map(function($ua) { return $ua->appointment->name; })->implode(', ');
                if (empty($appointment_dates_string)) {
                    $appointment_dates_string = 'n/a';
                }

                $data = [
                    $user->id,
                    $user->fname . ' ' . $user->lname,
                    $user->dob,
                    $user->email,
                    $user->number,
                    $user->country,
                    $user->utm_source,
                    $user->sms_consent ? 'Yes' : 'No',
                    $user->email_consent ? 'Yes' : 'No',
                    $user->alliance_bank ? 'Yes' : 'No',
                    $user->created_at->format('Y-m-d H:i:s'),
                    $appointment_dates_string,
                ];

                foreach ($stations as $station) {
                    $user_station_value = $user->stations()->where('station_id', $station->id)->first();
                    $display_value = 'No';
                    if ($user_station_value) {
                        $date = \Carbon\Carbon::parse($user_station_value->pivot->created_at)->format('F j');
                        $display_value = 'Yes (' . $date . ')';
                    }
                    $data[] = $display_value;
                }

                fputcsv($handle, $data);
            });

            fclose($handle);
        }, 200, $headers);
    }

    public function getUsersForDataFormatted()
    {
        $users = User::where('dob', '!=', 'admin')->get();
        $formattedData = [];

        foreach ($users as $user) {
            $formattedData[] = [
                'fname' => $user->fname,
                'lname' => $user->lname,
                'dob' => $user->dob,
                'email' => $user->email,
                'number' => $user->number,
                'country' => $user->country,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'utm_source' => $user->utm_source,
                'utm_medium' => $user->utm_medium,
                'otp' => $user->otp,
                'otp_verified' => $user->otp_verified,
                'password' => $user->password,
                'type' => $user->type,
                //check if user has station 6
                'hasRedeemed' => $user->stations()->where('station_id', 6)->exists()
            ];
        }

        return response()->json($formattedData);
    }
}
