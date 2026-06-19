<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = App\Models\User::where('email', 'dongmoduval269@icloud.com')->first();
if (!$user) { echo "Utilisateur non trouvé\n"; exit; }

$locataire = $user->locataire;
if (!$locataire) { echo "Locataire non trouvé\n"; exit; }

echo "Locataire: " . $locataire->nom . " | statut: " . $locataire->statut . "\n";

$affectations = $locataire->affectations()->where('deleted', false)->get();
echo "Affectations totales: " . $affectations->count() . "\n";
foreach ($affectations as $a) {
    echo " - Affectation #" . $a->id . " | statut: [" . $a->statut . "] | loyer: " . $a->loyer . "\n";
}

$affectationsActives = $locataire->affectations()->where('deleted', false)->where('statut', 'Actif')->get();
echo "Affectations actives (statut=Actif): " . $affectationsActives->count() . "\n";
foreach ($affectationsActives as $a) {
    echo " - Affectation #" . $a->id . " | loyer: " . $a->loyer . " | caution: " . $a->caution . "\n";
}
