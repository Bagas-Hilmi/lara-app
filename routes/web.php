<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CipCumBalController;



// Default route
Route::get('/', function () {
    return view('welcome');
});
Route::get('/employees', [EmployeeController::class, 'index'])->name('pages.tables');

Route::middleware('auth')->group(function () {
    Route::resource('cipcumbal', CipCumBalController::class);
});


// Include template routes
require base_path('routes/web_template.php');
