<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::post('ai/chat', [\App\Http\Controllers\Api\AiAssistantController::class, 'chat'])->name('ai.chat');
    Route::post('ai/chat/stream', [\App\Http\Controllers\Api\AiAssistantController::class, 'chatStream'])->name('ai.chat.stream');
    Route::get('ai/analytics', [\App\Http\Controllers\Api\AiAssistantController::class, 'analytics'])->name('ai.analytics');
});

Route::middleware('auth:tenant')->prefix('api/tenant')->name('api.tenant.')->group(function () {
    Route::post('ai/chat', [\App\Http\Controllers\Api\AiAssistantController::class, 'tenantChat'])->name('ai.chat');
    Route::post('ai/chat/stream', [\App\Http\Controllers\Api\AiAssistantController::class, 'tenantChatStream'])->name('ai.chat.stream');
});
