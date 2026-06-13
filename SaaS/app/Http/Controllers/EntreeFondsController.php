<?php

namespace App\Http\Controllers;

use App\Models\EntreeFonds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntreeFondsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $query = EntreeFonds::where('company_profile_id', $user->company_profile_id)
            ->where('deleted', false)
            ->orderBy('date_entree', 'desc');

        if ($user->employee && $user->employee->agency_id) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_entree' => 'required|date',
            'categorie' => 'nullable|string|max:100',
            'reference' => 'nullable|string|max:100',
            'statut' => 'required|string|in:Encaissé,En attente,Annulé',
        ]);

        $entree = new EntreeFonds($validated);
        $entree->company_profile_id = $user->company_profile_id;
        
        if ($user->employee && $user->employee->agency_id) {
            $entree->agency_id = $user->employee->agency_id;
        }

        $entree->save();

        return response()->json($entree, 201);
    }

    public function update(Request $request, EntreeFonds $entree_fond)
    {
        $user = Auth::user();
        if (!$user || $entree_fond->company_profile_id !== $user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_entree' => 'required|date',
            'categorie' => 'nullable|string|max:100',
            'reference' => 'nullable|string|max:100',
            'statut' => 'required|string|in:Encaissé,En attente,Annulé',
        ]);

        $entree_fond->update($validated);

        return response()->json($entree_fond);
    }

    public function updateStatus(Request $request, EntreeFonds $entree_fond)
    {
        $user = Auth::user();
        if (!$user || $entree_fond->company_profile_id !== $user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'statut' => 'required|string|in:Encaissé,En attente,Annulé',
        ]);

        $entree_fond->update($validated);

        return response()->json($entree_fond);
    }

    public function destroy(EntreeFonds $entree_fond)
    {
        $user = Auth::user();
        if (!$user || $entree_fond->company_profile_id !== $user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $entree_fond->update(['deleted' => true]);
        $entree_fond->delete();

        return response()->json(['message' => 'Supprimé avec succès']);
    }
}
