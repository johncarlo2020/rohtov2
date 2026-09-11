<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\HistoryLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Store a newly created user (Staff, Admin, or Client).
     * Restricted to Super Admins.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'number' => 'nullable|string|max:50',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string|in:superadmin,admin,staff,client',
        ]);

        $roleName = $validated['role'] ?? 'staff';

        // Ensure role exists in Spatie permissions
        if (!Role::where('name', $roleName)->exists()) {
            Role::create(['name' => $roleName]);
        }

        $fullName = trim($validated['fname'] . ' ' . ($validated['lname'] ?? ''));

        $user = User::create([
            'fname' => $validated['fname'],
            'lname' => $validated['lname'] ?? null,
            'email' => strtolower($validated['email']),
            'number' => $validated['number'] ?? null,
            'password' => Hash::make($validated['password']),
            'otp_verified' => 1,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($roleName);

        // Log action in history logs
        HistoryLogService::log(
            'CREATE_USER',
            "Created new " . strtoupper($roleName) . " user account: {$user->email} ({$fullName})",
            'User',
            $user->id
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'user' => $user,
            ], 201);
        }

        return redirect()->back()->with('success', "User account {$user->email} created successfully!");
    }
}
