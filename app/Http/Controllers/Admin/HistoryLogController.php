<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistoryLog;
use Illuminate\Http\Request;

class HistoryLogController extends Controller
{
    /**
     * Display a listing of system history logs.
     * Restricted to Super Admins.
     */
    public function index(Request $request)
    {
        $query = HistoryLog::with('user')->latest();

        // Search filter
        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'LIKE', "%{$search}%")
                  ->orWhere('user_role', 'LIKE', "%{$search}%")
                  ->orWhere('action', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Action filter
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $logs = $query->paginate(25)->withQueryString();

        $actionTypes = HistoryLog::select('action')->distinct()->pluck('action');

        return view('history_logs', compact('logs', 'actionTypes'));
    }
}
