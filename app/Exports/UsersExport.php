<?php

namespace App\Exports;

use App\Models\Developer;
use App\Models\Station;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Builder $query;
    protected $stations;
    protected $developers;
    protected int $rowNumber = 0;

    public function __construct(Builder $query)
    {
        $this->query = $query->with([
            'stationUser',
            'developers.projects',
            'userGift.gift',
        ]);
        $this->stations = Station::pluck('name', 'id');
        $this->developers = Developer::pluck('name', 'id');
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        $headers = [
            'ID',
            'Fullname',
            'Email',
            'Preferred Location',
            'Property Budget',
            'Marketing',
            'Registration Timestamp',
        ];

        foreach ($this->stations as $name) {
            $headers[] = strtoupper($name);
        }

        foreach ($this->developers as $name) {
            $headers[] = strtoupper($name);
        }

        return $headers;
    }

    public function map($user): array
    {
        $this->rowNumber++;

        $locations = $user->developers
            ->map(fn($d) => optional($d->projects->first())->address)
            ->filter()
            ->unique()
            ->implode(', ');

        $row = [
            $this->rowNumber,
            $user->fname,
            $user->email,
            $locations ?: '-',
            $user->property_budget ?? '-',
            $user->marketing ?? '-',
            Carbon::parse($user->created_at)->toDayDateTimeString(),
        ];

        $userStationIds = $user->stationUser->pluck('station_id')->all();
        foreach ($this->stations as $sId => $sName) {
            $hasStation = in_array($sId, $userStationIds);
            if ($sId == 1) {
                $val = $hasStation ? 'Yes' : 'No';
                if ($user->is_early_bird && $user->source_of_channel) {
                    $val .= " (Channel: {$user->source_of_channel})";
                }
            } elseif ($sId == 2 && $hasStation) {
                $val = $user->userGift?->gift?->name ?? 'No Gift';
            } else {
                $val = $hasStation ? 'Yes' : 'No';
            }
            $row[] = $val;
        }

        $userDevIds = $user->developers->pluck('id')->all();
        foreach ($this->developers as $dId => $dName) {
            $row[] = in_array($dId, $userDevIds) ? 'Yes' : 'No';
        }

        return $row;
    }
}

