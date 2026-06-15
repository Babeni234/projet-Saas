<?php

namespace App\Helpers;

use App\Models\Evenement;
use Illuminate\Support\Facades\Auth;

class EventLogger
{
    /**
     * Log a real estate event.
     */
    public static function log($titre, $description, $type, $categorie, $agencyId = null, $companyId = null, $userId = null)
    {
        $user = Auth::user();
        
        $cId = $companyId ?? ($user ? $user->company_profile_id : null);
        $aId = $agencyId ?? ($user && $user->employee ? $user->employee->agency_id : null);
        $uId = $userId ?? ($user ? $user->id : null);

        if (!$cId) {
            return null;
        }

        return Evenement::create([
            'company_profile_id' => $cId,
            'agency_id' => $aId,
            'user_id' => $uId,
            'titre' => $titre,
            'description' => $description,
            'type' => $type,
            'categorie' => $categorie,
        ]);
    }
}
