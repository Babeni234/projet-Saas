<?php

namespace App\Http\Controllers;

use App\Models\TypeDepense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeDepenseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $types = TypeDepense::where('company_profile_id', $companyProfileId)
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

        $type = TypeDepense::create([
            ...$validated,
            'company_profile_id' => $user->company_profile_id,
        ]);

        return response()->json($type, 201);
    }

    public function update(Request $request, TypeDepense $typeDepense)
    {
        $this->authorizeCompany($typeDepense);

        $validated = $request->validate([
            'nom'         => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $typeDepense->update($validated);

        return response()->json($typeDepense->fresh());
    }

    public function destroy(TypeDepense $typeDepense)
    {
        $this->authorizeCompany($typeDepense);

        $typeDepense->update(['deleted' => true]);
        $typeDepense->delete();

        return response()->json(['message' => 'Type de dépense supprimé avec succès.']);
    }

    private function authorizeCompany(TypeDepense $typeDepense): void
    {
        $user = Auth::user();
        abort_if(
            $typeDepense->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
