<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\ReportController;

$controller = new ReportController();

echo "Running Maintenance reporting verification...\n";

$company = \App\Models\CompanyProfile::first();
if (!$company) {
    echo "No company profile found.\n";
    exit(1);
}
$companyId = $company->id;

// Let's find one lodging
$logement = \App\Models\Logement::where('company_profile_id', $companyId)->first();
if (!$logement) {
    echo "No logement found.\n";
    exit(1);
}

// Let's find one building
$batiment = \App\Models\Batiment::where('company_profile_id', $companyId)->first();
if (!$batiment) {
    echo "No batiment found.\n";
    exit(1);
}

// Save original statuses
$origLogStatut = $logement->statut;
$origBatStatut = $batiment->statut;

echo "Original Logement Status: {$origLogStatut}\n";
echo "Original Batiment Status: {$origBatStatut}\n";

// Set to Maintenance
$logement->update(['statut' => 'Maintenance']);
$batiment->update(['statut' => 'Maintenance']);

// Add a test expense for maintenance
$expense = \App\Models\Depense::create([
    'company_profile_id' => $companyId,
    'titre' => 'Reparation fuite maintenance test',
    'categorie' => 'Maintenance',
    'montant' => 450.00,
    'date_depense' => '2026-06-15',
    'statut' => 'Payé',
    'deleted' => false
]);

echo "Set statuses to 'Maintenance' and created a paid test maintenance expense.\n";

// Run compilation
$reflector = new \ReflectionClass(ReportController::class);
$method = $reflector->getMethod('compileMaintenanceReportData');
$method->setAccessible(true);
$result = $method->invokeArgs($controller, [$companyId, null, 'Juin 2026']);

echo "COMPILATION RESULTS:\n";
echo "  Total Expenses: " . $result['total_expenses'] . "\n";
echo "  Interventions Count: " . $result['interventions_count'] . "\n";
echo "  Average Cost: " . $result['average_cost'] . "\n";
echo "  Buildings in Maintenance: " . count($result['buildings_in_maintenance']) . "\n";
foreach ($result['buildings_in_maintenance'] as $b) {
    echo "    - " . $b['nom'] . " (Ref: " . $b['reference'] . ")\n";
}
echo "  Logements in Maintenance: " . count($result['logements_in_maintenance']) . "\n";
foreach ($result['logements_in_maintenance'] as $l) {
    echo "    - Logement Ref: " . $l['reference'] . "\n";
}
echo "  Expenses Detail: " . count($result['expenses_detail']) . "\n";
foreach ($result['expenses_detail'] as $e) {
    echo "    - Title: " . $e['titre'] . ", Amount: " . $e['montant'] . " EUR\n";
}

// Restore
$logement->update(['statut' => $origLogStatut]);
$batiment->update(['statut' => $origBatStatut]);
$expense->delete();

echo "\nRestored original statuses and deleted test expense.\n";
