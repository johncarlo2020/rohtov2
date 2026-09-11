<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gifts;
use Illuminate\Support\Facades\Schema;

class GiftController extends Controller
{
    public function draw()
    {
        $gifts = Schema::hasTable('gifts')
            ? Gifts::select('id', 'name', 'stock_level')->get()
            : collect();

        return view('draw', [
            'stocks' => $gifts,
        ]);
    }

    public function stocks()
    {
        $gifts = Schema::hasTable('gifts')
            ? Gifts::select('id', 'name', 'stock_level')->get()
            : collect();

        return response()->json([
            'status' => 'success',
            'data' => $gifts
        ]);
    }

    public function stock($id)
    {
        $gift = Schema::hasTable('gifts')
            ? Gifts::select('id', 'name', 'stock_level')->find($id)
            : null;

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