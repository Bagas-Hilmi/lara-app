<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
// Include routing roles
require base_path('routes/web_role.php');

// Authentication routes
Auth::routes();

Route::get('sign-up', [RegisterController::class, 'showRegistrationForm'])->middleware('guest')->name('register');
Route::post('sign-up', [RegisterController::class, 'register'])->middleware('guest');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

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
});

Route::get('user-management', function () {
    return view('pages.laravel-examples.user-management');
})->name('user-management');

Route::get('user-profile', function () {
    return view('pages.laravel-examples.user-profile');
})->name('user-profile');
