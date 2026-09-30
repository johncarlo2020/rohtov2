<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    public function create(): RedirectResponse
    {
        $code = (string) Str::ulid();
        $user = User::create([
            'code' => $code,
            'password' => Hash::make($code),
        ]);
        $clientRole = Role::firstOrCreate([
            'name' => 'client',
            'guard_name' => 'web',
        ]);
        $user->assignRole($clientRole);

        Auth::login($user);

        return redirect()->route('landing');
    }
}
