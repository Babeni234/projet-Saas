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
    $user = auth()->user();
    if ($user && $user->employee && $user->employee->agency_id !== null) {
        return redirect()->route('agence.dashboard');
    }
    return Inertia::render('entreprise/Dashboard/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('agence')->name('agence.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Agence\AgencyDashboardController::class, 'index'])->name('dashboard');
    Route::get('/employees/data', [\App\Http\Controllers\Agence\AgencyEmployeeController::class, 'index'])->name('employees.data');
    Route::post('/employees/store', [\App\Http\Controllers\Agence\AgencyEmployeeController::class, 'store'])->name('employees.store');
    
    // Scoped sub-navigation redirects for Espace Agence
    Route::get('/immobilier', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier']);
    })->name('immobilier.index');
    Route::get('/immobilier/batiments', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier/batiments']);
    })->name('immobilier.batiments');
    Route::get('/immobilier/logements', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier/logements']);
    })->name('immobilier.logements');
    Route::get('/immobilier/affectations', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier/affectations']);
    })->name('immobilier.affectations');
    Route::get('/immobilier/contrats', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier/contrats']);
    })->name('immobilier.contrats');
    Route::get('/immobilier/locataires', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier/locataires']);
    })->name('immobilier.locataires');
    Route::get('/immobilier/factures', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier/factures']);
    })->name('immobilier.factures');
    Route::get('/immobilier/paiements', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier/paiements']);
    })->name('immobilier.paiements');
    Route::get('/immobilier/renouvellements', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier/renouvellements']);
    })->name('immobilier.renouvellements');
    Route::get('/immobilier/engagements', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier/engagements']);
    })->name('immobilier.engagements');
    Route::get('/immobilier/etats-des-lieux', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier/etats-des-lieux']);
    })->name('immobilier.etats-des-lieux');
    Route::get('/immobilier/historique', function () {
        return redirect()->route('agence.dashboard', ['route' => 'immobilier/historique']);
    })->name('immobilier.historique');
    Route::get('/immobilier/illustrations', [\App\Http\Controllers\IllustrationController::class, 'index'])->name('immobilier.illustrations');
    Route::get('/comptabilite', function () {
        return redirect()->route('agence.dashboard', ['route' => 'comptabilite']);
    })->name('accounting');
    Route::get('/comptabilite/factures', function () {
        return redirect()->route('agence.dashboard', ['route' => 'comptabilite/factures']);
    })->name('accounting.factures');
    Route::get('/comptabilite/facture-creation', function () {
        return redirect()->route('agence.dashboard', ['route' => 'comptabilite/facture-creation']);
    })->name('accounting.facture-creation');
    Route::get('/maintenance', function () {
        return redirect()->route('agence.dashboard', ['route' => 'maintenance']);
    })->name('maintenance');
    Route::get('/rapports', function () {
        return redirect()->route('agence.dashboard', ['route' => 'rapports']);
    })->name('reports');
    Route::get('/employees', function () {
        return redirect()->route('agence.dashboard', ['route' => 'employees']);
    })->name('employees');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return Inertia::render('entreprise/Dashboard/Dashboard', [
            'initialRoute' => 'company/profile'
        ]);
    })->name('profile.edit');
    Route::post('/company/profile', [\App\Http\Controllers\CompanyProfileController::class, 'update'])->name('company.profile.update');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Contract AI generation routes
    Route::post('/api/ai/generate-contract', [\App\Http\Controllers\ContractGenerationController::class, 'generate'])->name('ai.generate-contract');
    Route::post('/api/ai/generate-engagement', [\App\Http\Controllers\ContractGenerationController::class, 'generateEngagement'])->name('ai.generate-engagement');
    Route::post('/api/ai/assistant', [\App\Http\Controllers\ContractGenerationController::class, 'assistantChat'])->name('ai.assistant');

    // Agencies routes
    Route::prefix('agencies')->name('agencies.')->group(function () {
        Route::get('/', [AgencyController::class, 'index'])->name('index');
        Route::get('/create', [AgencyController::class, 'create'])->name('create');
        Route::post('/', [AgencyController::class, 'store'])->name('store');
        Route::get('/export/csv', [AgencyController::class, 'export'])->name('export');
        Route::get('/statistics', [AgencyController::class, 'getStatistics'])->name('statistics');
        Route::get('/map/agencies', [AgencyController::class, 'getAgenciesForMap'])->name('map');
        Route::post('/bulk/status', [AgencyController::class, 'bulkUpdateStatus'])->name('bulk-status');
        Route::post('/bulk/delete', [AgencyController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/{agency}', [AgencyController::class, 'show'])->name('show');
        Route::get('/{agency}/edit', [AgencyController::class, 'edit'])->name('edit');
        Route::put('/{agency}', [AgencyController::class, 'update'])->name('update');
        Route::delete('/{agency}', [AgencyController::class, 'destroy'])->name('destroy');
    });

    // Immobilier routes
    Route::prefix('immobilier')->name('immobilier.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier'
            ]);
        })->name('index');
        Route::get('/batiments', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier/batiments'
            ]);
        })->name('batiments');
        Route::get('/logements', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier/logements'
            ]);
        })->name('logements');
        Route::get('/affectations', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier/affectations'
            ]);
        })->name('affectations');
        Route::get('/contrats', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier/contrats'
            ]);
        })->name('contrats');
        Route::get('/locataires', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier/locataires'
            ]);
        })->name('locataires');
        Route::get('/factures', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier/factures'
            ]);
        })->name('factures');
        Route::get('/paiements', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier/paiements'
            ]);
        })->name('paiements');
        Route::get('/renouvellements', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier/renouvellements'
            ]);
        })->name('renouvellements');
        Route::get('/engagements', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier/engagements'
            ]);
        })->name('engagements');
        Route::get('/etats-des-lieux', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier/etats-des-lieux'
            ]);
        })->name('etats-des-lieux');
        Route::get('/historique', function () {
            return Inertia::render('entreprise/Dashboard/Dashboard', [
                'initialRoute' => 'immobilier/historique'
            ]);
        })->name('historique');
        Route::get('/illustrations', [\App\Http\Controllers\IllustrationController::class, 'index'])->name('illustrations');
    });

    // Roles and Permissions routes
    Route::prefix('dashboard/roles')->name('roles.')->group(function () {
        Route::get('/', [\App\Http\Controllers\RoleController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\RoleController::class, 'store'])->name('store');
        Route::put('/{role}', [\App\Http\Controllers\RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [\App\Http\Controllers\RoleController::class, 'destroy'])->name('destroy');
        Route::post('/employees', [\App\Http\Controllers\RoleController::class, 'storeEmployee'])->name('store-employee');
    });
    Route::post('dashboard/users/{user}/role', [\App\Http\Controllers\RoleController::class, 'updateUserRole'])->name('users.update-role');
    Route::post('dashboard/users/{user}/status', [\App\Http\Controllers\RoleController::class, 'updateUserStatus'])->name('users.update-status');

    // Illustrations API routes
    Route::post('/api/illustrations', [\App\Http\Controllers\IllustrationController::class, 'store'])->name('illustrations.store');
    Route::put('/api/illustrations/{illustration}', [\App\Http\Controllers\IllustrationController::class, 'update'])->name('illustrations.update');
    Route::delete('/api/illustrations/{illustration}', [\App\Http\Controllers\IllustrationController::class, 'destroy'])->name('illustrations.destroy');
});

require __DIR__.'/auth.php';
