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
        $cycle = $request->input('cycle', 'Mensuel');
        $maxDays = 31;
        if ($cycle === 'Trimestriel') $maxDays = 90;
        elseif ($cycle === 'Annuel') $maxDays = 365;

        $request->validate([
            'cycle'              => 'required|string|in:Mensuel,Trimestriel,Annuel',
            'jour_declenchement' => 'required|integer|min:1|max:' . $maxDays,
            'taux_penalite'      => 'required|numeric|min:0|max:100',
        ]);

        // Eviter les doublons de jour pour la même entreprise et le même cycle
        $existing = RegleLoyer::where('company_profile_id', $user->company_profile_id)
                              ->where('cycle', $cycle)
                              ->where('jour_declenchement', $request->input('jour_declenchement'))
                              ->first();

        if ($existing) {
            return response()->json(['message' => 'Une règle existe déjà pour ce jour et ce cycle.'], 422);
        }

        $rule = RegleLoyer::create([
            'company_profile_id' => $user->company_profile_id,
            'cycle'              => $cycle,
            'jour_declenchement' => $request->input('jour_declenchement'),
            'taux_penalite'      => $request->input('taux_penalite'),
        ]);

        return response()->json($rule, 201);
    }

    public function update(Request $request, RegleLoyer $regleLoyer)
    {
        $user = Auth::user();
        abort_if($regleLoyer->company_profile_id !== $user->company_profile_id, 403);

        $cycle = $request->input('cycle', 'Mensuel');
        $maxDays = 31;
        if ($cycle === 'Trimestriel') $maxDays = 90;
        elseif ($cycle === 'Annuel') $maxDays = 365;

        $request->validate([
            'cycle'              => 'required|string|in:Mensuel,Trimestriel,Annuel',
            'jour_declenchement' => 'required|integer|min:1|max:' . $maxDays,
            'taux_penalite'      => 'required|numeric|min:0|max:100',
        ]);

        $existing = RegleLoyer::where('company_profile_id', $user->company_profile_id)
                              ->where('cycle', $cycle)
                              ->where('jour_declenchement', $request->input('jour_declenchement'))
                              ->where('id', '!=', $regleLoyer->id)
                              ->first();

        if ($existing) {
            return response()->json(['message' => 'Une règle existe déjà pour ce jour et ce cycle.'], 422);
        }

        $regleLoyer->update([
            'cycle'              => $cycle,
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
