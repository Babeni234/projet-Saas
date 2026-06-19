<?php
// Test du filtre corrigé - whereIn avec tous les statuts actifs
$user = App\Models\User::where('email', 'dongmoduval269@icloud.com')->first();
if (!$user) { echo "No user\n"; return; }

$loc = $user->locataire;
if (!$loc) { echo "No locataire\n"; return; }

$affs = $loc->affectations()
    ->where('deleted', false)
    ->whereIn('statut', [
        'Actif', 'actif', 'active', 'en_cours',
        "En cours d'exécution", 'En cours', 'signé', 'signe'
    ])
    ->get();

echo "Affectations avec nouveau filtre: " . $affs->count() . "\n";
foreach ($affs as $a) {
    echo "ID:" . $a->id . " statut:[" . $a->statut . "] loyer:" . $a->loyer . "\n";
}
