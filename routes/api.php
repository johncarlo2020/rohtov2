<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\StationController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('users/export', [UserController::class, 'export'])->name('users.export');

Route::get('users-datatable', [UserController::class, 'getUsersForDataTable'])
    ->name('users.datatable');

Route::get('dump-details', [StationController::class, 'dumpDetails'])->name('dump.details');


