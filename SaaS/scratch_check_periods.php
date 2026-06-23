<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Batiment statuses:\n";
$bs = \App\Models\Batiment::select('statut', \DB::raw('count(*) as count'))->groupBy('statut')->get();
foreach ($bs as $row) {
    echo "  - Status: '{$row->statut}', Count: {$row->count}\n";
}

echo "\nLogement statuses:\n";
$ls = \App\Models\Logement::select('statut', \DB::raw('count(*) as count'))->groupBy('statut')->get();
foreach ($ls as $row) {
    echo "  - Status: '{$row->statut}', Count: {$row->count}\n";
}

echo "\nMaintenance Expenses (category 'Maintenance' or title containing maintenance):\n";
$exps = \App\Models\Depense::where('deleted', false)
    ->where(function($q) {
        $q->where('categorie', 'like', '%maintenance%')
          ->orWhere('titre', 'like', '%maintenance%')
          ->orWhere('description', 'like', '%maintenance%')
          ->orWhere('titre', 'like', '%travaux%');
    })
    ->get();
foreach ($exps as $row) {
    echo "  - Title: '{$row->titre}', Cat: '{$row->categorie}', Status: '{$row->statut}', Amount: {$row->montant}\n";
}

