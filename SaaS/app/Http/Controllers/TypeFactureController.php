<?php

namespace App\Http\Controllers;

use App\Models\TypeFacture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeFactureController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $types = TypeFacture::where('company_profile_id', $companyProfileId)
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

        $type = TypeFacture::create([
            ...$validated,
            'company_profile_id' => $user->company_profile_id,
        ]);

        return response()->json($type, 201);
    }

    public function update(Request $request, TypeFacture $typeFacture)
    {
        $this->authorizeCompany($typeFacture);

        $validated = $request->validate([
            'nom'         => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $typeFacture->update($validated);

        return response()->json($typeFacture->fresh());
    }

    public function destroy(TypeFacture $typeFacture)
    {
        $this->authorizeCompany($typeFacture);

        $typeFacture->update(['deleted' => true]);
        $typeFacture->delete();

        return response()->json(['message' => 'Type de facture supprimé avec succès.']);
    }

    private function authorizeCompany(TypeFacture $typeFacture): void
    {
        $user = Auth::user();
        abort_if(
            $typeFacture->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
