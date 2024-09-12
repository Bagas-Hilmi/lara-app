<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\LoginController;
// Include routing roles
require base_path('routes/web_role.php');

// Default route
Route::get('/', function () {
    return view('welcome');
});


Route::get('login', [LoginController::class, 'create'])->name('login');
Route::post('login', [LoginController::class, 'store']);
Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
Route::post('verify', [LoginController::class, 'show'])->name('password.email');
Route::post('password/reset', [LoginController::class, 'update'])->name('password.update');

Route::get('/employees', [EmployeeController::class, 'index'])->name('pages.tables');
// Authentication routes
Auth::routes();

Route::get('sign-up', [RegisterController::class, 'showRegistrationForm'])->middleware('guest')->name('register');
Route::post('sign-up', [RegisterController::class, 'register'])->middleware('guest');

Route::get('verify', function () {
    return view('auth.verify');
})->middleware('guest')->name('verify');

Route::get('reset-password/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');


// Authenticated routes
Route::middleware('auth')->group(function () {

    // Dashboard and Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [ProfileController::class, 'create'])->name('profile');
    Route::post('user-profile', [ProfileController::class, 'update']);

    // Static Pages
    Route::get('billing', function () {
        return view('pages.billing');
    })->name('billing');
    
    Route::get('tables', function () {
        return view('pages.tables');
    })->name('tables');
    
    Route::get('notifications', function () {
        return view('pages.notifications');
    })->name('notifications');
    
    Route::get('static-sign-in', function () {
        return view('pages.static-sign-in');
    })->name('static-sign-in');
    
    Route::get('static-sign-up', function () {
        return view('pages.static-sign-up');
    })->name('static-sign-up');
    
    Route::get('user-management', function () {
        return view('pages.laravel-examples.user-management');
    })->name('user-management');
    
    Route::get('user-profile', function () {
        return view('pages.laravel-examples.user-profile');
    })->name('user-profile');
    
});
