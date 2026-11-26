<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class UsersExport extends StringValueBinder implements FromQuery, WithCustomValueBinder, WithHeadings, WithMapping
{
    public function __construct(
        private Builder $users,
        private Collection $stations,
        private bool $hasStationUsers
    ) {
    }

    public function query(): Builder
    {
        return clone $this->users;
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'Email Address',
            'Registration Timestamp',
            ...$this->stations->map(fn ($name) => strtoupper($name))->values()->all(),
        ];
    }

    public function map($user): array
    {
        $completed = $this->hasStationUsers ? $user->stationUser->pluck('station_id')->all() : [];

        return [
            trim(implode(' ', [$user->title, $user->fname, $user->lname])),
            $user->email,
            $user->created_at?->toDateTimeString(),
            ...$this->stations->keys()->map(fn ($id) => in_array($id, $completed) ? 'Yes' : 'No')->all(),
        ];
    }
}
