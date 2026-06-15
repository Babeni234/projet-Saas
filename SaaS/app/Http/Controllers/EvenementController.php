<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvenementController extends Controller
{
    /**
     * Display a listing of real estate events.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        if (!$companyProfileId) {
            return response()->json([]);
        }

        $query = Evenement::where('company_profile_id', $companyProfileId)
            ->with(['user.role', 'agency']);

        // Check if user is restricted to an agency (espace agence)
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        if ($isAgent) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $evenements = $query->orderBy('created_at', 'desc')->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'titre' => $event->titre,
                'description' => $event->description,
                'type' => $event->type,
                'categorie' => $event->categorie,
                'date' => $event->created_at->toDateString(),
                'heure' => $event->created_at->format('H:i'),
                'utilisateur' => $event->user ? $event->user->name : 'Système',
                'role' => $event->user && $event->user->role ? $event->user->role->name : 'N/A',
                'agency_name' => $event->agency ? $event->agency->name : 'Siège général',
                'agency_id' => $event->agency_id,
            ];
        });

        return response()->json($evenements);
    }
}
