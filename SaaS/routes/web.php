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
    $user = auth()->user();

    $totalProperties = \App\Models\Property::where('user_id', $user->id)->count();
    $totalTenants = \App\Models\Tenant::whereHas('contracts.property', fn($q) => $q->where('user_id', $user->id))->count();
    $activeContracts = \App\Models\Contract::whereHas('property', fn($q) => $q->where('user_id', $user->id))
        ->where('status', 'active')
        ->count();
    $monthlyRevenue = \App\Models\Receipt::whereHas('contract.property', fn($q) => $q->where('user_id', $user->id))
        ->where('status', 'paid')
        ->whereMonth('created_at', now()->month)
        ->sum('total');

    return Inertia::render('Dashboard', [
        'stats' => [
            'total_properties' => $totalProperties,
            'total_tenants' => $totalTenants,
            'active_contracts' => $activeContracts,
            'monthly_revenue' => $monthlyRevenue,
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
        Route::get('messages', [\App\Http\Controllers\Landlord\MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/create', [\App\Http\Controllers\Landlord\MessageController::class, 'create'])->name('messages.create');
        Route::post('messages', [\App\Http\Controllers\Landlord\MessageController::class, 'store'])->name('messages.store');
        Route::get('messages/{conversation}', [\App\Http\Controllers\Landlord\MessageController::class, 'show'])->name('messages.show');
        Route::post('messages/{conversation}/reply', [\App\Http\Controllers\Landlord\MessageController::class, 'reply'])->name('messages.reply');
    });
});

require __DIR__.'/auth.php';
