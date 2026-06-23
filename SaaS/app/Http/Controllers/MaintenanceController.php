<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\TypeMaintenance;
use App\Models\Batiment;
use App\Models\Logement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\GenerationConfig;
use Gemini\Data\Schema;
use Gemini\Enums\DataType;
use Gemini\Enums\ResponseMimeType;

class MaintenanceController extends Controller
{
    /**
     * List all maintenance tickets.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $query = Maintenance::with(['typeMaintenance', 'assignedUser', 'maintenanceable'])
            ->where('company_profile_id', $user->company_profile_id)
            ->orderBy('created_at', 'desc');

        // Scope to agency if user is an agent
        if ($user->employee && $user->employee->agency_id !== null) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $maintenances = $query->get()->map(function($m) {
            // Include readable target information
            $m->target_name = $m->maintenanceable ? ($m->maintenanceable_type === Batiment::class ? $m->maintenanceable->nom : $m->maintenanceable->reference) : 'Inconnu';
            $m->target_type_label = $m->maintenanceable_type === Batiment::class ? 'Bâtiment' : 'Logement';
            return $m;
        });

        return response()->json($maintenances);
    }

    /**
     * Get available assets (Buildings & Lodgings) for maintenance selection.
     */
    public function getTargets(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $batimentsQuery = Batiment::where('company_profile_id', $companyProfileId)->where('deleted', false);
        $logementsQuery = Logement::where('company_profile_id', $companyProfileId)->where('deleted', false);

        if ($user->employee && $user->employee->agency_id !== null) {
            $agencyId = $user->employee->agency_id;
            $batimentsQuery->where('agency_id', $agencyId);
            $logementsQuery->where('agency_id', $agencyId);
        }

        $batiments = $batimentsQuery->orderBy('nom')->get()->map(function($b) {
            return [
                'id' => $b->id,
                'type' => 'batiment',
                'name' => $b->nom . " (" . $b->reference . ")",
                'statut_maintenance' => $b->statut_maintenance,
            ];
        });

        $logements = $logementsQuery->orderBy('reference')->get()->map(function($l) {
            return [
                'id' => $l->id,
                'type' => 'logement',
                'name' => "Logement " . $l->reference . ($l->batiment ? " - " . $l->batiment->nom : ""),
                'statut_maintenance' => $l->statut_maintenance,
            ];
        });

        return response()->json([
            'batiments' => $batiments,
            'logements' => $logements,
        ]);
    }

    /**
     * Get employees having role of Maintenancier.
     */
    public function getMaintenanciers(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $query = User::where('company_profile_id', $companyProfileId)
            ->whereHas('role', function($q) {
                $q->where('slug', 'maintenancier')
                  ->orWhere('name', 'Maintenancier');
            });

        if ($user->employee && $user->employee->agency_id !== null) {
            $query->whereHas('employee', function($q) use ($user) {
                $q->where('agency_id', $user->employee->agency_id);
            });
        }

        $maintenanciers = $query->with('employee')->get()->map(function($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'position' => $u->employee?->position ?? 'Maintenancier',
            ];
        });

        return response()->json($maintenanciers);
    }

    /**
     * Create a new maintenance ticket.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->company_profile_id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'type_maintenance_id' => 'required|integer|exists:type_maintenances,id',
            'description' => 'required|string',
            'priorite' => 'required|string|in:Basse,Normale,Haute,Critique,IA',
            'budget' => 'required|numeric|min:0',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'target_type' => 'required|string|in:batiment,logement',
            'target_id' => 'required|integer',
        ]);

        // Verify Type Maintenance belongs to company
        $type = TypeMaintenance::where('id', $validated['type_maintenance_id'])
            ->where('company_profile_id', $user->company_profile_id)
            ->firstOrFail();

        // Resolve Target
        $targetModel = null;
        $agencyId = null;
        if ($validated['target_type'] === 'batiment') {
            $targetModel = Batiment::where('id', $validated['target_id'])
                ->where('company_profile_id', $user->company_profile_id)
                ->firstOrFail();
            $agencyId = $targetModel->agency_id;
        } else {
            $targetModel = Logement::where('id', $validated['target_id'])
                ->where('company_profile_id', $user->company_profile_id)
                ->firstOrFail();
            $agencyId = $targetModel->agency_id;
        }

        // Scope to agency if user is agent
        if ($user->employee && $user->employee->agency_id !== null) {
            if ($agencyId !== $user->employee->agency_id) {
                return response()->json(['error' => 'La cible n\'appartient pas à votre agence.'], 403);
            }
            $agencyId = $user->employee->agency_id;
        }

        // AI priority logic
        $priority = $validated['priorite'];
        $aiExplanation = null;

        if ($priority === 'IA') {
            $aiResult = $this->determineAiPriority($validated['description'], $type->nom, $validated['target_type']);
            $priority = $aiResult['priorite'];
            $aiExplanation = $aiResult['explanation'];
        }

        // Create ticket
        $maintenance = new Maintenance();
        $maintenance->company_profile_id = $user->company_profile_id;
        $maintenance->agency_id = $agencyId;
        $maintenance->type_maintenance_id = $type->id;
        $maintenance->description = $validated['description'];
        $maintenance->priorite = $priority;
        $maintenance->budget = $validated['budget'];
        $maintenance->statut = 'Créé';
        $maintenance->assigned_to = $validated['assigned_to'] ?? null;
        
        $maintenance->maintenanceable_type = get_class($targetModel);
        $maintenance->maintenanceable_id = $targetModel->id;
        
        $maintenance->save();

        $maintenance->load(['typeMaintenance', 'assignedUser', 'maintenanceable']);
        $maintenance->target_name = $validated['target_type'] === 'batiment' ? $targetModel->nom : $targetModel->reference;
        $maintenance->target_type_label = $validated['target_type'] === 'batiment' ? 'Bâtiment' : 'Logement';
        if ($aiExplanation) {
            $maintenance->ai_explanation = $aiExplanation;
        }

        return response()->json($maintenance, 201);
    }

    /**
     * Start task execution.
     */
    public function startExecution($id)
    {
        $user = Auth::user();
        $maintenance = Maintenance::findOrFail($id);
        $this->authorizeAccess($maintenance);

        if ($maintenance->statut !== 'Créé') {
            return response()->json(['error' => 'La maintenance a déjà débuté ou est terminée.'], 400);
        }

        $maintenance->update([
            'statut' => 'En cours',
            'date_debut_execution' => now(),
        ]);

        return response()->json($maintenance->load(['typeMaintenance', 'assignedUser', 'maintenanceable']));
    }

    /**
     * End task execution.
     */
    public function endExecution($id)
    {
        $user = Auth::user();
        $maintenance = Maintenance::findOrFail($id);
        $this->authorizeAccess($maintenance);

        if ($maintenance->statut !== 'En cours') {
            return response()->json(['error' => 'La maintenance n\'est pas en cours d\'exécution.'], 400);
        }

        $now = now();
        $duration = 0;
        if ($maintenance->date_debut_execution) {
            $duration = (int) $maintenance->date_debut_execution->diffInMinutes($now);
        }

        $maintenance->update([
            'statut' => 'Terminé',
            'date_fin_execution' => $now,
            'duree_execution_minutes' => $duration,
        ]);

        return response()->json($maintenance->load(['typeMaintenance', 'assignedUser', 'maintenanceable']));
    }

    /**
     * Delete a maintenance ticket.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $maintenance = Maintenance::findOrFail($id);
        $this->authorizeAccess($maintenance);

        $maintenance->delete();

        return response()->json(['message' => 'Ticket de maintenance supprimé avec succès.']);
    }

    /**
     * Call Gemini to determine priority or fallback.
     */
    private function determineAiPriority(string $description, string $typeName, string $targetType): array
    {
        $apiKey = config('gemini.api_key');
        
        if (empty($apiKey)) {
            Log::info("Gemini API key not set. Using rule-based fallback priority.");
            return $this->fallbackPriority($description, $typeName);
        }

        try {
            $prompt = "Vous êtes un assistant IA spécialisé dans la gestion immobilière et la maintenance de bâtiments.\n"
                . "Analysez la description de la tâche de maintenance suivante et son type, puis déterminez la priorité recommandée.\n"
                . "La priorité doit obligatoirement être l'une de ces valeurs : 'Basse', 'Normale', 'Haute', 'Critique'.\n"
                . "Donnez également une courte phrase d'explication en français pour justifier votre choix.\n\n"
                . "Type de maintenance : {$typeName}\n"
                . "Description du problème : {$description}\n"
                . "Type d'actif : {$targetType}\n\n"
                . "Retournez uniquement un objet JSON valide avec les clés 'priorite' et 'explanation'. Exemple:\n"
                . "{\n  \"priorite\": \"Haute\",\n  \"explanation\": \"Explication courte\"\n}";

            $response = Gemini::generativeModel(model: config('ai.gemini_model', 'gemini-2.0-flash'))
                ->withGenerationConfig(new GenerationConfig(
                    responseMimeType: ResponseMimeType::APPLICATION_JSON,
                    responseSchema: new Schema(
                        type: DataType::OBJECT,
                        properties: [
                            'priorite' => new Schema(type: DataType::STRING),
                            'explanation' => new Schema(type: DataType::STRING),
                        ],
                        required: ['priorite', 'explanation']
                    )
                ))
                ->generateContent($prompt);

            $result = json_decode($response->text(), true);
            if (isset($result['priorite']) && in_array($result['priorite'], ['Basse', 'Normale', 'Haute', 'Critique'])) {
                return $result;
            }
        } catch (\Exception $e) {
            Log::error("Gemini priority evaluation error: " . $e->getMessage());
        }

        return $this->fallbackPriority($description, $typeName);
    }

    /**
     * Rule-based priority heuristic fallback.
     */
    private function fallbackPriority(string $description, string $typeName): array
    {
        $text = strtolower($description . ' ' . $typeName);
        if (str_contains($text, 'urg') || str_contains($text, 'incendie') || str_contains($text, 'inondation') || str_contains($text, 'fuite d\'eau grave') || str_contains($text, 'gaz')) {
            return [
                'priorite' => 'Critique',
                'explanation' => 'Détecté comme critique basé sur des mots clés de sécurité ou d\'urgence.'
            ];
        }
        if (str_contains($text, 'panne') || str_contains($text, 'fuite') || str_contains($text, 'electric') || str_contains($text, 'électricité') || str_contains($text, 'bloqu') || str_contains($text, 'court-circuit')) {
            return [
                'priorite' => 'Haute',
                'explanation' => 'Priorité Haute définie automatiquement en raison d\'une panne fonctionnelle importante.'
            ];
        }
        if (str_contains($text, 'peinture') || str_contains($text, 'nettoyage') || str_contains($text, 'ampoule') || str_contains($text, 'cosmetique')) {
            return [
                'priorite' => 'Basse',
                'explanation' => 'Priorité Basse définie pour des travaux cosmétiques ou mineurs.'
            ];
        }
        return [
            'priorite' => 'Normale',
            'explanation' => 'Priorité Normale par défaut.'
        ];
    }

    /**
     * Authorize access to maintenance resource.
     */
    private function authorizeAccess(Maintenance $maintenance): void
    {
        $user = Auth::user();
        abort_if(
            $maintenance->company_profile_id !== $user->company_profile_id,
            403,
            'Accès non autorisé.'
        );

        if ($user->employee && $user->employee->agency_id !== null) {
            abort_if(
                $maintenance->agency_id !== $user->employee->agency_id,
                403,
                'Accès non autorisé.'
            );
        }
    }
}
