<?php

namespace App\Http\Controllers;

use App\Models\PaiementLoyer;
use App\Models\MoisPaye;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaiementLoyerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $query = PaiementLoyer::where('company_profile_id', $companyProfileId)
            ->where('deleted', false)
            ->with(['locataire.user', 'contrat.logement.batiment', 'moisPayes', 'agency', 'company']);

        // Filter by agency if agent employee
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        if ($isAgent) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $payments = $query->orderBy('created_at', 'desc')->get()->map(fn($p) => $this->formatPayment($p));

        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $request->validate([
            'locataire_id'   => 'required|integer|exists:locataires,id',
            'contrat_id'     => 'required|integer|exists:contrats,id',
            'date_reglement' => 'required|date',
            'mode_reglement' => 'required|string',
            'reference_tx'   => 'nullable|string',
            'months'         => 'required|array|min:1',
            'months.*.periode'       => 'required|string',
            'months.*.loyer_de_base' => 'required|numeric',
            'months.*.penalite'      => 'required|numeric',
            'months.*.total_paye'    => 'required|numeric',
        ]);

        $contrat = Contrat::findOrFail($request->input('contrat_id'));
        
        // Deduce agency: use employee's agency if agent, else use contract's agency
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        $agencyId = $isAgent ? $user->employee->agency_id : $contrat->agency_id;

        // Sum up total payments from months
        $monthsInput = $request->input('months');
        $totalAmount = array_reduce($monthsInput, fn($sum, $m) => $sum + (float)$m['total_paye'], 0.0);

        $payment = DB::transaction(function () use ($companyProfileId, $agencyId, $request, $totalAmount, $monthsInput) {
            $p = PaiementLoyer::create([
                'company_profile_id' => $companyProfileId,
                'agency_id'          => $agencyId,
                'locataire_id'       => $request->input('locataire_id'),
                'contrat_id'         => $request->input('contrat_id'),
                'date_reglement'     => $request->input('date_reglement'),
                'montant_total'      => $totalAmount,
                'mode_reglement'     => $request->input('mode_reglement'),
                'reference_tx'       => $request->input('reference_tx'),
                'deleted'            => false,
            ]);

            foreach ($monthsInput as $m) {
                MoisPaye::create([
                    'paiement_loyer_id' => $p->id,
                    'periode'           => $m['periode'],
                    'loyer_de_base'     => $m['loyer_de_base'],
                    'penalite'          => $m['penalite'],
                    'total_paye'        => $m['total_paye'],
                ]);
            }

            return $p;
        });

        $payment->load(['locataire.user', 'contrat.logement.batiment', 'moisPayes', 'agency', 'company']);

        return response()->json($this->formatPayment($payment), 201);
    }

    public function destroy(PaiementLoyer $paiementLoyer)
    {
        $user = Auth::user();
        abort_if($paiementLoyer->company_profile_id !== $user->company_profile_id, 403);

        // Security restriction: Agency employees are not allowed to delete payments
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        abort_if($isAgent, 403, 'Les agences n\'ont pas le droit de supprimer un paiement.');

        $paiementLoyer->update(['deleted' => true]);
        $paiementLoyer->delete();

        return response()->json(['message' => 'Paiement supprimé avec succès.']);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function formatPayment(PaiementLoyer $p): array
    {
        return [
            'id'               => $p->id,
            'uuid'             => $p->uuid,
            'reference'        => $p->reference,
            'company_name'     => $p->company?->legal_name ?? 'Siège Social',
            'agency_id'        => $p->agency_id,
            'agency_name'      => $p->agency?->name ?? 'Siège Social',
            'locataire'        => $p->locataire?->user?->name ?? 'Locataire Supprimé',
            'locataire_id'     => $p->locataire_id,
            'contrat_id'       => $p->contrat_id,
            'contrat_numero'   => $p->contrat?->numero ?? 'N/A',
            'contrat_debut'    => $p->contrat?->debut?->toDateString(),
            'contrat_fin'      => $p->contrat?->fin?->toDateString(),
            'date_reglement'   => $p->date_reglement?->toDateString(),
            'montant_total'    => (float)$p->montant_total,
            'mode_reglement'   => $p->mode_reglement,
            'reference_tx'     => $p->reference_tx,
            'batiment'         => $p->contrat?->logement?->batiment?->nom ?? 'Sans bâtiment',
            'logement'         => $p->contrat?->logement?->reference ?? 'Logement Supprimé',
            'mois_payes'       => $p->moisPayes->map(fn($m) => [
                'periode'       => $m->periode,
                'loyer_de_base' => (float)$m->loyer_de_base,
                'penalite'      => (float)$m->penalite,
                'total_paye'    => (float)$m->total_paye,
            ])->toArray(),
            'created_at'       => $p->created_at?->toDateString(),
        ];
    }
}
