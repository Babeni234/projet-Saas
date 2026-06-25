<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Countries in Database ===\n";
$countries = \App\Models\Country::all();
if ($countries->isEmpty()) {
    echo "No countries found in database.\n";
} else {
    foreach ($countries as $c) {
        echo "  - Code: {$c->code}, Name: {$c->name}, Lat: {$c->latitude}, Lng: {$c->longitude}\n";
    }
}

echo "\n=== Company Profiles in Database ===\n";
$companies = \App\Models\CompanyProfile::all();
if ($companies->isEmpty()) {
    echo "No company profiles found in database.\n";
} else {
    foreach ($companies as $cp) {
        echo "  - ID: {$cp->id}, Name: {$cp->legal_name}, Country: {$cp->country}, City: {$cp->city}\n";
    }
}
