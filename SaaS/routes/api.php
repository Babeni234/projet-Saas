<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::post('ai/chat', [\App\Http\Controllers\Api\AiAssistantController::class, 'chat'])->name('ai.chat');
});

Route::middleware('auth:tenant')->prefix('api/tenant')->name('api.tenant.')->group(function () {
    Route::post('ai/chat', [\App\Http\Controllers\Api\AiAssistantController::class, 'tenantChat'])->name('ai.chat');
});
