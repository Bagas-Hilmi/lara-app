<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
// Include routing roles

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
    Route::post('user-profile', [ProfileController::class, 'update']);
});



Route::get('user-profile', function () {
    return view('pages.user-profile');
})->name('user-profile');
