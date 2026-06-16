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
            'statut' => 'required|string|in:Encaissé,En attente,Annulé',
        ]);

        $entree = new EntreeFonds($validated);
        $entree->company_profile_id = $user->company_profile_id;
        
        if ($user->employee && $user->employee->agency_id) {
            $entree->agency_id = $user->employee->agency_id;
        }

        $entree->save();

        if ($entree->statut === 'Encaissé') {
            \App\Models\Tresorerie::enregistrer(
                $entree,
                (float) $entree->montant,
                "Entrée de fonds : {$entree->titre} (Réf: {$entree->reference})",
                $entree->date_entree ? $entree->date_entree->toDateString() : now()->toDateString()
            );
        }

        return response()->json($entree, 201);
    }

    public function update(Request $request, EntreeFonds $entree_fond)
    {
        $user = Auth::user();
        if (!$user || $entree_fond->company_profile_id !== $user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Cannot update a processed or canceled fund entry
        if ($entree_fond->statut === 'Encaissé' || $entree_fond->statut === 'Annulé') {
            return response()->json(['error' => 'Impossible de modifier une entrée de fonds déjà traitée.'], 403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_entree' => 'required|date',
            'categorie' => 'nullable|string|max:100',
            'statut' => 'required|string|in:Encaissé,En attente,Annulé',
        ]);

        $entree_fond->update($validated);

        if ($entree_fond->statut === 'Encaissé') {
            \App\Models\Tresorerie::enregistrer(
                $entree_fond,
                (float) $entree_fond->montant,
                "Entrée de fonds : {$entree_fond->titre} (Réf: {$entree_fond->reference})",
                $entree_fond->date_entree ? $entree_fond->date_entree->toDateString() : now()->toDateString()
            );
        } else {
            \App\Models\Tresorerie::where('source_type', EntreeFonds::class)
                ->where('source_id', $entree_fond->id)
                ->update(['deleted' => true]);
            \App\Models\Tresorerie::where('source_type', EntreeFonds::class)
                ->where('source_id', $entree_fond->id)
                ->delete();
        }

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

        if ($entree_fond->statut === 'Encaissé') {
            \App\Models\Tresorerie::enregistrer(
                $entree_fond,
                (float) $entree_fond->montant,
                "Entrée de fonds : {$entree_fond->titre} (Réf: {$entree_fond->reference})",
                $entree_fond->date_entree ? $entree_fond->date_entree->toDateString() : now()->toDateString()
            );
        } else {
            \App\Models\Tresorerie::where('source_type', EntreeFonds::class)
                ->where('source_id', $entree_fond->id)
                ->update(['deleted' => true]);
            \App\Models\Tresorerie::where('source_type', EntreeFonds::class)
                ->where('source_id', $entree_fond->id)
                ->delete();
        }

        return response()->json($entree_fond);
    }

    public function destroy(EntreeFonds $entree_fond)
    {
        $user = Auth::user();
        if (!$user || $entree_fond->company_profile_id !== $user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        // Cannot delete a processed or canceled fund entry
        if ($entree_fond->statut === 'Encaissé' || $entree_fond->statut === 'Annulé') {
            return response()->json(['error' => 'Impossible de supprimer une entrée de fonds déjà traitée.'], 403);
        }

        $entree_fond->update(['deleted' => true]);
        $entree_fond->delete();

        \App\Models\Tresorerie::where('source_type', EntreeFonds::class)
            ->where('source_id', $entree_fond->id)
            ->update(['deleted' => true]);
        \App\Models\Tresorerie::where('source_type', EntreeFonds::class)
            ->where('source_id', $entree_fond->id)
            ->delete();

        return response()->json(['message' => 'Supprimé avec succès']);
    }
}
