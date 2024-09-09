<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;



// Middleware untuk 'super-admin'
Route::middleware(['role:super-admin'])->group(function () {
    // Rute untuk 'super-admin' yang memiliki akses penuh (CRUD pada task dan user)
    Route::resource('tasks', TaskController::class);
    Route::resource('users', UserController::class);
});

// Middleware untuk 'admin'
Route::middleware(['role:admin'])->group(function () {
    // Rute untuk 'admin' yang memiliki akses pada task dan user
    Route::resource('tasks', TaskController::class)->except(['destroy']); // Tidak bisa menghapus (no 'd')
    Route::resource('users', UserController::class)->except(['destroy']); // Tidak bisa menghapus (no 'd')
});

// Middleware untuk 'manager'
Route::middleware(['role:manager'])->group(function () {
    // Rute untuk 'manager' yang memiliki akses serupa dengan 'admin'
    Route::resource('tasks', TaskController::class)->except(['destroy']); // Tidak bisa menghapus (no 'd')
    Route::resource('users', UserController::class)->except(['destroy']); // Tidak bisa menghapus (no 'd')
});

// Middleware untuk 'superintendent'
Route::middleware(['role:superintendent'])->group(function () {
    // Rute untuk 'superintendent' yang memiliki akses terbatas (CRUD dan 'approve' serta 'acknowledge' untuk task)
    Route::resource('tasks', TaskController::class)->except(['destroy']); // Tidak bisa menghapus (no 'd')
});

// Middleware untuk 'senior-supervisor'
Route::middleware(['role:senior-supervisor'])->group(function () {
    // Rute untuk 'senior-supervisor' yang mirip dengan 'superintendent'
    Route::resource('tasks', TaskController::class)->except(['destroy']); // Tidak bisa menghapus (no 'd')
});

// Middleware untuk 'supervisor'
Route::middleware(['role:supervisor'])->group(function () {
    // Rute untuk 'supervisor' yang tidak memiliki akses untuk 'approve' dan 'acknowledge'
    Route::resource('tasks', TaskController::class)->except(['destroy', 'update']); // Tidak bisa menghapus atau update (no 'd', no 'u')
});

// Middleware untuk 'senior-staff'
Route::middleware(['role:senior-staff'])->group(function () {
    // Rute untuk 'senior-staff' yang memiliki akses terbatas (CRUD tanpa delete pada task)
    Route::resource('tasks', TaskController::class)->except(['destroy']); // Tidak bisa menghapus (no 'd')
});

// Middleware untuk 'staff'
Route::middleware(['role:staff'])->group(function () {
    // Rute untuk 'staff' yang hanya memiliki akses read dan update
    Route::resource('tasks', TaskController::class)->only(['index', 'show', 'edit', 'update']); // Hanya bisa melihat dan mengedit (c,r,u)
});

// Middleware untuk 'viewer'
Route::middleware(['role:viewer'])->group(function () {
    // Rute untuk 'viewer' yang hanya memiliki akses untuk membaca
    Route::resource('tasks', TaskController::class)->only(['index', 'show']); // Hanya bisa melihat (r)
});
