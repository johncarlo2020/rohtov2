<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportEmbarkAll()
    {
        $tasks = Task::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="embark-users-all.csv"',
        ];

        $callback = function () use ($tasks) {
            $file = fopen('php://output', 'w');

            // Add column headers
            $columns = ['ID', 'Name', 'Phone Number', 'Email'];
            foreach ($tasks as $task) {
                if ($task->id == 4) {
                    $columns[] = 'Pledge (Status)';
                    $columns[] = 'Pledge (Date)';
                } else {
                    $columns[] = $task->name . ' (Status/Image)';
                    $columns[] = $task->name . ' (Date)';
                }
            }
            fputcsv($file, $columns);

            // Add data for all users with tasks
            User::with('tasks')->whereHas('tasks')->chunk(200, function ($users) use ($file, $tasks) {
                // Replicate the data transformation from EmbarkController to ensure consistency
                $users->each(function ($user) use ($tasks) {
                    $userTasks = $user->tasks->keyBy('id');
                    $user->all_tasks = $tasks->map(function ($task) use ($userTasks) {
                        $clonedTask = clone $task;
                        $userTaskPivot = $userTasks->get($task->id);
                        $clonedTask->status = optional($userTaskPivot)->pivot->status ?? 'pending';
                        $clonedTask->image = optional($userTaskPivot)->pivot->images ?? '';
                        $clonedTask->submission_date = optional($userTaskPivot)->pivot->updated_at ?? null;
                        return $clonedTask;
                    });
                });

                foreach ($users as $user) {
                    $row = [
                        $user->id,
                        $user->fname . ' ' . $user->lname,
                        $user->number,
                        $user->email,
                    ];

                    foreach ($tasks as $task) {
                        $userTask = $user->all_tasks->firstWhere('id', $task->id);

                        if ($task->id == 4) { // Pledge task
                            $pledgeStatus = (strtolower($userTask->image) === 'yes') ? 'Yes' : 'No';
                            $row[] = $pledgeStatus;
                            $row[] = $userTask->submission_date ? \Carbon\Carbon::parse($userTask->submission_date)->format('Y-m-d H:i:s') : 'N/A';
                        } else {
                            if ($userTask->status === 'completed' && $userTask->image) {
                                $row[] = url('storage/uploads/' . $userTask->image);
                            } else {
                                $row[] = $userTask->status;
                            }
                            $row[] = $userTask->submission_date ? \Carbon\Carbon::parse($userTask->submission_date)->format('Y-m-d H:i:s') : 'N/A';
                        }
                    }
                    fputcsv($file, $row);
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
