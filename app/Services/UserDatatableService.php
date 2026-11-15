<?php

namespace App\Services;

use App\Exports\UsersExport;
use App\Models\Developer;
use App\Models\Station;
use App\Models\StationUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserDatatableService
{
    /**
     * Start date for event customer tracking.
     */
    protected function getStartDate(): Carbon
    {
        return Carbon::create(2025, 6, 17);
    }

    /**
     * Base query for non-admin users registered after the start date.
     */
    public function baseQuery(): Builder
    {
        return User::whereDate('created_at', '>=', $this->getStartDate()->toDateString())
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            });
    }

    /**
     * Apply date range and search filters to query.
     */
    public function filteredQuery(Request $request): Builder
    {
        $query = $this->baseQuery();

        $startDate = $request->input('start_date')
            ?? $request->input('date_from')
            ?? $request->input('from');

        $endDate = $request->input('end_date')
            ?? $request->input('date_to')
            ?? $request->input('to');

        if (!empty($startDate)) {
            $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }

        if (!empty($endDate)) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        $search = $request->input('search.value') ?? $request->input('search');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('fname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('property_budget', 'like', "%{$search}%")
                    ->orWhere('marketing', 'like', "%{$search}%")
                    ->orWhereHas('developers.projects', function ($pq) use ($search) {
                        $pq->where('address', 'like', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    /**
     * Get summary metrics and station/developer meta for initial page load.
     */
    public function getViewData(): array
    {
        $today = Carbon::today();
        $total = $this->baseQuery()->count();
        $userToday = (clone $this->baseQuery())->whereDate('created_at', $today)->count();
        $completedUsers = (clone $this->baseQuery())->has('stationUser', '>=', 5)->count();

        $percentage = $total > 0
            ? number_format(($completedUsers / $total) * 100, 2)
            : 0;

        $averageTimespentByStation = StationUser::select(
            'station_id',
            \DB::raw('AVG(time_spent) as average_timespent')
        )
            ->groupBy('station_id')
            ->get()
            ->keyBy('station_id');

        $stations = Station::pluck('name', 'id');
        $developers = Developer::pluck('name', 'id');

        return [
            'usersCount' => $total,
            'userToday' => $userToday,
            'completedUsers' => $completedUsers,
            'percentage' => $percentage,
            'developers' => $developers->map(fn($name) => ['name' => $name]),
            'stations' => $stations->map(function ($name, $id) use ($averageTimespentByStation) {
                return [
                    'name' => $name,
                    'average_timespent' => number_format(
                        ($averageTimespentByStation->get($id)['average_timespent'] ?? 0) / 60,
                        2
                    ),
                ];
            }),
        ];
    }

    /**
     * Process DataTables server-side AJAX request.
     */
    public function getDataTable(Request $request): array
    {
        $totalRecords = $this->baseQuery()->count();

        $query = $this->filteredQuery($request)
            ->with([
                'stationUser',
                'developers.projects',
                'userGift.gift',
                'roles',
            ]);

        $filteredRecords = $query->count();

        // Ordering
        $sortableColumns = [
            0 => 'id',
            1 => 'fname',
            2 => 'email',
            4 => 'property_budget',
            5 => 'marketing',
            6 => 'created_at',
        ];

        $orderColIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');

        if (isset($sortableColumns[$orderColIndex])) {
            $query->orderBy($sortableColumns[$orderColIndex], $orderDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        if ($length > 0) {
            $query->skip($start)->take($length);
        }

        $users = $query->get();

        $stations = Station::pluck('name', 'id');
        $developers = Developer::pluck('name', 'id');

        $data = [];
        $iteration = $start + 1;

        foreach ($users as $user) {
            $data[] = $this->formatRow($user, $iteration++, $stations, $developers);
        }

        return [
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
    }

    /**
     * Format a single user row for DataTables output.
     */
    protected function formatRow(User $user, int $iteration, $stations, $developers): array
    {
        $locations = $user->developers
            ->map(fn($dev) => optional($dev->projects->first())->address)
            ->filter()
            ->unique()
            ->values();

        $locHtml = $locations->isEmpty()
            ? '-'
            : $locations->map(fn($loc) => '<div>' . e($loc) . '</div>')->implode('');

        $userStationIds = $user->stationUser->pluck('station_id')->all();
        $stationCols = [];

        foreach ($stations as $sId => $sName) {
            $hasStation = in_array($sId, $userStationIds);
            $class = $hasStation ? 'text-success' : 'text-danger';

            if ($sId == 1) {
                $val = $hasStation ? 'Yes' : 'No';
                if ($user->is_early_bird && $user->source_of_channel) {
                    $val .= '<br><small class="text-muted">Channel:' . e($user->source_of_channel) . '</small>';
                }
            } elseif ($sId == 3 && $hasStation) {
                $giftName = e($user->userGift?->gift?->name ?? 'No Gift');
                $giftDate = $user->userGift?->created_at ? $user->userGift->created_at->toDayDateTimeString() : '-';
                $val = $giftName . '<br><small class="text-muted">' . $giftDate . '</small>';
            } else {
                $val = $hasStation ? 'Yes' : 'No';
            }

            $stationCols[] = '<span class="text-sm mb-0 ' . $class . '">' . $val . '</span>';
        }

        $userDevIds = $user->developers->pluck('id')->all();
        $devCols = [];

        foreach ($developers as $dId => $dName) {
            $hasDev = in_array($dId, $userDevIds);
            $class = $hasDev ? 'text-success' : 'text-danger';
            $devCols[] = '<span class="' . $class . '">' . ($hasDev ? 'Yes' : 'No') . '</span>';
        }

        if ($user->isProtectedAdmin()) {
            $actionHtml = '<button class="btn btn-secondary btn-sm btn-protected" disabled title="This admin user is protected and cannot be deleted" data-bs-toggle="tooltip" data-bs-placement="top"><i class="fa fa-lock"></i> Protected</button>';
        } else {
            $actionHtml = '<button class="btn btn-danger btn-sm delete-user-btn" data-user-id="' . $user->id . '" data-user-name="' . e($user->fname) . '">Delete</button>';
        }

        return array_merge([
            '<span class="d-none user-id-holder" data-user-id="' . $user->id . '"></span>' . $iteration,
            e($user->fname),
            e($user->email),
            $locHtml,
            e($user->property_budget ?? '-'),
            e($user->marketing ?? '-'),
            Carbon::parse($user->created_at)->toDayDateTimeString(),
        ], $stationCols, $devCols, [$actionHtml]);
    }

    /**
     * Export filtered users as CSV or Excel download.
     */
    public function export(Request $request)
    {
        $type = strtolower((string) $request->input('type', 'csv'));
        $rawName = trim((string) $request->input('filename')) ?: 'customers_export';
        $baseName = preg_replace('/\.(csv|xlsx|xls)$/i', '', $rawName);

        $query = $this->filteredQuery($request)->orderBy('id', 'desc');

        if ($type === 'excel' || $type === 'xlsx') {
            return Excel::download(new UsersExport($query), "{$baseName}.xlsx");
        }

        return $this->streamCsv($query, "{$baseName}.csv");
    }

    /**
     * Export filtered users as streaming CSV download.
     */
    protected function streamCsv(Builder $query, string $fileName): StreamedResponse
    {
        $stations = Station::pluck('name', 'id');
        $developers = Developer::pluck('name', 'id');

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query, $stations, $developers) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            $headers = [
                'ID',
                'Fullname',
                'Email',
                'Preferred Location',
                'Property Budget',
                'Marketing',
                'Registration Timestamp',
            ];
            foreach ($stations as $name) {
                $headers[] = strtoupper($name);
            }
            foreach ($developers as $name) {
                $headers[] = strtoupper($name);
            }
            fputcsv($handle, $headers);

            $iteration = 1;
            $query->chunk(500, function ($users) use ($handle, $stations, $developers, &$iteration) {
                foreach ($users as $user) {
                    $locations = $user->developers
                        ->map(fn($d) => optional($d->projects->first())->address)
                        ->filter()
                        ->unique()
                        ->implode(', ');

                    $row = [
                        $iteration++,
                        $user->fname,
                        $user->email,
                        $locations ?: '-',
                        $user->property_budget ?? '-',
                        $user->marketing ?? '-',
                        Carbon::parse($user->created_at)->toDayDateTimeString(),
                    ];

                    $userStationIds = $user->stationUser->pluck('station_id')->all();
                    foreach ($stations as $sId => $sName) {
                        $hasStation = in_array($sId, $userStationIds);
                        if ($sId == 1) {
                            $val = $hasStation ? 'Yes' : 'No';
                            if ($user->is_early_bird && $user->source_of_channel) {
                                $val .= " (Channel: {$user->source_of_channel})";
                            }
                        } elseif ($sId == 3 && $hasStation) {
                            $val = $user->userGift?->gift?->name ?? 'No Gift';
                        } else {
                            $val = $hasStation ? 'Yes' : 'No';
                        }
                        $row[] = $val;
                    }

                    $userDevIds = $user->developers->pluck('id')->all();
                    foreach ($developers as $dId => $dName) {
                        $row[] = in_array($dId, $userDevIds) ? 'Yes' : 'No';
                    }

                    fputcsv($handle, $row);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}

