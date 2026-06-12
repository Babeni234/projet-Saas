<?php

namespace App\Http\Controllers;

use App\Models\TypeEngagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeEngagementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $types = TypeEngagement::where('company_profile_id', $companyProfileId)
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

        $type = TypeEngagement::create([
            ...$validated,
            'company_profile_id' => $user->company_profile_id,
        ]);

        return response()->json($type, 201);
    }

    public function update(Request $request, TypeEngagement $typeEngagement)
    {
        $this->authorizeCompany($typeEngagement);

        $validated = $request->validate([
            'nom'         => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'template'    => 'nullable|string',
        ]);

        $typeEngagement->update($validated);

        return response()->json($typeEngagement->fresh());
    }

    public function destroy(TypeEngagement $typeEngagement)
    {
        $this->authorizeCompany($typeEngagement);

        $typeEngagement->delete();

        return response()->json(['message' => 'Type d\'engagement supprimé avec succès.']);
    }

    private function authorizeCompany(TypeEngagement $typeEngagement): void
    {
        $user = Auth::user();
        abort_if(
            $typeEngagement->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
