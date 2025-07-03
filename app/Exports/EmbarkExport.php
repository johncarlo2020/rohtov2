<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmbarkExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::with('all_tasks')->where('role', 'user')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            'ID',
            'Name',
            'Phone Number',
            'Email',
        ];

        $user = User::with('all_tasks')->where('role', 'user')->first();
        if ($user) {
            foreach ($user->all_tasks as $task) {
                if ($task->id == 4) {
                    $headings[] = 'Pledge (Status)';
                    $headings[] = 'Pledge (Date)';
                } else {
                    $headings[] = $task->name . ' (Status/Image)';
                    $headings[] = $task->name . ' (Date)';
                }
            }
        }

        $headings[] = 'Redeem Status';

        return $headings;
    }

    /**
     * @param mixed $user
     *
     * @return array
     */
    public function map($user): array
    {
        $data = [
            $user->id,
            $user->fname . ' ' . $user->lname,
            $user->number ?? 'none',
            $user->email,
        ];

        foreach ($user->all_tasks as $task) {
            if ($task->id == 4) {
                $data[] = $task->image;
                $data[] = $task->submission_date ? \Carbon\Carbon::parse($task->submission_date)->format('d M h:i A') : 'N/A';
            } else {
                if (ucfirst($task->status) === 'In-progress' && !empty($task->image)) {
                    $data[] = asset('storage/uploads/' . $task->image);
                } else {
                    $data[] = ucfirst($task->status);
                }
                $data[] = $task->submission_date ? \Carbon\Carbon::parse($task->submission_date)->format('d M h:i A') : 'N/A';
            }
        }

        if ($user->redeem_date == null) {
            if ($user->can_redeem) {
                $data[] = 'Eligible';
            } else {
                $data[] = 'Not Eligible';
            }
        } else {
            $data[] = 'Redeemed';
        }

        return $data;
    }
}
