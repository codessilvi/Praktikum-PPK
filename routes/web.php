<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\TaskStatusController;

// SRS-005: Kolaborasi
Route::post('/lists/{id}/members', [CollaborationController::class, 'addMember']);
Route::delete('/lists/{id}/members/{userId}', [CollaborationController::class, 'removeMember']);

// SRS-006: Status & Progress
Route::patch('/tasks/{id}/status', [TaskStatusController::class, 'updateStatus']);
Route::get('/lists/{id}/progress', [TaskStatusController::class, 'getProgress']);


