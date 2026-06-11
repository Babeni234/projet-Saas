<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategorieController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $categories = Categorie::where('company_profile_id', $companyProfileId)
            ->where('deleted', false)
            ->orderBy('nom')
            ->get();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nom'         => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $categorie = Categorie::create([
            ...$validated,
            'company_profile_id' => $user->company_profile_id,
        ]);

        return response()->json($categorie, 201);
    }

    public function update(Request $request, Categorie $categorie)
    {
        $this->authorizeCompany($categorie);

        $validated = $request->validate([
            'nom'         => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $categorie->update($validated);

        return response()->json($categorie->fresh());
    }

    public function destroy(Categorie $categorie)
    {
        $this->authorizeCompany($categorie);

        $categorie->update(['deleted' => true]);
        $categorie->delete();

        return response()->json(['message' => 'Catégorie supprimée avec succès.']);
    }

    private function authorizeCompany(Categorie $categorie): void
    {
        $user = Auth::user();
        abort_if(
            $categorie->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );
    }
}
