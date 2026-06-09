<?php

use App\Http\Controllers\ProfileController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('immo.particulier');
    })->name('dashboard');

    // Routes pour la vérification du statut de bailleur (accessible à tous les utilisateurs connectés)
    Route::get('/bailleur/verification', function () {
        $controller = new \Nangue\Http\Controllers\LandlordVerificationController();
        return $controller->create();
    })->name('landlord.verification.create');

    Route::post('/bailleur/verification', function () {
        $controller = new \Nangue\Http\Controllers\LandlordVerificationController();
        return $controller->store(request());
    })->name('landlord.verification.store');

    Route::get('/bailleur/verification/en-attente', function () {
        $controller = new \Nangue\Http\Controllers\LandlordVerificationController();
        return $controller->pending();
    })->name('landlord.verification.pending');

    // Routes accessibles à tous les utilisateurs connectés (particulier + bailleur)
    Route::get('/espace-particulier', function () {
        $controller = new \Nangue\Http\Controllers\UserDashboardController();
        return $controller->__invoke();
    })->name('immo.particulier');

    Route::get('/mes-logements', function () {
        $controller = new \Nangue\Http\Controllers\PropertyController();
        return $controller->index();
    })->name('immo.properties');

    Route::get('/mes-publications', function () {
        $controller = new \Nangue\Http\Controllers\PropertyController();
        return $controller->publications();
    })->name('immo.publications');

    Route::get('/creer-annonce', function () {
        $controller = new \Nangue\Http\Controllers\PropertyController();
        return $controller->create();
    })->name('immo.property.create');

    Route::post('/creer-annonce', function () {
        $controller = new \Nangue\Http\Controllers\PropertyController();
        return $controller->store(request());
    })->name('immo.property.store');

    Route::get('/bien/{id}', function ($id) {
        $controller = new \Nangue\Http\Controllers\PropertyController();
        return $controller->show($id);
    })->name('immo.property.show');

    Route::get('/bien/{id}/modifier', function ($id) {
        $controller = new \Nangue\Http\Controllers\PropertyController();
        return $controller->edit($id);
    })->name('immo.property.edit');

    Route::put('/bien/{id}', function ($id) {
        $controller = new \Nangue\Http\Controllers\PropertyController();
        return $controller->update(request(), $id);
    })->name('immo.property.update');

    Route::delete('/bien/{id}', function ($id) {
        $controller = new \Nangue\Http\Controllers\PropertyController();
        return $controller->destroy($id);
    })->name('immo.property.destroy');

    Route::get('/messages', function () {
        $controller = new \Nangue\Http\Controllers\MessageController();
        return $controller->index();
    })->name('immo.messages');

    Route::get('/messages/{id}', function ($id) {
        $controller = new \Nangue\Http\Controllers\MessageController();
        return $controller->show($id);
    })->name('immo.messages.show');

    Route::post('/messages', function () {
        $controller = new \Nangue\Http\Controllers\MessageController();
        return $controller->store(request());
    })->name('immo.messages.store');

    Route::post('/messages/{id}/read', function ($id) {
        $controller = new \Nangue\Http\Controllers\MessageController();
        return $controller->markAsRead($id);
    })->name('immo.messages.read');

    Route::get('/favoris', function () {
        $controller = new \Nangue\Http\Controllers\FavoriteController();
        return $controller->index();
    })->name('immo.favorites');

    Route::post('/favoris', function () {
        $controller = new \Nangue\Http\Controllers\FavoriteController();
        return $controller->store(request());
    })->name('immo.favorites.store');

    Route::delete('/favoris/{id}', function ($id) {
        $controller = new \Nangue\Http\Controllers\FavoriteController();
        return $controller->destroy($id);
    })->name('immo.favorites.destroy');

    Route::post('/favoris/export', function () {
        $controller = new \Nangue\Http\Controllers\FavoriteController();
        return $controller->export();
    })->name('immo.favorites.export');

    Route::get('/recherche', function () {
        return Inertia::render('Nangue/User/SearchResults');
    })->name('immo.search');

    Route::get('/recherches-enregistrees', function () {
        return Inertia::render('Nangue/User/SavedSearches');
    })->name('immo.saved_searches');

    // Routes protégées réservées aux bailleurs vérifiés
    Route::middleware('landlord.verified')->group(function () {
        Route::get('/espace-bailleur', function () {
            $controller = new \Nangue\Http\Controllers\LandlordDashboardController();
            return $controller->__invoke();
        })->name('immo.bailleur');

        Route::get('/contrats', function () {
            $controller = new \Nangue\Http\Controllers\ContractController();
            return $controller->index();
        })->name('landlord.contracts.index');

        Route::get('/contrats/creer', function () {
            $controller = new \Nangue\Http\Controllers\ContractController();
            return $controller->create();
        })->name('landlord.contracts.create');

        Route::post('/contrats', function () {
            $controller = new \Nangue\Http\Controllers\ContractController();
            return $controller->store(request());
        })->name('landlord.contracts.store');

        Route::get('/contrats/{id}', function ($id) {
            $controller = new \Nangue\Http\Controllers\ContractController();
            return $controller->show($id);
        })->name('landlord.contracts.show');

        Route::get('/contrats/{id}/modifier', function ($id) {
            $controller = new \Nangue\Http\Controllers\ContractController();
            return $controller->edit($id);
        })->name('landlord.contracts.edit');

        Route::put('/contrats/{id}', function ($id) {
            $controller = new \Nangue\Http\Controllers\ContractController();
            return $controller->update(request(), $id);
        })->name('landlord.contracts.update');

        Route::delete('/contrats/{id}', function ($id) {
            $controller = new \Nangue\Http\Controllers\ContractController();
            return $controller->destroy($id);
        })->name('landlord.contracts.destroy');

        Route::get('/quittances', function () {
            $controller = new \Nangue\Http\Controllers\ReceiptController();
            return $controller->index();
        })->name('landlord.payments.index');

        Route::get('/quittances/creer', function () {
            $controller = new \Nangue\Http\Controllers\ReceiptController();
            return $controller->create();
        })->name('landlord.payments.create');

        Route::post('/quittances', function () {
            $controller = new \Nangue\Http\Controllers\ReceiptController();
            return $controller->store(request());
        })->name('landlord.payments.store');

        Route::get('/quittances/{id}', function ($id) {
            $controller = new \Nangue\Http\Controllers\ReceiptController();
            return $controller->show($id);
        })->name('landlord.payments.show');

        Route::get('/quittances/{id}/telecharger', function ($id) {
            $controller = new \Nangue\Http\Controllers\ReceiptController();
            return $controller->download($id);
        })->name('landlord.payments.download');

        Route::post('/quittances/{id}/envoyer', function ($id) {
            $controller = new \Nangue\Http\Controllers\ReceiptController();
            return $controller->send($id);
        })->name('landlord.payments.send');

        Route::post('/quittances/exporter', function () {
            $controller = new \Nangue\Http\Controllers\ReceiptController();
            return $controller->export();
        })->name('landlord.payments.export');

        Route::get('/calendrier-visites', function () {
            $controller = new \Nangue\Http\Controllers\VisitController();
            return $controller->index();
        })->name('landlord.calendar.index');

        Route::get('/calendrier-visites/creer', function () {
            $controller = new \Nangue\Http\Controllers\VisitController();
            return $controller->create();
        })->name('landlord.calendar.create');

        Route::post('/calendrier-visites', function () {
            $controller = new \Nangue\Http\Controllers\VisitController();
            return $controller->store(request());
        })->name('landlord.calendar.store');

        Route::get('/calendrier-visites/{id}', function ($id) {
            $controller = new \Nangue\Http\Controllers\VisitController();
            return $controller->show($id);
        })->name('landlord.calendar.show');

        Route::get('/calendrier-visites/{id}/modifier', function ($id) {
            $controller = new \Nangue\Http\Controllers\VisitController();
            return $controller->edit($id);
        })->name('landlord.calendar.edit');

        Route::put('/calendrier-visites/{id}', function ($id) {
            $controller = new \Nangue\Http\Controllers\VisitController();
            return $controller->update(request(), $id);
        })->name('landlord.calendar.update');

        Route::post('/calendrier-visites/{id}/annuler', function ($id) {
            $controller = new \Nangue\Http\Controllers\VisitController();
            return $controller->cancel($id);
        })->name('landlord.calendar.cancel');

        Route::delete('/calendrier-visites/{id}', function ($id) {
            $controller = new \Nangue\Http\Controllers\VisitController();
            return $controller->destroy($id);
        })->name('landlord.calendar.destroy');

        Route::get('/creer-annonce-rapide', function () {
            return Inertia::render('Nangue/Landlord/QuickCreatePublication');
        })->name('landlord.quick_publication.create');

        Route::get('/locataires', function () {
            return Inertia::render('Nangue/Landlord/Tenants');
        })->name('landlord.tenants.index');

        Route::get('/equipe', function () {
            return Inertia::render('Nangue/Landlord/SettingsTeam');
        })->name('landlord.settings.team');

        Route::get('/analytiques', function () {
            $controller = new \Nangue\Http\Controllers\AnalyticsController();
            return $controller->index();
        })->name('landlord.reports.index');

        Route::post('/analytiques/exporter', function () {
            $controller = new \Nangue\Http\Controllers\AnalyticsController();
            return $controller->export(request());
        })->name('landlord.reports.export');
    }); // Fin du groupe réservé aux bailleurs vérifiés
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
