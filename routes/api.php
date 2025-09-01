<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');
    
    // User info (optional - you can keep or remove)
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user.profile');

    // Task routes
    Route::apiResource('tasks', TaskController::class)->names([
        'index' => 'tasks.index',
        'store' => 'tasks.store',
        'show' => 'tasks.show',
        'update' => 'tasks.update',
        'destroy' => 'tasks.destroy'
    ]);

    // Additional task endpoints
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('/tasks/{task}/pending', [TaskController::class, 'pending'])->name('tasks.pending');
});

// Fallback route for undefined endpoints
Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint not found. Please check the API documentation.',
        'status' => 404
    ], 404);
});