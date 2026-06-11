<?php

namespace App\Http\Controllers;

use App\Models\ContratTemplate;
use App\Models\TypeContrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContratTemplateController extends Controller
{
    /**
     * Liste des templates de contrats.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;
        $companyName = $user->company?->legal_name ?? 'Votre Entreprise';

        $templates = ContratTemplate::where('company_profile_id', $companyProfileId)
            ->where('deleted', false)
            ->with('typeContrat')
            ->get()
            ->map(function ($tpl) use ($companyName) {
                return [
                    'id'              => $tpl->id,
                    'type_contrat_id' => $tpl->type_contrat_id,
                    'type_contrat_nom'=> $tpl->typeContrat?->nom ?? 'Inconnu',
                    'content'         => $tpl->content,
                    'company_name'    => $companyName,
                ];
            });

        return response()->json([
            'templates'    => $templates,
            'company_name' => $companyName,
        ]);
    }

    /**
     * Enregistre ou met à jour un template pour un type de contrat donné.
     */
    public function storeOrUpdate(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;
        $companyName = $user->company?->legal_name ?? 'Votre Entreprise';

        $request->validate([
            'type_contrat_id' => 'required|integer|exists:type_contrats,id',
            'content'         => 'required|string',
        ]);

        $typeContratId = $request->input('type_contrat_id');

        // Check ownership of TypeContrat
        TypeContrat::where('id', $typeContratId)
            ->where('company_profile_id', $companyProfileId)
            ->firstOrFail();

        $template = ContratTemplate::updateOrCreate(
            [
                'company_profile_id' => $companyProfileId,
                'type_contrat_id'    => $typeContratId,
            ],
            [
                'content' => $request->input('content'),
                'deleted' => false,
            ]
        );

        return response()->json([
            'success'         => true,
            'template'        => [
                'id'              => $template->id,
                'type_contrat_id' => $template->type_contrat_id,
                'content'         => $template->content,
                'company_name'    => $companyName,
            ]
        ]);
    }
}
