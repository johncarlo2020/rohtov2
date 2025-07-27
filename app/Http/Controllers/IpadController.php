<?php

namespace App\Http\Controllers;
use App\Models\Donation;

use Illuminate\Http\Request;

class IpadController extends Controller
{
    public function index()
    {
        return view('ipad');
    }

    public function index2()
    {
        return view('ipad.ipad2');
    }

    public function store(Request $request)
    {
        $rawWeight = $request->input('weight'); // e.g., "200G" or "4KG"

        // Separate number and unit
        preg_match('/^(\d+)(G|KG)$/i', strtoupper($rawWeight), $matches);

        if (!$matches || count($matches) < 3) {
            return back()->with('error', 'Invalid weight format');
        }

        $value = (int) $matches[1];
        $unit = $matches[2]; // "G" or "KG"

        // Save to DB
        Donation::create([
            'value' => $value,
            'unit' => $unit,
        ]);

    
        return back();
    }

    public function donationCount()
    {

        // Calculate total in grams
        $totalGrams = Donation::all()->sum(function ($w) {
            return strtoupper($w->unit) === 'KG'
                ? $w->value * 1000
                : $w->value;
        });

        // Optional: Format total as comma-separated
        $totalDonations = ($totalGrams);

        return response()->json(['count' => $totalDonations]);
    }

}
