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
        Route::get('ai-analytics', [\App\Http\Controllers\Ai\AnalyticsController::class, 'index'])->name('ai.analytics');

        Route::prefix('pro')->name('pro.')->group(function () {
            Route::get('dashboard', [\App\Http\Controllers\Landlord\Pro\DashboardController::class, 'index'])->name('dashboard');

            Route::resource('portfolios', \App\Http\Controllers\Landlord\Pro\PortfolioController::class);

            Route::get('team', [\App\Http\Controllers\Landlord\Pro\TeamController::class, 'index'])->name('team.index');
            Route::post('team/invite', [\App\Http\Controllers\Landlord\Pro\TeamController::class, 'invite'])->name('team.invite');
            Route::post('team/{invitation}/resend', [\App\Http\Controllers\Landlord\Pro\TeamController::class, 'resend'])->name('team.resend');
            Route::delete('team/invitations/{invitation}', [\App\Http\Controllers\Landlord\Pro\TeamController::class, 'cancelInvitation'])->name('team.cancel');
            Route::delete('team/members/{member}', [\App\Http\Controllers\Landlord\Pro\TeamController::class, 'removeMember'])->name('team.remove');
            Route::get('team/accept/{token}', [\App\Http\Controllers\Landlord\Pro\TeamController::class, 'acceptInvitation'])->name('team.accept');

            Route::get('ged', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'index'])->name('ged.index');
            Route::post('ged/folders', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'storeFolder'])->name('ged.folders.store');
            Route::post('ged/folders/{documentFolder}', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'updateFolder'])->name('ged.folders.update');
            Route::delete('ged/folders/{documentFolder}', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'destroyFolder'])->name('ged.folders.destroy');
            Route::post('ged/upload', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'upload'])->name('ged.upload');
            Route::post('ged/documents/{document}/star', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'toggleStar'])->name('ged.documents.star');
            Route::post('ged/documents/{document}', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'updateDocument'])->name('ged.documents.update');
            Route::post('ged/documents/{document}/move', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'moveDocument'])->name('ged.documents.move');
            Route::delete('ged/documents/{document}', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'destroyDocument'])->name('ged.documents.destroy');
            Route::post('ged/documents/{document}/share', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'shareDocument'])->name('ged.documents.share');
            Route::delete('ged/shares/{documentShare}', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'revokeShare'])->name('ged.shares.revoke');
            Route::post('ged/documents/{document}/versions', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'uploadVersion'])->name('ged.documents.versions');
            Route::get('ged/templates', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'templates'])->name('ged.templates');
            Route::post('ged/templates', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'storeTemplate'])->name('ged.templates.store');
            Route::delete('ged/templates/{documentTemplate}', [\App\Http\Controllers\Landlord\Pro\GedController::class, 'destroyTemplate'])->name('ged.templates.destroy');

            Route::get('jurisdiction', [\App\Http\Controllers\Landlord\Pro\JurisdictionController::class, 'index'])->name('jurisdiction.index');
            Route::post('jurisdiction/preview', [\App\Http\Controllers\Landlord\Pro\JurisdictionController::class, 'preview'])->name('jurisdiction.preview');
            Route::put('jurisdiction/save', [\App\Http\Controllers\Landlord\Pro\JurisdictionController::class, 'save'])->name('jurisdiction.save');

            Route::get('accounting', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'index'])->name('accounting.index');
            Route::get('accounting/accounts', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'accounts'])->name('accounting.accounts');
            Route::post('accounting/accounts', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'storeAccount'])->name('accounting.accounts.store');
            Route::post('accounting/accounts/{bankAccount}', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'updateAccount'])->name('accounting.accounts.update');
            Route::delete('accounting/accounts/{bankAccount}', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'destroyAccount'])->name('accounting.accounts.destroy');
            Route::get('accounting/transactions', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'transactions'])->name('accounting.transactions');
            Route::post('accounting/transactions', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'storeTransaction'])->name('accounting.transactions.store');
            Route::delete('accounting/transactions/{transaction}', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'destroyTransaction'])->name('accounting.transactions.destroy');
            Route::get('accounting/categories', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'categories'])->name('accounting.categories');
            Route::post('accounting/categories', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'storeCategory'])->name('accounting.categories.store');
            Route::post('accounting/categories/{accountingCategory}', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'updateCategory'])->name('accounting.categories.update');
            Route::delete('accounting/categories/{accountingCategory}', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'destroyCategory'])->name('accounting.categories.destroy');
            Route::get('accounting/budgets', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'budgets'])->name('accounting.budgets');
            Route::post('accounting/budgets', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'storeBudget'])->name('accounting.budgets.store');
            Route::delete('accounting/budgets/{budget}', [\App\Http\Controllers\Landlord\Pro\AccountingController::class, 'destroyBudget'])->name('accounting.budgets.destroy');

            Route::get('automation', [\App\Http\Controllers\Landlord\Pro\AutomationController::class, 'index'])->name('automation.index');
            Route::get('automation/create', [\App\Http\Controllers\Landlord\Pro\AutomationController::class, 'create'])->name('automation.create');
            Route::post('automation', [\App\Http\Controllers\Landlord\Pro\AutomationController::class, 'store'])->name('automation.store');
            Route::get('automation/{automationRule}', [\App\Http\Controllers\Landlord\Pro\AutomationController::class, 'show'])->name('automation.show');
            Route::post('automation/{automationRule}/toggle', [\App\Http\Controllers\Landlord\Pro\AutomationController::class, 'toggle'])->name('automation.toggle');
            Route::post('automation/{automationRule}/run', [\App\Http\Controllers\Landlord\Pro\AutomationController::class, 'runNow'])->name('automation.run');
            Route::delete('automation/{automationRule}', [\App\Http\Controllers\Landlord\Pro\AutomationController::class, 'destroy'])->name('automation.destroy');

            Route::get('workflows', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'index'])->name('workflows.index');
            Route::get('workflows/create', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'create'])->name('workflows.create');
            Route::post('workflows', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'store'])->name('workflows.store');
            Route::get('workflows/{workflow}', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'show'])->name('workflows.show');
            Route::get('workflows/{workflow}/edit', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'edit'])->name('workflows.edit');
            Route::put('workflows/{workflow}', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'update'])->name('workflows.update');
            Route::delete('workflows/{workflow}', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'destroy'])->name('workflows.destroy');
            Route::post('workflows/{workflow}/run', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'run'])->name('workflows.run');
            Route::get('workflows/{workflow}/instances', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'instances'])->name('workflows.instances');
            Route::post('workflows/from-template', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'fromTemplate'])->name('workflows.from-template');
            Route::post('workflows/{workflow}/steps', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'addStep'])->name('workflows.steps.add');
            Route::put('workflows/steps/{step}', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'updateStep'])->name('workflows.steps.update');
            Route::post('workflows/{workflow}/steps/reorder', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'reorderSteps'])->name('workflows.steps.reorder');
            Route::delete('workflows/steps/{step}', [\App\Http\Controllers\Landlord\Pro\Workflow\WorkflowController::class, 'removeStep'])->name('workflows.steps.destroy');
        });
    });
});

Route::prefix('locataire')->name('tenant.')->group(function () {
    Route::get('login', [\App\Http\Controllers\Tenant\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [\App\Http\Controllers\Tenant\AuthController::class, 'login'])->name('login.submit');
    Route::post('logout', [\App\Http\Controllers\Tenant\AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:tenant')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('dashboard');
        Route::get('receipts', [\App\Http\Controllers\Tenant\ReceiptController::class, 'index'])->name('receipts.index');
        Route::get('receipts/{receipt}/pdf', [\App\Http\Controllers\Tenant\ReceiptPdfController::class, 'download'])->name('receipts.pdf');
        Route::resource('incidents', \App\Http\Controllers\Tenant\IncidentController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('incidents/{incident}/comment', [\App\Http\Controllers\Tenant\IncidentController::class, 'comment'])->name('incidents.comment');
        Route::get('documents', [\App\Http\Controllers\Tenant\DocumentController::class, 'index'])->name('documents.index');
        Route::get('messages', [\App\Http\Controllers\Tenant\MessageController::class, 'index'])->name('messages.index');
        Route::get('messages/{conversation}', [\App\Http\Controllers\Tenant\MessageController::class, 'show'])->name('messages.show');
        Route::post('messages/{conversation}/reply', [\App\Http\Controllers\Tenant\MessageController::class, 'reply'])->name('messages.reply');
    });
});

require __DIR__.'/auth.php';
