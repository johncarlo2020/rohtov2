<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\IpadController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GameConfigController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LiveFeedController;

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



Route::get('/game', function () {
    return view('mobile-game.index');
})->name('game.index');

// Game trigger routes
Route::get('/game-trigger', [GameConfigController::class, 'trigger'])->name('game.trigger');
Route::get('/game-config', [GameConfigController::class, 'index'])->name('game.config');
Route::get('/game-reset', [GameConfigController::class, 'resetGame'])->name('game.reset');

// Game config API routes
Route::post('/game-config/save', [GameConfigController::class, 'store'])->name('game.config.store');
Route::get('/game-config/active', [GameConfigController::class, 'getActive'])->name('game.config.active');

// live feed route
Route::get('/live-feed', [LiveFeedController::class, 'index'])->name('live.feed.index');
//get live feed data
Route::get('/live-feed/data', [LiveFeedController::class, 'getData'])->name('live.feed.data');

Route::get('/start', [LiveFeedController::class, 'start'])->name('start');

Route::get('/upload-baby', function () {
    return view('upload-baby');
})->name('upload.baby.form');

Route::post('/uploadBabyIpad', 'App\Http\Controllers\StationController@uploadBabyIpad')->name('upload.babyIpad');

Route::get('/listen-baby', function () {
    return view('listen-baby');
})->name('listen.baby.form');

Route::get('/pad', function () {
    return view('error');
});


Route::get('/admin/login', function () {
    return view('auth.admin-login');
});

Route::get('/ipad', [IpadController::class, 'index'])->name('ipad.index');
Route::get('/ipad-2', [IpadController::class, 'index2'])->name('ipad.index2');

Route::get('/counter-value',[IpadController::class, 'donationCount'])->name('pledge.counter');

Route::get('/ipad-pledge-info',function(){
        return view('ipad.info');
    })->name('ipad.info');

Route::get('/ipad-pledge-info-2',function(){
        return view('ipad.info2');
    })->name('ipad.info2');


Route::get('/ipad-select-message-type',function(){
        return view('ipad.message-type');
    })->name('ipad.message.type');

Route::get('/ipad-select-message-type-duplicate',function(){
        return view('ipad.message-type-duplicate');
    })->name('ipad.message.type.duplicate');

Route::get('/congrats', function () {
    return view('congrats');
})->name('congrats');

Route::get('/pledge-wall',function()
{
     return view('ipad.pledgewall');
});

Route::get('/voteyourfav', function () {
    return view('welcomeVote');
})->name('welcomeVote');
Route::get('/vote', 'App\Http\Controllers\StationController@vote')->name('vote');
Route::post('/castVote', 'App\Http\Controllers\StationController@castVote')->name('castVote');
Route::get('/voteData', 'App\Http\Controllers\StationController@voteData')->name('voteData');
Route::get('/congratsVote', 'App\Http\Controllers\StationController@congratsVote')->name('congratsVote');

//royal canine
Route::get('/join', function () {
    return view('join');
})->name('join.welcome');

Route::get('/avatar-select', function () {
    return view('join.selectAvatar');
})->name('avatar.select');

Route::get('/avatar-register', function () {
    return view('join.registerAvatar');
})->name('avatar.register');

Route::group(['middleware' => ['admin']], function () {
    Route::get('/admin', 'App\Http\Controllers\StationController@admin')->name('admin');
    Route::get('/admin/users', 'App\Http\Controllers\StationController@users')->name('users');
    Route::get('/admin/scanner', 'App\Http\Controllers\StationController@scanner')->name('scanner');
    Route::get('/admin/trigger', [LiveFeedController::class, 'trigger'])->name('trigger');

    // Game trigger live feed route
    Route::post('/trigger-live-feed', [GameConfigController::class, 'triggerLiveFeed'])->name('trigger.live.feed');

    Route::post('verify-otp-admin', 'App\Http\Controllers\StationController@verifyAdmin')->name('verifyAdmin');

    Route::post('/workshop/scan', 'App\Http\Controllers\WorkshopController@scan')->name('workshop.scan');

    Route::post('/admin/logout', 'App\Http\Controllers\LoginController@destroy')->name('admin.logout');

    Route::get('/admin/bookings', [BookingController::class, 'index'])->name('bookings');
    Route::delete('/admin/bookings/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
    Route::get('/admin/{user}', 'App\Http\Controllers\StationController@userData')->name('userData');
    Route::post('/admin/check', 'App\Http\Controllers\StationController@check')->name('check');
    Route::delete('/admin/users/{id}', 'App\Http\Controllers\StationController@userDelete')->name('users.destroy');
    Route::post('/editUser', 'App\Http\Controllers\StationController@editUser')->name('editUser');

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
    Route::get('/workshop/register', 'App\Http\Controllers\WorkshopController@register')->name('workshop.register');
    Route::get('/workshop/check', 'App\Http\Controllers\WorkshopController@check')->name('workshop.check');
    Route::get('/workshop/submit', 'App\Http\Controllers\WorkshopController@submit')->name('workshop.submit');
    Route::get('/workshop/congrats', 'App\Http\Controllers\WorkshopController@congrats')->name('workshop.congrats');
    Route::get('/workshop', 'App\Http\Controllers\WorkshopController@index')->name('workshop');
    Route::get('/pledge-dj', 'App\Http\Controllers\StationController@pledgeDj')->name('pledgeDj');


    Route::get('/promotion', function () {
        return view('promotion');
    })->name('promotion');


    Route::post('/upload', 'App\Http\Controllers\StationController@uploadBaby')->name('upload.baby');

    Route::get('/otp', function () {
        return view('auth.otp');
    })->name('otp');

    Route::get('/resend-otp', 'App\Http\Controllers\StationController@resend')->name('resend.otp');
    Route::post('/verify-otp', 'App\Http\Controllers\StationController@verify')->name('verify.otp');

     Route::get('/register-welcome', function () {
        return view('registerSuccess');
    })->name('register.welcome');

});



require __DIR__ . '/auth.php';
