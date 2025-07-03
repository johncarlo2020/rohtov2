<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


use App\Models\User;
use Illuminate\Http\Request;

class EmbarkController extends Controller
{
    public function index()
     {
        $tasks = Task::all();
        $totalUsers = User::count();
        $usersWithTasks = User::whereHas('tasks')->count();
        return view('embark-export', compact('tasks', 'totalUsers', 'usersWithTasks'));
    }

    public function exportPage()
    {
        $users = User::with('all_tasks')->where('role', 'user')->get();
        return view('embark-export', compact('users'));
    }

    public function completeTask(Request $request)
    {
        // Logic to complete task
    }

    public function redeem(Request $request)
    {
        // Logic to redeem
    }

    public function getUsersData(Request $request)
    {
        $tasks = Task::all();

        $query = User::whereHas('tasks')->with(['tasks' => function ($query) {
            $query->withPivot('status', 'images', 'created_at', 'updated_at');
        }]);

        // Count total records
        $totalRecords = $query->count();

        // Handle search
        if ($request->has('search') && !empty($request->input('search.value'))) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('fname', 'like', '%' . $searchValue . '%')
                    ->orWhere('lname', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%')
                    ->orWhere('number', 'like', '%' . $searchValue . '%');
            });
        }

        // Count filtered records
        $totalFiltered = $query->count();

        // Handle sorting
        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');
            $columns = ['id', 'fname', 'number', 'email'];

            if (isset($columns[$orderColumnIndex])) {
                $query->orderBy($columns[$orderColumnIndex], $orderDirection);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // Handle pagination
        if ($request->has('length') && $request->input('length') != -1) {
            $query->skip($request->input('start'))->take($request->input('length'));
        }

        $users = $query->get();

        // Transform data
        $users->each(function ($user) use ($tasks) {
            $userTasks = $user->tasks->keyBy('id');
            $user->all_tasks = $tasks->map(function ($task) use ($userTasks) {
                $clonedTask = clone $task;
                $userTaskPivot = $userTasks->get($task->id);
                $clonedTask->status = $userTaskPivot->pivot->status ?? 'pending';
                $clonedTask->image = $userTaskPivot->pivot->images ?? '';
                $clonedTask->submission_date = $userTaskPivot->pivot->updated_at ?? null;
                return $clonedTask;
            });
            $user->can_redeem = $user->all_tasks->where('status', 'completed')->count() >= 3;
        });

        // Format data for DataTables
        $data = $users->map(function ($user) use ($tasks) {
            $rowData = [
                'id' => $user->id,
                'name' => $user->fname . ' ' . $user->lname,
                'number' => $user->number ?? 'none',
                'email' => $user->email,
            ];

            foreach ($tasks as $task) {
                $userTask = $user->all_tasks->firstWhere('id', $task->id);
                $statusHtml = '';
                if ($userTask) {
                    if (ucfirst($userTask->status) === 'In-progress' && !empty($userTask->image)) {
                        if ($userTask->id == 4) {
                            $statusHtml = '<span class="badge bg-warning">' . e($userTask->image) . '</span>';
                        } else {
                            $imageUrl = asset('storage/uploads/' . $userTask->image);
                            $statusHtml = '<img src="' . $imageUrl . '" alt="Task Image" class="clickable-image" data-task-id="' . $userTask->id . '" data-user-id="' . $user->id . '" data-image="' . $imageUrl . '" style="max-width: 60px; max-height: 60px; cursor: pointer;">';
                        }
                    } else {
                         if ($userTask->id == 4) {
                            if($userTask->status == 'pending'){
                                 $statusHtml = '<span class="badge bg-secondary">' . ucfirst($userTask->status) . '</span>';
                            }else{
                                 $statusHtml = '<span class="badge bg-success">' . ucfirst(e($userTask->image)) . '</span>';
                            }
                        } else {
                            $statusHtml = '<span class="badge bg-secondary">' . ucfirst($userTask->status) . '</span>';
                        }
                    }
                    $rowData['task_status_' . $task->id] = $statusHtml;
                    $rowData['task_date_' . $task->id] = $userTask->submission_date ? \Carbon\Carbon::parse($userTask->submission_date)->format('d M h:i A') : 'N/A';
                } else {
                    $rowData['task_status_' . $task->id] = '<span class="badge bg-secondary">Pending</span>';
                    $rowData['task_date_' . $task->id] = 'N/A';
                }
            }

            $actionHtml = '';
            if ($user->redeem_date == null) {
                if ($user->can_redeem) {
                    $actionHtml = '<button class="btn btn-success btn-sm redeem-btn" data-user-id="' . $user->id . '">Redeem</button>';
                } else {
                    $actionHtml = '<span class="badge bg-secondary">Not Eligible</span>';
                }
            } else {
                $actionHtml = '<span class="badge bg-success">Redeemed</span>';
            }
            $rowData['action'] = $actionHtml;

            return $rowData;
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }
}
