<?php

namespace App\Http\Controllers;

use App\Models\RegleLoyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegleLoyerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rules = RegleLoyer::where('company_profile_id', $user->company_profile_id)
                           ->orderBy('jour_declenchement', 'asc')
                           ->get();

        return response()->json($rules);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'jour_declenchement' => 'required|integer|min:1|max:31',
            'taux_penalite'      => 'required|numeric|min:0|max:100',
        ]);

        // Eviter les doublons de jour pour la même entreprise
        $existing = RegleLoyer::where('company_profile_id', $user->company_profile_id)
                              ->where('jour_declenchement', $request->input('jour_declenchement'))
                              ->first();

        if ($existing) {
            return response()->json(['message' => 'Une règle existe déjà pour ce jour du mois.'], 422);
        }

        $rule = RegleLoyer::create([
            'company_profile_id' => $user->company_profile_id,
            'jour_declenchement' => $request->input('jour_declenchement'),
            'taux_penalite'      => $request->input('taux_penalite'),
        ]);

        return response()->json($rule, 201);
    }

    public function update(Request $request, RegleLoyer $regleLoyer)
    {
        $user = Auth::user();
        abort_if($regleLoyer->company_profile_id !== $user->company_profile_id, 403);

        $request->validate([
            'jour_declenchement' => 'required|integer|min:1|max:31',
            'taux_penalite'      => 'required|numeric|min:0|max:100',
        ]);

        $existing = RegleLoyer::where('company_profile_id', $user->company_profile_id)
                              ->where('jour_declenchement', $request->input('jour_declenchement'))
                              ->where('id', '!=', $regleLoyer->id)
                              ->first();

        if ($existing) {
            return response()->json(['message' => 'Une règle existe déjà pour ce jour du mois.'], 422);
        }

        $regleLoyer->update([
            'jour_declenchement' => $request->input('jour_declenchement'),
            'taux_penalite'      => $request->input('taux_penalite'),
        ]);

        return response()->json($regleLoyer);
    }

    public function destroy(RegleLoyer $regleLoyer)
    {
        $user = Auth::user();
        abort_if($regleLoyer->company_profile_id !== $user->company_profile_id, 403);

        $regleLoyer->delete();

        return response()->json(['message' => 'Règles supprimée avec succès.']);
    }
}
