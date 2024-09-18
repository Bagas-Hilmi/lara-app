<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserTaskController;

// Middleware untuk 'super-admin'
Route::middleware(['role:super-admin'])->group(function () {
    Route::resource('tasks', UserTaskController::class);
    
    Route::resource('users', UserTaskController::class)->only([
        'index',
        'create',
        'store',
        'show',
        'edit',
        'update',
        'destroy'
    ]);
});

// Middleware untuk 'admin'
Route::middleware(['role:admin'])->group(function () {
    Route::resource('tasks', UserTaskController::class)->except(['destroy']);
    Route::resource('users', UserTaskController::class)->except(['destroy']);
});

// Middleware untuk 'manager'
Route::middleware(['role:manager'])->group(function () {
    Route::resource('tasks', UserTaskController::class)->except(['destroy']);
    Route::resource('users', UserTaskController::class)->except(['destroy']);
});

// Middleware untuk 'superintendent'
Route::middleware(['role:superintendent'])->group(function () {
    Route::resource('tasks', UserTaskController::class)->except(['destroy']);
});

// Middleware untuk 'senior-supervisor'
Route::middleware(['role:senior-supervisor'])->group(function () {
    Route::resource('tasks', UserTaskController::class)->except(['destroy']);
});

// Middleware untuk 'supervisor'
Route::middleware(['role:supervisor'])->group(function () {
    Route::resource('tasks', UserTaskController::class)->only(['index', 'show', 'edit', 'update']);
});

// Middleware untuk 'senior-staff'
Route::middleware(['role:senior-staff'])->group(function () {
    Route::resource('tasks', UserTaskController::class)->except(['destroy']);
});

// Middleware untuk 'staff'
Route::middleware(['role:staff'])->group(function () {
    Route::resource('tasks', UserTaskController::class)->only(['index', 'show', 'edit', 'update']);
});

// Middleware untuk 'viewer'
Route::middleware(['role:viewer'])->group(function () {
    Route::resource('tasks', UserTaskController::class)->only(['index', 'show']);
});
