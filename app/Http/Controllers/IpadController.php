<?php

namespace App\Http\Controllers;
use App\Models\Donation;
use App\Events\LiveFeedEvent;
use Illuminate\Http\Request;

class IpadController extends Controller
{
    public function index()

    {
        $donationData = $this->donationCount();

        // update mo to sa view eto ung initial data for the view
        return view('ipad', compact('donationData'));
    }

    public function index2()
    {
        return view('ipad.ipad2');
    }

    public function store(Request $request)
    {

        $rawWeight = $request->input('weight'); // Already in grams (e.g. "90060")

        // Validate that it's a positive number
        if (!is_numeric($rawWeight) || (int)$rawWeight <= 0) {
            return back()->with('error', 'Invalid weight value.');
        }

        $value = (int)$rawWeight;
        $unit = 'G'; // Always in grams
        $tenPercentValue = $value * 0.10;

        Donation::create([
            'value' => $value,
            'unit' => $unit,
            'percentage' => $tenPercentValue,
        ]);

        $donationData = $this->donationCount();

        event(new LiveFeedEvent('pledge', $donationData));

        return response()->json([
            'success' => true,
            'message' => 'Donation recorded successfully',
            'data' => $donationData->getData(),
            'redirect' => route('ipad.index')
        ]);

        // $rawWeight = $request->input('weight'); // e.g., "200G" or "4KG"

        // // Separate number and unit
        // preg_match('/^(\d+)(G|KG)$/i', strtoupper($rawWeight), $matches);

        // if (!$matches || count($matches) < 3) {
        //     return back()->with('error', 'Invalid weight format');
        // }

        // $value = (int) $matches[1];
        // $unit = strtoupper($matches[2]); // "G" or "KG"

        // // Convert to grams if it's in KG
        // if ($unit === 'KG') {
        //     $value *= 1000;
        //     $unit = 'G';
        // }

        // // Calculate 10%
        // $tenPercentValue = $value * 0.10;

        // // Save to DB
        // Donation::create([
        //     'value' => $value,
        //     'unit' => $unit,
        //     'percentage' => $tenPercentValue,
        // ]);

        // $donationData = $this->donationCount();

        // event(new LiveFeedEvent('pledge', $donationData));

        // return back()->with('success', true);
    }

    public function donationCount()
    {
        // Total weight in grams (normalize units)
        $totalGrams = Donation::all()->sum(function ($w) {
            return strtoupper($w->unit) === 'KG'
                ? $w->value * 1000
                : $w->value;
        });

        // Get the latest donation's percentage value
        $latestDonation = Donation::latest()->first();
        $percentage = $latestDonation ? $latestDonation->percentage : 0;


        $data = [
            'count' => $totalGrams,
            'percentage' => $percentage,
        ];


        return response()->json($data);
    }

}
