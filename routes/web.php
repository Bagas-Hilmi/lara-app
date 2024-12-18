<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CipCumBalController;
use App\Http\Controllers\FaglbController;
use App\Http\Controllers\CapexController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ApproveController;
use App\Http\Controllers\UnauthorizedController;
use Laratrust\LaratrustFacade as Laratrust; // Jika menggunakan Laratrust

// Default route
Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
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


// Include template routes
require base_path('routes/web_template.php');

