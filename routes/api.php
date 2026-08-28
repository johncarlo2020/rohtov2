<?php

use App\Http\Controllers\Api\GiftController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\BookingApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/gifts/stocks', [GiftController::class, 'stocks']);
Route::get('/gifts/{id}/stock', [GiftController::class, 'stock']);

// Booking System API Endpoints
Route::get('/booking/dates', [BookingApiController::class, 'getDates']);
Route::get('/booking/dates/{date}/slots', [BookingApiController::class, 'getSlots']);
Route::post('/bookings', [BookingApiController::class, 'store']);
Route::post('/bookings/{id}/cancel', [BookingApiController::class, 'cancel']);
Route::post('/bookings/{id}/modify', [BookingApiController::class, 'modify']);