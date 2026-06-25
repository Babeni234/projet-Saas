<?php

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
    return Inertia::render('Dashboard', [
        'stats' => [
            'total_properties' => 0,
            'total_tenants' => 0,
            'active_contracts' => 0,
            'monthly_revenue' => 0,
        ],
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('bailleur')->name('landlord.')->group(function () {
        Route::resource('properties', \App\Http\Controllers\Landlord\PropertyController::class);
        Route::resource('tenants', \App\Http\Controllers\Landlord\TenantController::class);
        Route::resource('contracts', \App\Http\Controllers\Landlord\ContractController::class);
        Route::resource('visits', \App\Http\Controllers\Landlord\VisitController::class);
        Route::resource('receipts', \App\Http\Controllers\Landlord\ReceiptController::class);
    });
});

require __DIR__.'/auth.php';
