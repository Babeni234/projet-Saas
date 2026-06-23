<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking locataire data...\r\n\r\n";

$user = \App\Models\User::where('account_type', 'Locataire')->first();

if (!$user) {
    echo "No locataire user found\r\n";
    exit;
}

echo "User found: " . $user->email . "\r\n";
echo "User ID: " . $user->id . "\r\n";
echo "User name: " . $user->name . "\r\n";

$locataire = $user->locataire;
echo "Has locataire relation: " . ($locataire ? 'Yes' : 'No') . "\r\n";

if (!$locataire) {
    echo "No locataire profile found\r\n";
    exit;
}

echo "Locataire ID: " . $locataire->id . "\r\n";

$wallet = $locataire->wallet;
echo "Has wallet: " . ($wallet ? 'Yes' : 'No') . "\r\n";
if ($wallet) {
    echo "Wallet ID: " . $wallet->id . "\r\n";
    echo "Wallet solde: " . $wallet->solde . "\r\n";
}

$contracts = $locataire->contrats;
echo "Contracts count: " . $contracts->count() . "\r\n";
foreach ($contracts as $contract) {
    echo "  - Contract ID: " . $contract->id . ", Statut: " . $contract->statut . "\r\n";
}

$company = $locataire->company;
echo "Has company: " . ($company ? 'Yes' : 'No') . "\r\n";
if ($company) {
    echo "Company name: " . $company->nom . "\r\n";
}

$agency = $locataire->agency;
echo "Has agency: " . ($agency ? 'Yes' : 'No') . "\r\n";
if ($agency) {
    echo "Agency name: " . $agency->nom . "\r\n";
}

echo "\r\nDone.\r\n";
