<?php

use App\Http\Controllers\Api\AiAgentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

// Auth Routes (Mobile)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Authenticated User Routes
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store']);
    Route::delete('/push-subscriptions', [PushSubscriptionController::class, 'destroy']);

    // AI Agent routes
    Route::post('/agent/message', [AiAgentController::class, 'message']);
    Route::get('/agent/history', [AiAgentController::class, 'history']);
    Route::delete('/agent/clear', [AiAgentController::class, 'clear']);
    Route::get('/agent/notifications', [AiAgentController::class, 'notifications']);
    Route::get('/agent/state', [AiAgentController::class, 'state']);
});
