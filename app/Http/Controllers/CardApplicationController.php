<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CardApplicationController extends Controller
{
    public function scan(Request $request)
    {
        $data = $request->validate(['qrCodeMessage' => 'required|string|max:2048']);
        $expected = config('card_application.qr_code') ?: route('card-application.booth');

        if (!is_string($expected) || $expected === '' || !hash_equals($expected, trim($data['qrCodeMessage']))) {
            return response()->json(['message' => 'Invalid QR Code'], 422);
        }

        $request->user()->forceFill(['isCardApply' => true])->save();

        return response()->json(['message' => 'Check-in Successful', 'isCardApply' => true]);
    }
}
