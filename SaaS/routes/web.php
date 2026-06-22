<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

        // Incidents
        Route::get('/incidents', [\Nangue\Http\Controllers\IncidentController::class, 'index'])->name('landlord.incidents.index');
        Route::get('/incidents/creer', [\Nangue\Http\Controllers\IncidentController::class, 'create'])->name('landlord.incidents.create');
        Route::post('/incidents', [\Nangue\Http\Controllers\IncidentController::class, 'store'])->name('landlord.incidents.store');
        Route::get('/incidents/{incident}', [\Nangue\Http\Controllers\IncidentController::class, 'show'])->name('landlord.incidents.show');
        Route::patch('/incidents/{incident}', [\Nangue\Http\Controllers\IncidentController::class, 'update'])->name('landlord.incidents.update');
        Route::delete('/incidents/{incident}', [\Nangue\Http\Controllers\IncidentController::class, 'destroy'])->name('landlord.incidents.destroy');

        // États des lieux
        Route::get('/etats-des-lieux', [\Nangue\Http\Controllers\InspectionController::class, 'index'])->name('landlord.inspections.index');
        Route::get('/etats-des-lieux/creer', [\Nangue\Http\Controllers\InspectionController::class, 'create'])->name('landlord.inspections.create');
        Route::post('/etats-des-lieux', [\Nangue\Http\Controllers\InspectionController::class, 'store'])->name('landlord.inspections.store');
        Route::get('/etats-des-lieux/{inspection}', [\Nangue\Http\Controllers\InspectionController::class, 'show'])->name('landlord.inspections.show');
        Route::patch('/etats-des-lieux/{inspection}', [\Nangue\Http\Controllers\InspectionController::class, 'update'])->name('landlord.inspections.update');
        Route::delete('/etats-des-lieux/{inspection}', [\Nangue\Http\Controllers\InspectionController::class, 'destroy'])->name('landlord.inspections.destroy');

        // Documents
        Route::get('/documents', [\Nangue\Http\Controllers\DocumentController::class, 'index'])->name('landlord.documents.index');
        Route::post('/documents', [\Nangue\Http\Controllers\DocumentController::class, 'store'])->name('landlord.documents.store');
        Route::patch('/documents/{document}', [\Nangue\Http\Controllers\DocumentController::class, 'update'])->name('landlord.documents.update');
        Route::delete('/documents/{document}', [\Nangue\Http\Controllers\DocumentController::class, 'destroy'])->name('landlord.documents.destroy');

        // Révision des loyers
        Route::get('/revisions-loyers', [\Nangue\Http\Controllers\RentRevisionController::class, 'index'])->name('landlord.rent-revisions.index');
        Route::post('/revisions-loyers', [\Nangue\Http\Controllers\RentRevisionController::class, 'store'])->name('landlord.rent-revisions.store');
        Route::post('/revisions-loyers/{revision}/appliquer', [\Nangue\Http\Controllers\RentRevisionController::class, 'apply'])->name('landlord.rent-revisions.apply');

        // Cautions
        Route::get('/cautions', [\Nangue\Http\Controllers\DepositController::class, 'index'])->name('landlord.deposits.index');
        Route::post('/cautions', [\Nangue\Http\Controllers\DepositController::class, 'store'])->name('landlord.deposits.store');
        Route::post('/cautions/{deposit}/restituer', [\Nangue\Http\Controllers\DepositController::class, 'returnDeposit'])->name('landlord.deposits.return');

        // Garanties / Assurances
        Route::get('/garanties', [\Nangue\Http\Controllers\InsuranceGuaranteeController::class, 'index'])->name('landlord.insurance-guarantees.index');
        Route::post('/garanties', [\Nangue\Http\Controllers\InsuranceGuaranteeController::class, 'store'])->name('landlord.insurance-guarantees.store');
        Route::patch('/garanties/{guarantee}', [\Nangue\Http\Controllers\InsuranceGuaranteeController::class, 'update'])->name('landlord.insurance-guarantees.update');

        // Prélèvements automatiques
        Route::get('/prelevements', [\Nangue\Http\Controllers\SubscriptionController::class, 'index'])->name('landlord.subscriptions.index');
        Route::get('/prelevements/creer', [\Nangue\Http\Controllers\SubscriptionController::class, 'create'])->name('landlord.subscriptions.create');
        Route::post('/prelevements', [\Nangue\Http\Controllers\SubscriptionController::class, 'store'])->name('landlord.subscriptions.store');
        Route::post('/prelevements/{subscription}/annuler', [\Nangue\Http\Controllers\SubscriptionController::class, 'cancel'])->name('landlord.subscriptions.cancel');

        // Moyens de paiement
        Route::get('/moyens-paiement', [\Nangue\Http\Controllers\PaymentMethodController::class, 'index'])->name('landlord.payment-methods.index');
        Route::post('/moyens-paiement', [\Nangue\Http\Controllers\PaymentMethodController::class, 'store'])->name('landlord.payment-methods.store');
        Route::post('/moyens-paiement/{paymentMethod}/defaut', [\Nangue\Http\Controllers\PaymentMethodController::class, 'setDefault'])->name('landlord.payment-methods.default');
        Route::delete('/moyens-paiement/{paymentMethod}', [\Nangue\Http\Controllers\PaymentMethodController::class, 'destroy'])->name('landlord.payment-methods.destroy');

        // Notifications
        Route::get('/notifications', [\Nangue\Http\Controllers\NotificationLogController::class, 'index'])->name('landlord.notifications.index');
        Route::post('/notifications/{notificationLog}/renvoyer', [\Nangue\Http\Controllers\NotificationLogController::class, 'resend'])->name('landlord.notifications.resend');

        // Créneaux de visites en ligne
        Route::get('/creneaux-visites', [\Nangue\Http\Controllers\PublicVisitSlotController::class, 'index'])->name('landlord.visit-slots.index');
        Route::post('/creneaux-visites', [\Nangue\Http\Controllers\PublicVisitSlotController::class, 'store'])->name('landlord.visit-slots.store');
        Route::patch('/creneaux-visites/{slot}', [\Nangue\Http\Controllers\PublicVisitSlotController::class, 'update'])->name('landlord.visit-slots.update');
        Route::delete('/creneaux-visites/{slot}', [\Nangue\Http\Controllers\PublicVisitSlotController::class, 'destroy'])->name('landlord.visit-slots.destroy');

        // Encadrement des loyers
        Route::get('/encadrement-loyers', [\Nangue\Http\Controllers\RentControlController::class, 'index'])->name('landlord.rent-control.index');
        Route::post('/encadrement-loyers/zones', [\Nangue\Http\Controllers\RentControlController::class, 'storeZone'])->name('landlord.rent-control.zones.store');
        Route::post('/encadrement-loyers/conformite', [\Nangue\Http\Controllers\RentControlController::class, 'checkCompliance'])->name('landlord.rent-control.compliance');

        // Fiscal
        Route::get('/fiscal', [\Nangue\Http\Controllers\FiscalController::class, 'index'])->name('landlord.fiscal.index');
        Route::post('/fiscal', [\Nangue\Http\Controllers\FiscalController::class, 'store'])->name('landlord.fiscal.store');
        Route::get('/fiscal/{fiscalYear}', [\Nangue\Http\Controllers\FiscalController::class, 'show'])->name('landlord.fiscal.show');
        Route::post('/fiscal/{fiscalYear}/depenses', [\Nangue\Http\Controllers\FiscalController::class, 'addExpense'])->name('landlord.fiscal.expenses.store');
        Route::post('/fiscal/{fiscalYear}/finaliser', [\Nangue\Http\Controllers\FiscalController::class, 'finalize'])->name('landlord.fiscal.finalize');
    }); // Fin du groupe réservé aux bailleurs vérifiés
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
