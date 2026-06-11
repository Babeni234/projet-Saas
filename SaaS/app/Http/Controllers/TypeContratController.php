<?php

namespace App\Http\Controllers;

use App\Models\TypeContrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeContratController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $types = TypeContrat::where('company_profile_id', $companyProfileId)
            ->where('deleted', false)
            ->orderBy('nom')
            ->get();

        return response()->json($types);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nom'         => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $type = TypeContrat::create([
            ...$validated,
            'company_profile_id' => $user->company_profile_id,
        ]);

        return response()->json($type, 201);
    }

    public function update(Request $request, TypeContrat $typeContrat)
    {
        $this->authorizeCompany($typeContrat);

        $validated = $request->validate([
            'nom'         => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $typeContrat->update($validated);

        return response()->json($typeContrat->fresh());
    }

    public function destroy(TypeContrat $typeContrat)
    {
        $this->authorizeCompany($typeContrat);

        $typeContrat->update(['deleted' => true]);
        $typeContrat->delete();

        return response()->json(['message' => 'Type de contrat supprimé avec succès.']);
    }

    private function authorizeCompany(TypeContrat $typeContrat): void
    {
        $user = Auth::user();
        abort_if(
            $typeContrat->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
