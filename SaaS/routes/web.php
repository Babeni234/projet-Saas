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
    Route::post('/api/ai/generate-etat-des-lieux', [\App\Http\Controllers\ContractGenerationController::class, 'generateEtatDesLieux'])->name('ai.generate-etat-des-lieux');
    Route::post('/api/ai/generate-rejection-motif', [\App\Http\Controllers\ContractGenerationController::class, 'generateRejectionMotif'])->name('ai.generate-rejection-motif');
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

    // TypeContrats API routes
    Route::get('/api/type-contrats', [\App\Http\Controllers\TypeContratController::class, 'index'])->name('type-contrats.json');
    Route::post('/api/type-contrats', [\App\Http\Controllers\TypeContratController::class, 'store'])->name('type-contrats.store');
    Route::put('/api/type-contrats/{typeContrat}', [\App\Http\Controllers\TypeContratController::class, 'update'])->name('type-contrats.update');
    Route::delete('/api/type-contrats/{typeContrat}', [\App\Http\Controllers\TypeContratController::class, 'destroy'])->name('type-contrats.destroy');

    // TypeEngagements API routes
    Route::get('/api/type-engagements', [\App\Http\Controllers\TypeEngagementController::class, 'index'])->name('type-engagements.json');
    Route::post('/api/type-engagements', [\App\Http\Controllers\TypeEngagementController::class, 'store'])->name('type-engagements.store');
    Route::put('/api/type-engagements/{typeEngagement}', [\App\Http\Controllers\TypeEngagementController::class, 'update'])->name('type-engagements.update');
    Route::delete('/api/type-engagements/{typeEngagement}', [\App\Http\Controllers\TypeEngagementController::class, 'destroy'])->name('type-engagements.destroy');

    // Engagements API routes
    Route::get('/api/engagements', [\App\Http\Controllers\EngagementController::class, 'index'])->name('engagements.json');
    Route::post('/api/engagements', [\App\Http\Controllers\EngagementController::class, 'store'])->name('engagements.store');
    Route::put('/api/engagements/{engagement}', [\App\Http\Controllers\EngagementController::class, 'update'])->name('engagements.update');
    Route::delete('/api/engagements/{engagement}', [\App\Http\Controllers\EngagementController::class, 'destroy'])->name('engagements.destroy');
    Route::post('/api/engagements/{engagement}/confirm-honor', [\App\Http\Controllers\EngagementController::class, 'confirmHonor'])->name('engagements.confirm-honor');

    // Renouvellements API routes
    Route::get('/api/renouvellements', [\App\Http\Controllers\RenouvellementController::class, 'index'])->name('renouvellements.json');
    Route::post('/api/renouvellements', [\App\Http\Controllers\RenouvellementController::class, 'store'])->name('renouvellements.store');
    Route::put('/api/renouvellements/{renouvellement}', [\App\Http\Controllers\RenouvellementController::class, 'update'])->name('renouvellements.update');
    Route::post('/api/renouvellements/{renouvellement}/approuver', [\App\Http\Controllers\RenouvellementController::class, 'approuver'])->name('renouvellements.approuver');
    Route::post('/api/renouvellements/{renouvellement}/rejeter', [\App\Http\Controllers\RenouvellementController::class, 'rejeter'])->name('renouvellements.rejeter');
    Route::post('/api/renouvellements/{renouvellement}/confirmer', [\App\Http\Controllers\RenouvellementController::class, 'confirmer'])->name('renouvellements.confirmer');
    Route::delete('/api/renouvellements/{renouvellement}', [\App\Http\Controllers\RenouvellementController::class, 'destroy'])->name('renouvellements.destroy');

    // TypeEtatDesLieux API routes
    Route::get('/api/type-etat-des-lieux', [\App\Http\Controllers\TypeEtatDesLieuxController::class, 'index'])->name('type-etat-des-lieux.json');
    Route::post('/api/type-etat-des-lieux', [\App\Http\Controllers\TypeEtatDesLieuxController::class, 'store'])->name('type-etat-des-lieux.store');
    Route::put('/api/type-etat-des-lieux/{typeEtatDesLieux}', [\App\Http\Controllers\TypeEtatDesLieuxController::class, 'update'])->name('type-etat-des-lieux.update');
    Route::delete('/api/type-etat-des-lieux/{typeEtatDesLieux}', [\App\Http\Controllers\TypeEtatDesLieuxController::class, 'destroy'])->name('type-etat-des-lieux.destroy');

    // EtatDesLieux API routes
    Route::get('/api/etat-des-lieux', [\App\Http\Controllers\EtatDesLieuxController::class, 'index'])->name('etat-des-lieux.json');
    Route::post('/api/etat-des-lieux', [\App\Http\Controllers\EtatDesLieuxController::class, 'store'])->name('etat-des-lieux.store');
    Route::put('/api/etat-des-lieux/{etatDesLieux}', [\App\Http\Controllers\EtatDesLieuxController::class, 'update'])->name('etat-des-lieux.update');
    Route::delete('/api/etat-des-lieux/{etatDesLieux}', [\App\Http\Controllers\EtatDesLieuxController::class, 'destroy'])->name('etat-des-lieux.destroy');

    // Devise API routes
    Route::get('/api/devise', [\App\Http\Controllers\DeviseController::class, 'show'])->name('devise.show');
    Route::post('/api/devise', [\App\Http\Controllers\DeviseController::class, 'storeOrUpdate'])->name('devise.storeOrUpdate');

    // TypeFactures API routes
    Route::get('/api/type-factures', [\App\Http\Controllers\TypeFactureController::class, 'index'])->name('type-factures.json');
    Route::post('/api/type-factures', [\App\Http\Controllers\TypeFactureController::class, 'store'])->name('type-factures.store');
    Route::put('/api/type-factures/{typeFacture}', [\App\Http\Controllers\TypeFactureController::class, 'update'])->name('type-factures.update');
    Route::delete('/api/type-factures/{typeFacture}', [\App\Http\Controllers\TypeFactureController::class, 'destroy'])->name('type-factures.destroy');

    // Factures API routes
    Route::get('/api/factures', [\App\Http\Controllers\FactureController::class, 'index'])->name('factures.json');
    Route::post('/api/factures', [\App\Http\Controllers\FactureController::class, 'store'])->name('factures.store');
    Route::post('/api/factures/{facture}/regler', [\App\Http\Controllers\FactureController::class, 'regler'])->name('factures.regler');
    Route::delete('/api/factures/{facture}', [\App\Http\Controllers\FactureController::class, 'destroy'])->name('factures.destroy');

    // Contrats API routes
    Route::get('/api/contrats', [\App\Http\Controllers\ContratController::class, 'index'])->name('contrats.json');
    Route::post('/api/contrats', [\App\Http\Controllers\ContratController::class, 'store'])->name('contrats.store');
    Route::put('/api/contrats/{contrat}', [\App\Http\Controllers\ContratController::class, 'update'])->name('contrats.update');
    Route::delete('/api/contrats/{contrat}', [\App\Http\Controllers\ContratController::class, 'destroy'])->name('contrats.destroy');

    // ContratTemplates API routes
    Route::get('/api/contrat-templates', [\App\Http\Controllers\ContratTemplateController::class, 'index'])->name('contrat-templates.json');
    Route::post('/api/contrat-templates', [\App\Http\Controllers\ContratTemplateController::class, 'storeOrUpdate'])->name('contrat-templates.storeOrUpdate');

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

    // RegleLoyers API routes
    Route::get('/api/regle-loyers', [\App\Http\Controllers\RegleLoyerController::class, 'index'])->name('regle-loyers.json');
    Route::post('/api/regle-loyers', [\App\Http\Controllers\RegleLoyerController::class, 'store'])->name('regle-loyers.store');
    Route::put('/api/regle-loyers/{regleLoyer}', [\App\Http\Controllers\RegleLoyerController::class, 'update'])->name('regle-loyers.update');
    Route::delete('/api/regle-loyers/{regleLoyer}', [\App\Http\Controllers\RegleLoyerController::class, 'destroy'])->name('regle-loyers.destroy');

    // PaiementLoyers API routes
    Route::get('/api/paiement-loyers', [\App\Http\Controllers\PaiementLoyerController::class, 'index'])->name('paiement-loyers.json');
    Route::post('/api/paiement-loyers', [\App\Http\Controllers\PaiementLoyerController::class, 'store'])->name('paiement-loyers.store');
    Route::delete('/api/paiement-loyers/{paiementLoyer}', [\App\Http\Controllers\PaiementLoyerController::class, 'destroy'])->name('paiement-loyers.destroy');
});

require __DIR__.'/auth.php';
