<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgencyController;
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
    return Inertia::render('entreprise/Dashboard/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Contract AI generation route
    Route::post('/api/ai/generate-contract', [\App\Http\Controllers\ContractGenerationController::class, 'generate'])->name('ai.generate-contract');

    // Agencies routes
    Route::prefix('agencies')->name('agencies.')->group(function () {
        Route::get('/', [AgencyController::class, 'index'])->name('index');
        Route::get('/create', [AgencyController::class, 'create'])->name('create');
        Route::post('/', [AgencyController::class, 'store'])->name('store');
        Route::get('/{agency}', [AgencyController::class, 'show'])->name('show');
        Route::get('/{agency}/edit', [AgencyController::class, 'edit'])->name('edit');
        Route::put('/{agency}', [AgencyController::class, 'update'])->name('update');
        Route::delete('/{agency}', [AgencyController::class, 'destroy'])->name('destroy');
        Route::get('/export/csv', [AgencyController::class, 'export'])->name('export');
        Route::get('/statistics', [AgencyController::class, 'getStatistics'])->name('statistics');
        Route::get('/map/agencies', [AgencyController::class, 'getAgenciesForMap'])->name('map');
        Route::post('/bulk/status', [AgencyController::class, 'bulkUpdateStatus'])->name('bulk-status');
        Route::post('/bulk/delete', [AgencyController::class, 'bulkDelete'])->name('bulk-delete');
    });

    // Immobilier routes
    Route::prefix('immobilier')->name('immobilier.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard');
        })->name('index');
        Route::get('/batiments', function () {
            return Inertia::render('entreprise/Dashboard/components/BatimentsPage');
        })->name('batiments');
        Route::get('/logements', function () {
            return Inertia::render('entreprise/Dashboard/components/LogementsPage');
        })->name('logements');
        Route::get('/contrats', function () {
            return Inertia::render('entreprise/Dashboard/components/ContratsBailPage');
        })->name('contrats');
        Route::get('/locataires', function () {
            return Inertia::render('entreprise/Dashboard/components/LocatairesPage');
        })->name('locataires');
    });
});

require __DIR__.'/auth.php';
