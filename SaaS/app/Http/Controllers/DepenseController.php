<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepenseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $query = Depense::with('typeDepense')
            ->where('company_profile_id', $user->company_profile_id)
            ->where('deleted', false)
            ->orderBy('date_depense', 'desc');

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
            'date_depense' => 'required|date',
            'type_depense_id' => 'nullable|integer|exists:type_depenses,id',
            'categorie' => 'nullable|string|max:100',
            'reference' => 'nullable|string|max:100',
            'statut' => 'required|string|in:Payé,En attente,Annulé',
        ]);

        $depense = new Depense($validated);
        $depense->company_profile_id = $user->company_profile_id;
        
        if ($user->employee && $user->employee->agency_id) {
            $depense->agency_id = $user->employee->agency_id;
        }

        $depense->save();

        return response()->json($depense->load('typeDepense'), 201);
    }

    public function update(Request $request, Depense $depense)
    {
        $user = Auth::user();
        if (!$user || $depense->company_profile_id !== $user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'type_depense_id' => 'nullable|integer|exists:type_depenses,id',
            'categorie' => 'nullable|string|max:100',
            'reference' => 'nullable|string|max:100',
            'statut' => 'required|string|in:Payé,En attente,Annulé',
        ]);

        $depense->update($validated);

        return response()->json($depense->load('typeDepense'));
    }

    public function updateStatus(Request $request, Depense $depense)
    {
        $user = Auth::user();
        if (!$user || $depense->company_profile_id !== $user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'statut' => 'required|string|in:Payé,En attente,Annulé',
        ]);

        $depense->update($validated);

        return response()->json($depense);
    }

    public function destroy(Depense $depense)
    {
        $user = Auth::user();
        if (!$user || $depense->company_profile_id !== $user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $depense->update(['deleted' => true]);
        $depense->delete();

        return response()->json(['message' => 'Supprimé avec succès']);
    }
}
