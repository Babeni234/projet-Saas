<?php

namespace App\Http\Controllers;

use App\Models\TypeEtatDesLieux;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeEtatDesLieuxController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $types = TypeEtatDesLieux::where('company_profile_id', $companyProfileId)
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
            'template'    => 'nullable|string',
        ]);

        $type = TypeEtatDesLieux::create([
            ...$validated,
            'company_profile_id' => $user->company_profile_id,
        ]);

        return response()->json($type, 201);
    }

    public function update(Request $request, TypeEtatDesLieux $typeEtatDesLieux)
    {
        $this->authorizeCompany($typeEtatDesLieux);

        $validated = $request->validate([
            'nom'         => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'template'    => 'nullable|string',
        ]);

        $typeEtatDesLieux->update($validated);

        return response()->json($typeEtatDesLieux->fresh());
    }

    public function destroy(TypeEtatDesLieux $typeEtatDesLieux)
    {
        $this->authorizeCompany($typeEtatDesLieux);

        $typeEtatDesLieux->delete();

        return response()->json(['message' => 'Type d\'état des lieux supprimé avec succès.']);
    }

    private function authorizeCompany(TypeEtatDesLieux $typeEtatDesLieux): void
    {
        $user = Auth::user();
        abort_if(
            $typeEtatDesLieux->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
