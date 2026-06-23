<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\ReportController;

$controller = new ReportController();

echo "Running Report compiler tests...\n";

// Let's find a valid company profile id
$company = \App\Models\CompanyProfile::first();
if (!$company) {
    echo "No company profile found in database. Exiting.\n";
    exit(1);
}
$companyId = $company->id;
$periode = "Juin 2026";

echo "Found Company ID: {$companyId}\n";

// Reflector to access private/protected methods
$reflector = new \ReflectionClass(ReportController::class);

$methods = [
    'compileLoyerReportData' => [$companyId, null, $periode],
    'compileFinancierReportData' => [$companyId, null, $periode],
    'compileOccupationReportData' => [$companyId, null, $periode],
    'compileMaintenanceReportData' => [$companyId, null, $periode]
];

foreach ($methods as $methodName => $args) {
    try {
        echo "Testing {$methodName}... ";
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);
        $result = $method->invokeArgs($controller, $args);
        
        echo "SUCCESS!\n";
        echo "  Keys returned: " . implode(', ', array_keys($result)) . "\n";
        if (isset($result['summary'])) {
            echo "  Summary: " . json_encode($result['summary']) . "\n";
        } elseif (isset($result['buildings_breakdown'])) {
            echo "  Summary: Total Units=" . $result['total_units'] . ", Occupied=" . $result['occupied_units'] . "\n";
            echo "  Buildings breakdown: " . json_encode($result['buildings_breakdown']) . "\n";
        } elseif (isset($result['interventions_count'])) {
            echo "  Summary: Expenses=" . $result['total_expenses'] . ", Count=" . $result['interventions_count'] . "\n";
            echo "  Logements under maintenance: " . json_encode($result['logements_in_maintenance']) . "\n";
        } elseif (isset($result['total_revenue'])) {
            echo "  Summary: Revenues=" . $result['total_revenue'] . ", Expenses=" . $result['total_expenses'] . ", Net=" . $result['net_profit'] . "\n";
            echo "  Revenues detail count: " . count($result['revenues_detail']) . ", Expenses detail count: " . count($result['expenses_detail']) . "\n";
        }
    } catch (\Exception $e) {
        echo "FAILED!\n";
        echo "  Error: " . $e->getMessage() . "\n";
        echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
}
