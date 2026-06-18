<?php

use App\Http\Controllers\Api\AiAgentController;
use App\Http\Controllers\Api\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store']);
    Route::delete('/push-subscriptions', [PushSubscriptionController::class, 'destroy']);

    // AI Agent routes
    Route::post('/agent/message', [AiAgentController::class, 'message']);
    Route::get('/agent/history', [AiAgentController::class, 'history']);
    Route::delete('/agent/clear', [AiAgentController::class, 'clear']);
    Route::get('/agent/notifications', [AiAgentController::class, 'notifications']);
    Route::get('/agent/state', [AiAgentController::class, 'state']);
});
