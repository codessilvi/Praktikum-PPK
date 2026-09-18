<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\TaskListController;

// Halaman awal
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Task & Manajemen Tugas
    Route::get('/tasks', [TaskController::class, 'index'])
        ->name('tasks.index');

    Route::get('/tasks/create', [TaskController::class, 'create'])
        ->name('tasks.create');

    Route::post('/tasks', [TaskController::class, 'store'])
        ->name('tasks.store');

    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])
        ->name('tasks.edit');

    Route::put('/tasks/{task}', [TaskController::class, 'update'])
        ->name('tasks.update');

    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
        ->name('tasks.destroy');

    // Status & Progress (SRS-006)
    Route::patch('/tasks/{id}/toggle-status', [TaskController::class, 'toggleStatus'])
        ->name('tasks.toggleStatus');

    // Kolaborasi (SRS-005)
    Route::post('/tasks/{taskId}/collaborators', [CollaboratorController::class, 'store'])
        ->name('collaborators.store');

    Route::delete('/collaborators/{id}', [CollaboratorController::class, 'destroy'])
        ->name('collaborators.destroy');

    // Task List - Hapus Task List (Atomic Transaction)
    Route::delete('/task-lists/{taskList}', [TaskListController::class, 'destroy'])
        ->name('task-lists.destroy');
});