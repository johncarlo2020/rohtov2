<?php

namespace App\Http\Controllers;

use App\Models\Touchpoint;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminTouchpointController extends Controller
{
    public function index()
    {
        $touchpoints = Touchpoint::orderBy('key')->paginate(25);

        return view('admin.touchpoints.index', compact('touchpoints'));
    }

    public function store(Request $request)
    {
        Touchpoint::create($this->validatedData($request));

        return redirect()->route('admin.touchpoints.index')->with('success', 'Touchpoint created.');
    }

    public function update(Request $request, Touchpoint $touchpoint)
    {
        $touchpoint->update($this->validatedData($request, $touchpoint));

        return redirect()->route('admin.touchpoints.index')->with('success', 'Touchpoint updated.');
    }

    public function destroy(Touchpoint $touchpoint)
    {
        $touchpoint->delete();

        return redirect()->route('admin.touchpoints.index')->with('success', 'Touchpoint deleted.');
    }

    private function validatedData(Request $request, ?Touchpoint $touchpoint = null): array
    {
        $uniqueKey = Rule::unique('touchpoints', 'key');
        if ($touchpoint) {
            $uniqueKey->ignore($touchpoint->id);
        }

        return $request->validate([
            'key' => ['required', 'string', 'max:255', $uniqueKey],
            'label' => ['required', 'string', 'max:255'],
            'required_touches' => ['required', 'integer', 'min:1', 'max:255'],
        ]);
    }
}