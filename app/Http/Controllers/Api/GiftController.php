<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gifts;

class GiftController extends Controller
{

    public function stocks()
    {
        $gifts = Gifts::select('id', 'name', 'stock_level')->get();

        return response()->json([
            'status' => 'success',
            'data' => $gifts
        ]);
    }

    public function stock($id)
    {
        $gift = Gifts::select('id', 'name', 'stock_level')->find($id);

        if (!$gift) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gift not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $gift
        ]);
    }
}