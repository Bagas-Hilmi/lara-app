<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CipCumBalController;
use App\Http\Controllers\FaglbController;
use App\Http\Controllers\CapexController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ApproveController;
use App\Http\Controllers\UnauthorizedController;
use App\Mail\MyTestMail;
use Laratrust\LaratrustFacade as Laratrust; // Jika menggunakan Laratrust
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\UserController;

// Default route
Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('user-management', UserController::class);
    Route::resource('cipcumbal', CipCumBalController::class);
    Route::resource('faglb', FaglbController::class);
    Route::resource('capex', CapexController::class);
});

Route::middleware(['auth', 'role:user|admin|engineer'])->group(function () {
    Route::resource('report', ReportController::class);
});

Route::middleware(['auth', 'role:user|admin|engineer'])->group(function () {
    Route::resource('approve', ApproveController::class);
});


Route::get('/unauthorized', [UnauthorizedController::class, 'index'])
    ->name('unauthorized');

Route::get('send-mail', function () {

    $details = [

        'title' => 'Mail from ItSolutionStuff.com',

        'body' => 'This is for testing email using smtp'

    ];

    Mail::to('your_receiver_email@gmail.com')->send(new \App\Mail\MyTestMail($details));

    dd("Email is Sent.");
});

route::get('/send-welcome-mail', function() {
    Mail::to('abc@gmail.com')->send(new MyTestMail);

});


// Include template routes
require base_path('routes/web_template.php');
