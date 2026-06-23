<?php

use App\Http\Controllers\Api\AiAgentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LocataireController;
use App\Http\Controllers\Api\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::post('/login', [AuthController::class, 'login']);

// Debug route - remove in production
Route::get('/debug/users', function () {
    $users = \App\Models\User::select('id', 'name', 'email', 'account_type', 'is_connected')->get();
    return response()->json($users);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Push subscriptions
    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store']);
    Route::delete('/push-subscriptions', [PushSubscriptionController::class, 'destroy']);

    // AI Agent routes
    Route::post('/agent/message', [AiAgentController::class, 'message']);
    Route::get('/agent/history', [AiAgentController::class, 'history']);
    Route::delete('/agent/clear', [AiAgentController::class, 'clear']);
    Route::get('/agent/notifications', [AiAgentController::class, 'notifications']);
    Route::get('/agent/state', [AiAgentController::class, 'state']);

    // Locataire routes
    Route::get('/locataire/dashboard', [LocataireController::class, 'dashboard']);
    Route::post('/locataire/wallet/create', [LocataireController::class, 'createWallet']);
    Route::post('/locataire/wallet/recharge', [LocataireController::class, 'rechargeWallet']);
    Route::post('/locataire/wallet/pay-rent', [LocataireController::class, 'payRent']);
    Route::post('/locataire/wallet/pay-utility', [LocataireController::class, 'payUtility']);
    Route::post('/locataire/ticket/create', [LocataireController::class, 'createTicket']);
    Route::post('/locataire/wallet/transfer', [LocataireController::class, 'transferFunds']);
    Route::post('/locataire/contract-fee/pay', [LocataireController::class, 'payContractFee']);
});
