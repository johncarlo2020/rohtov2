<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create(Request $request): RedirectResponse|Response
    {
        $code = trim((string) $request->query('id'));

        if ($code === '') {
            return response('A mask ID is required.', 400);
        }

        $user = User::where('code', $code)->first();

        if (! $user) {
            $user = User::create([
                'code' => $code,
                'password' => Hash::make($code),
            ]);
            $user->assignRole('client');
        }

        Auth::login($user);

        return redirect()->route('landing');
    }
}
