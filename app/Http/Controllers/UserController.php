<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Station;
use Illuminate\Support\Facades\DB;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function users()
    {
        $stations = Station::orderBy('id')->get();
        $permission = 'default'; // Replace with actual permission logic if available
        return view('users-datatable', ['data' => ['stations' => $stations], 'permission' => $permission]);
    }

    public function getUsersForDataTable(Request $request)
    {
        $draw = $request->integer('draw', 1);
        $start = max(0, $request->integer('start', 0));
        $rowperpage = min(100, max(1, $request->integer('length', 10)));
        $columnIndex = $request->input('order.0.column', 0);
        $columnName = $request->input("columns.$columnIndex.data", 'id');
        $allowedColumns = ['id', 'name', 'email', 'number', 'country', 'email_consent',
            'isCardApply', 'terms', 'marketing', 'age_confirmed', 'created_at'];
        if (! in_array($columnName, $allowedColumns, true)) $columnName = 'id';
        $columnSortOrder = $request->input('order.0.dir') === 'asc' ? 'asc' : 'desc';
        $searchValue = trim((string) $request->input('search.value', ''));

        $totalRecords = User::participants()->count();

        $query = User::participants()->select('users.*');

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

        if ($columnName !== 'id') $query->orderBy('id', $columnSortOrder);

        $records = $query->with('stationUser')->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $stations = Station::orderBy('id')->get();

        foreach($records as $record){

            $user_stations = [];
            foreach ($stations as $station) {
                $user_station_value = $record->stationUser->firstWhere('station_id', $station->id);
                $display_value = 'No';

                if ($user_station_value) {
                    $date = \Carbon\Carbon::parse($user_station_value->created_at)->format('F j g:i A');
                    $display_value = 'Yes (' . $date . ')';
                }

                $user_stations[] = [
                    'id' => $station->id,
                    'completed' => $user_station_value !== null,
                    'name' => $station->name,
                    'value' => $user_station_value ? $user_station_value->time_spent : null,
                    'display_value' => $display_value,
                ];
            }

            // Check if user was created after August 11, 2025
            $cutoffDate = \Carbon\Carbon::create(2025, 8, 11, 23, 59, 59);
            $isNew = $record->created_at->gt($cutoffDate);
            $badge = $isNew ? '<span class="badge bg-primary badge-success ms-2">NEW</span>' : '<span class="badge bg-warning badge-secondary ms-2">OLD</span>';
            $idWithBadge = $record->id . '&nbsp;&nbsp;' . $badge;

            $data_arr[] = array(
                "id" => $idWithBadge,
                "name" => $record->fname . ' ' . $record->lname,
                "fname" => $record->fname,
                "lname" => $record->lname,
                "email" => $record->email,
                "number" => $record->number,
                "country" => $record->country,
                "email_consent" => $record->email_consent ? 'Yes' : 'No',
                "isCardApply" => $record->isCardApply ? 'Yes' : 'No',
                "terms" => $record->terms ? 'Yes' : 'No',
                "marketing" => $record->marketing ? 'Yes' : 'No',
                "age_confirmed" => $record->age_confirmed ? 'Yes' : 'No',
                "created_at" => $record->created_at->format('Y-m-d H:i:s'),
                "stations" => $user_stations,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordswithFilter,
            "data" => $data_arr,
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function export(Request $request)
    {
        $stations = Station::orderBy('id')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users.csv"',
        ];

        return new StreamedResponse(function () use ($stations) {
            $handle = fopen('php://output', 'w');

            // Add CSV headers
            $csv_headers = [
                'ID', 'Name', 'Email', 'Number', 'Country',
                'Email Consent', 'Card applied', 'Terms accepted', 'Marketing consent', 'Age 21+ confirmed', 'Created At'
            ];
            foreach ($stations as $station) {
                $csv_headers[] = $station->name;
            }
            fputcsv($handle, $csv_headers);

            User::participants()->cursor()->each(function ($user) use ($handle, $stations) {

                // Check if user was created after August 11, 2025
                $cutoffDate = \Carbon\Carbon::create(2025, 8, 11, 23, 59, 59);
                $isNew = $user->created_at->gt($cutoffDate);
                $badge = $isNew ? ' (NEW)' : ' (OLD)';
                $idWithBadge = $user->id . $badge;

                $data = [
                    $idWithBadge,
                    $user->fname . ' ' . $user->lname,
                    $user->email,
                    $user->number,
                    $user->country,
                    $user->email_consent ? 'Yes' : 'No',
                    $user->isCardApply ? 'Yes' : 'No',
                    $user->terms ? 'Yes' : 'No',
                    $user->marketing ? 'Yes' : 'No',
                    $user->age_confirmed ? 'Yes' : 'No',
                    $user->created_at->format('Y-m-d H:i:s'),
                ];

                foreach ($stations as $station) {
                    $user_station_value = $user->stations()->where('station_id', $station->id)->first();
                    $display_value = 'No';
                    if ($user_station_value) {
                        $date = \Carbon\Carbon::parse($user_station_value->pivot->created_at)->format('F j, Y g:i A');
                        $display_value = 'Yes (' . $date . ')';
                    }
                    $data[] = $display_value;
                }

                fputcsv($handle, $data);
            });

            fclose($handle);
        }, 200, $headers);
    }
}
