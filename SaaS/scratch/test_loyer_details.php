<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\ReportController;

$controller = new ReportController();

echo "Running Loyer reporting details verification...\n";

$company = \App\Models\CompanyProfile::first();
if (!$company) {
    echo "No company profile found.\n";
    exit(1);
}
$companyId = $company->id;
$periode = "Juin 2026";

$reflector = new \ReflectionClass(ReportController::class);
$method = $reflector->getMethod('compileLoyerReportData');
$method->setAccessible(true);
$result = $method->invokeArgs($controller, [$companyId, null, $periode]);

echo "Loyer Report Compilation Results for {$periode}:\n";
echo "Summary: " . json_encode($result['summary'], JSON_PRETTY_PRINT) . "\n";
echo "Hierarchy (Logement details):\n";

if (isset($result['hierarchy'])) {
    print_r($result['hierarchy']);
} else {
    echo "No hierarchy found.\n";
}
