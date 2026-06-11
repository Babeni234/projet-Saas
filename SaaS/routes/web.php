<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\BatimentController;
use App\Http\Controllers\ProprietaireController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\LogementController;
use App\Http\Controllers\LocataireController;
use App\Http\Controllers\AffectationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Manual require for Nangue controllers to bypass autoloader issue
require_once base_path('nangue/Http/Controllers/UserDashboardController.php');
require_once base_path('nangue/Http/Controllers/LandlordDashboardController.php');
require_once base_path('nangue/Http/Controllers/PropertyController.php');
require_once base_path('nangue/Http/Controllers/MessageController.php');
require_once base_path('nangue/Http/Controllers/FavoriteController.php');
require_once base_path('nangue/Http/Controllers/ContractController.php');
require_once base_path('nangue/Http/Controllers/ReceiptController.php');
require_once base_path('nangue/Http/Controllers/VisitController.php');
require_once base_path('nangue/Http/Controllers/AnalyticsController.php');
require_once base_path('nangue/Http/Controllers/LandlordVerificationController.php');
require_once base_path('nangue/Support/DemoData.php');

app('router')->aliasMiddleware(
    'landlord.verified',
    \App\Http\Middleware\EnsureLandlordVerified::class
);

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard'); // Redirect to existing dashboard route
    });
    // ... rest of the content

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
    Route::get('/api/illustrations', [\App\Http\Controllers\IllustrationController::class, 'fetchJson'])->name('illustrations.json');
    Route::post('/api/illustrations', [\App\Http\Controllers\IllustrationController::class, 'store'])->name('illustrations.store');
    Route::put('/api/illustrations/{illustration}', [\App\Http\Controllers\IllustrationController::class, 'update'])->name('illustrations.update');
    Route::delete('/api/illustrations/{illustration}', [\App\Http\Controllers\IllustrationController::class, 'destroy'])->name('illustrations.destroy');

    // Batiments API routes
    Route::get('/api/batiments', [BatimentController::class, 'index'])->name('batiments.json');
    Route::post('/api/batiments', [BatimentController::class, 'store'])->name('batiments.store');
    Route::put('/api/batiments/{batiment}', [BatimentController::class, 'update'])->name('batiments.update');
    Route::delete('/api/batiments/{batiment}', [BatimentController::class, 'destroy'])->name('batiments.destroy');

    // Proprietaires API routes
    Route::get('/api/proprietaires', [ProprietaireController::class, 'index'])->name('proprietaires.json');
    Route::post('/api/proprietaires', [ProprietaireController::class, 'store'])->name('proprietaires.store');
    Route::put('/api/proprietaires/{proprietaire}', [ProprietaireController::class, 'update'])->name('proprietaires.update');
    Route::delete('/api/proprietaires/{proprietaire}', [ProprietaireController::class, 'destroy'])->name('proprietaires.destroy');
    Route::post('/api/proprietaires/{proprietaire}/photo', [ProprietaireController::class, 'uploadPhoto'])->name('proprietaires.photo');
    Route::delete('/api/proprietaires/{proprietaire}/photo', [ProprietaireController::class, 'deletePhoto'])->name('proprietaires.photo.delete');
    Route::post('/api/proprietaires/{proprietaire}/documents', [ProprietaireController::class, 'uploadDocument'])->name('proprietaires.documents.upload');
    Route::delete('/api/proprietaires/{proprietaire}/documents/{index}', [ProprietaireController::class, 'deleteDocument'])->name('proprietaires.documents.delete');

    // Categories API routes
    Route::get('/api/categories', [CategorieController::class, 'index'])->name('categories.json');
    Route::post('/api/categories', [CategorieController::class, 'store'])->name('categories.store');
    Route::put('/api/categories/{categorie}', [CategorieController::class, 'update'])->name('categories.update');
    Route::delete('/api/categories/{categorie}', [CategorieController::class, 'destroy'])->name('categories.destroy');

    // Logements API routes
    Route::get('/api/logements', [LogementController::class, 'index'])->name('logements.json');
    Route::post('/api/logements', [LogementController::class, 'store'])->name('logements.store');
    Route::put('/api/logements/{logement}', [LogementController::class, 'update'])->name('logements.update');
    Route::delete('/api/logements/{logement}', [LogementController::class, 'destroy'])->name('logements.destroy');

    // Locataires API routes
    Route::get('/api/locataires', [LocataireController::class, 'index'])->name('locataires.json');
    Route::post('/api/locataires', [LocataireController::class, 'store'])->name('locataires.store');
    Route::post('/api/locataires/{locataire}', [LocataireController::class, 'update'])->name('locataires.update');
    Route::delete('/api/locataires/{locataire}', [LocataireController::class, 'destroy'])->name('locataires.destroy');
    Route::post('/api/locataires/{locataire}/status', [LocataireController::class, 'updateStatus'])->name('locataires.status');
    Route::delete('/api/locataires/{locataire}/documents/{index}', [LocataireController::class, 'deleteDocument'])->name('locataires.documents.delete');

    // Affectations API routes
    Route::get('/api/affectations', [AffectationController::class, 'index'])->name('affectations.json');
    Route::post('/api/affectations', [AffectationController::class, 'store'])->name('affectations.store');
    Route::post('/api/affectations/{affectation}/terminate', [AffectationController::class, 'terminate'])->name('affectations.terminate');
    Route::delete('/api/affectations/{affectation}', [AffectationController::class, 'destroy'])->name('affectations.destroy');

    // Page Proprietaires
    Route::get('/immobilier/proprietaires', function () {
        return Inertia::render('entreprise/Dashboard/Dashboard', [
            'initialRoute' => 'immobilier/proprietaires'
        ]);
    })->name('immobilier.proprietaires');
});

// Route simple pour afficher le dashboard du locataire
Route::get('/dashboard-locataire', function () {
    return Inertia::render('Locataire/dashboard-loc'); // Ne pas mettre l'extension .vue
})->name('dashboard.locataire')->middleware(['auth']); 
// Le middleware 'auth' sécurise la route pour que seuls les locataires connectés y accèdent

require __DIR__.'/auth.php';

