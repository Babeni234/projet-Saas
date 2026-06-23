<?php

namespace App\Http\Controllers;

use App\Models\TypeMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypeMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $types = TypeMaintenance::where('company_profile_id', $companyProfileId)
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

        $type = TypeMaintenance::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'] ?? null,
            'company_profile_id' => $user->company_profile_id,
        ]);

        return response()->json($type, 201);
    }

    public function update(Request $request, $id)
    {
        $type = TypeMaintenance::findOrFail($id);
        $this->authorizeCompany($type);

        $validated = $request->validate([
            'nom'         => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $type->update($validated);

        return response()->json($type->fresh());
    }

    public function destroy($id)
    {
        $type = TypeMaintenance::findOrFail($id);
        $this->authorizeCompany($type);

        $type->delete();

        return response()->json(['message' => 'Type de maintenance supprimé avec succès.']);
    }

    private function authorizeCompany(TypeMaintenance $type): void
    {
        $user = Auth::user();
        abort_if(
            $type->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
