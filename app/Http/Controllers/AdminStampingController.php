<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\StationUser;
use App\Models\User;
use Illuminate\Http\Request;

class AdminStampingController extends Controller
{
    public function index()
    {
        $stamps = StationUser::with(['user:id,name,code', 'station:id,name'])
            ->latest()
            ->paginate(25);
        $users = User::select('id', 'name', 'code')->orderBy('name')->get();
        $stations = Station::orderBy('id')->get(['id', 'name']);

        return view('admin.stamping.index', compact('stamps', 'users', 'stations'));
    }

    public function store(Request $request)
    {
        StationUser::create($this->validatedData($request));

        return redirect()->route('admin.stamping.index')->with('success', 'Stamp created.');
    }

    public function update(Request $request, StationUser $stamp)
    {
        $stamp->update($this->validatedData($request));

        return redirect()->route('admin.stamping.index')->with('success', 'Stamp updated.');
    }

    public function destroy(StationUser $stamp)
    {
        $stamp->delete();

        return redirect()->route('admin.stamping.index')->with('success', 'Stamp deleted.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'station_id' => ['required', 'integer', 'exists:stations,id'],
            'time_spent' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}