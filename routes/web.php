<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\IpadController;
use Illuminate\Http\Request;

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

Route::get('/', function (Request $request) {
    if ($request->has('utm_source')) {
            session(['utm.source' => $request->get('utm_source')]);
        }

        if ($request->has('utm_medium')) {
            session(['utm.medium' => $request->get('utm_medium')]);
        }

    return view('welcome');
})->name('welcome');


// add the apointment blade

Route::get('/ipad', [IpadController::class, 'index'])->name('ipad.index');

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

Route::get('/congrats', function () {
    return view('congrats');
})->name('congrats');

Route::get('/voteyourfav', function () {
    return view('welcomeVote');
})->name('welcomeVote');
Route::get('/vote', 'App\Http\Controllers\StationController@vote')->name('vote');
Route::post('/castVote', 'App\Http\Controllers\StationController@castVote')->name('castVote');
Route::get('/voteData', 'App\Http\Controllers\StationController@voteData')->name('voteData');
Route::get('/congratsVote', 'App\Http\Controllers\StationController@congratsVote')->name('congratsVote');
Route::post('/process_qr_code', 'App\Http\Controllers\StationController@scan')->name('process_qr_code');

Route::group(['middleware' => ['admin']], function () {
    Route::get('/admin', 'App\Http\Controllers\StationController@admin')->name('admin');
    // Route::get('/admin/users', 'App\Http\Controllers\StationController@users')->name('users');
    Route::get('/admin/ambient', 'App\Http\Controllers\StationController@ambient')->name('ambient');
    Route::get('/admin/embark', 'App\Http\Controllers\StationController@embark')->name('embark');
    Route::post('/tasks/complete', 'App\Http\Controllers\StationController@tasksComplete')->name('tasks.complete');
    Route::get('/admin/scanner', 'App\Http\Controllers\StationController@scanner')->name('scanner');

    Route::get('/admin/{user}', 'App\Http\Controllers\StationController@userData')->name('userData');
    Route::post('/admin/check', 'App\Http\Controllers\StationController@check')->name('check');
    Route::post('/editUser', 'App\Http\Controllers\StationController@editUser')->name('editUser');
    Route::post('/tasks/redeem', 'App\Http\Controllers\StationController@redeem')->name('tasks.redeem');
    Route::post('/verify-otp-admin', 'App\Http\Controllers\StationController@verifyAdmin')->name('verifyAdmin');
    Route::get('/admin/users/{date}/{keyword?}', 'App\Http\Controllers\StationController@usersFilter')->name('userFilter');



    Route::get('/dumpUser', 'App\Http\Controllers\StationController@logUser')->name('logUser');
});

Route::group(['middleware' => ['client']], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
     Route::get('/map', 'App\Http\Controllers\StationController@map')->name('map');
    Route::get('/station/{station}', 'App\Http\Controllers\StationController@index')->name('station');
    Route::get('/dashboard', 'App\Http\Controllers\StationController@welcome')->name('dashboard');
    Route::get('/station/{station}/extension', 'App\Http\Controllers\StationController@extension')->name('station.extension');
    Route::get('/station/{station}/brand', 'App\Http\Controllers\StationController@brand')->name('station.brand');
    Route::get('/puzzle', 'App\Http\Controllers\StationController@puzzle')->name('station.puzzle');
    Route::get('/brands', 'App\Http\Controllers\StationController@brands')->name('station.brands');
    Route::post('/saveStaff', 'App\Http\Controllers\StationController@saveStaff')->name('saveStaff');
    Route::post('/save-product', 'App\Http\Controllers\StationController@saveProduct')->name('saveProduct');
    Route::post('/submit-pledge', 'App\Http\Controllers\StationController@submitPledge')->name('pledge.submit');



    Route::post('/upload', 'App\Http\Controllers\StationController@uploadBaby')->name('upload.baby');

    Route::get('/otp', function () {
    return view('otp');
    })->name('otp');

    Route::get('/resend-otp', 'App\Http\Controllers\StationController@resend')->name('resend.otp');
    Route::post('/verify-otp', 'App\Http\Controllers\StationController@verify')->name('verify.otp');
    Route::get('/appointment', 'App\Http\Controllers\StationController@appointment')->name('appointment');
    Route::post('/appointment/submit', 'App\Http\Controllers\StationController@appointmentSubmit')->name('appointments.submit');
    Route::get('/pre-reg-event', 'App\Http\Controllers\StationController@preRegEvent')->name('preRegEvent');
    Route::get('/pre-reg-event/guestAndWin', 'App\Http\Controllers\StationController@guestAndWin')->name('guestAndWin');
    Route::get('/pre-reg-event/embarkJourney', 'App\Http\Controllers\StationController@embarckJourney')->name('embarckJourney');
    Route::get('/pre-reg-event/embarkJourney/station/{station}', 'App\Http\Controllers\StationController@embarckStation')->name('embarckStation');

    Route::post('/guess/submit', 'App\Http\Controllers\StationController@guessSubmit')->name('guess.submit');
    Route::post('/upload-image', 'App\Http\Controllers\StationController@uploadImage')->name('upload.image');
    Route::post('/receipt', 'App\Http\Controllers\StationController@receipt')->name('receipt');
    Route::post('/consent/submit', 'App\Http\Controllers\StationController@consent')->name('consent.submit');


});

require __DIR__ . '/auth.php';
