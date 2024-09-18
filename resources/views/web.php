<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/qr', function () {
    return view('error');
});

Route::get('/admin/login', function () {
    return view('auth.admin-login');
});

Route::get('/congrats', function () {
    return view('congrats');
})->name('congrats');

Route::group(['middleware' => ['admin']], function () {
    Route::get('/admin', 'App\Http\Controllers\StationController@admin')->name('admin');
    Route::get('/admin/users', 'App\Http\Controllers\StationController@users')->name('users');
    Route::get('/admin/{user}', 'App\Http\Controllers\StationController@userData')->name('userData');
    Route::post('/admin/check', 'App\Http\Controllers\StationController@check')->name('check');
});

Route::group(['middleware' => ['client']], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/station/{station}', 'App\Http\Controllers\StationController@index')->name('station');
    Route::get('/dashboard', 'App\Http\Controllers\StationController@welcome')->name('dashboard');
    Route::post('/process_qr_code', 'App\Http\Controllers\StationController@scan')->name('process_qr_code');
    Route::get('/station/{station}/extension', 'App\Http\Controllers\StationController@extension')->name('station.extension');
    Route::get('/station/{station}/brand', 'App\Http\Controllers\StationController@brand')->name('station.brand');
    Route::get('/puzzle', 'App\Http\Controllers\StationController@puzzle')->name('station.puzzle');
    Route::get('/brands', 'App\Http\Controllers\StationController@brands')->name('station.brands');
    Route::get('/vote', 'App\Http\Controllers\StationController@vote')->name('vote');
    Route::post('/castVote', 'App\Http\Controllers\StationController@castVote')->name('castVote');
    Route::get('/voteData', 'App\Http\Controllers\StationController@voteData')->name('voteData');
    Route::get('/congratsVote', 'App\Http\Controllers\StationController@congratsVote')->name('congratsVote');
});

require __DIR__ . '/auth.php';
