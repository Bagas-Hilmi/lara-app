<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CipCumBalController;
use App\Http\Controllers\FaglbController;
use App\Http\Controllers\CapexController;
use App\Http\Controllers\ReportController;
// Default route
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::resource('cipcumbal', CipCumBalController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('faglb', FaglbController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('capex', CapexController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('report', ReportController::class);
});


// Include template routes
require base_path('routes/web_template.php');

