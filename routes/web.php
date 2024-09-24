<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CipCumBalController;
use App\Http\Controllers\FaglbController;
// Default route
Route::get('/', function () {
    return view('welcome');
});
Route::get('/employees', [EmployeeController::class, 'index'])->name('pages.tables');

Route::middleware('auth')->group(function () {
    Route::resource('cipcumbal', CipCumBalController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('faglb', FaglbController::class);
});


// Include template routes
require base_path('routes/web_template.php');
