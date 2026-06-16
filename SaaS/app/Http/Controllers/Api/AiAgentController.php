<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AgentConversation;
use App\Services\AiAgentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiAgentController extends Controller
{
    public function message(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'conversation_id' => 'nullable|integer|exists:agent_conversations,id',
            'dashboard_data' => 'nullable|array',
        ]);

        $user = $request->user();
        $dashboardData = $validated['dashboard_data'] ?? [];

        $service = new AiAgentService($user, $dashboardData);
        $result = $service->processMessage(
            $validated['message'],
            $validated['conversation_id'] ?? null
        );

        return response()->json($result);
    }

    public function history(Request $request): JsonResponse
    {
        $conversations = AgentConversation::where('user_id', $request->user()->id)
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'message_count' => count($c->messages ?? []),
                'last_message' => count($c->messages ?? []) > 0
                    ? $c->messages[count($c->messages) - 1]['content'] ?? ''
                    : '',
                'updated_at' => $c->updated_at->diffForHumans(),
            ]);

        return response()->json(['conversations' => $conversations]);
    }

    public function clear(Request $request): JsonResponse
    {
        AgentConversation::where('user_id', $request->user()->id)->delete();

        return response()->json(['success' => true]);
    }

    public function notifications(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = [];

        if (class_exists(\App\Models\Invoice::class)) {
            $invoiceCount = \App\Models\Invoice::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();
            if ($invoiceCount > 0) {
                $notifications[] = [
                    'type' => 'pending_invoices',
                    'message' => "{$invoiceCount} facture(s) en attente de paiement.",
                    'action' => 'navigate_loyer',
                    'severity' => 'warning',
                ];
            }
        }

        $overdue = $request->boolean('is_overdue', false);
        if ($overdue) {
            $notifications[] = [
                'type' => 'overdue',
                'message' => 'Vous avez des loyers impayés avec pénalités.',
                'action' => 'navigate_loyer',
                'severity' => 'error',
            ];
        }

        return response()->json(['notifications' => $notifications, 'timestamp' => now()->toIso8601String()]);
    }

    public function state(Request $request): JsonResponse
    {
        $user = $request->user();
        $state = [
            'user_id' => $user->id,
            'timestamp' => now()->toIso8601String(),
            'changes' => [],
        ];

        if (class_exists(\App\Models\Invoice::class)) {
            $invoiceCount = \App\Models\Invoice::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();
            if ($invoiceCount > 0) {
                $state['changes'][] = [
                    'type' => 'pending_invoices',
                    'message' => "{$invoiceCount} facture(s) en attente.",
                    'action' => 'navigate_loyer',
                ];
            }
        }

        return response()->json($state);
    }
}
