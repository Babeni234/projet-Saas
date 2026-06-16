<?php

use App\Http\Controllers\Api\PushSubscriptionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Push subscriptions
    Route::post('/push-subscriptions', [PushSubscriptionController::class, 'store']);
    Route::delete('/push-subscriptions', [PushSubscriptionController::class, 'destroy']);

    // AI Agent routes (under /api prefix for session-based auth)
    Route::prefix('api')->group(function () {
        Route::post('/agent/message', [\App\Http\Controllers\Api\AiAgentController::class, 'message']);
        Route::get('/agent/history', [\App\Http\Controllers\Api\AiAgentController::class, 'history']);
        Route::delete('/agent/clear', [\App\Http\Controllers\Api\AiAgentController::class, 'clear']);
        Route::get('/agent/notifications', [\App\Http\Controllers\Api\AiAgentController::class, 'notifications']);
        Route::get('/agent/state', [\App\Http\Controllers\Api\AiAgentController::class, 'state']);
    });
});

// Route simple pour afficher le dashboard du locataire
Route::get('/dashboard-locataire', function () {
    return Inertia::render('Locataire/dashboard-loc'); // Ne pas mettre l'extension .vue
})->name('dashboard.locataire')->middleware(['auth']); 
// Le middleware 'auth' sécurise la route pour que seuls les locataires connectés y accèdent

require __DIR__.'/auth.php';

