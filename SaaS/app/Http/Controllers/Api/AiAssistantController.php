<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Ai\AiAssistantService;
use App\Services\Ai\AiTools;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AiAssistantController extends Controller
{
    public function chat(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'nullable|array',
        ]);

        $service = new AiAssistantService(auth()->id(), 'landlord');
        $result = $service->chat($data['message'], $data['history'] ?? []);

        return response()->json([
            'text' => $result['text'],
            'source' => $result['source'],
        ]);
    }

    public function chatStream(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'nullable|array',
        ]);

        $response = new StreamedResponse(function () use ($data) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('X-Accel-Buffering: no');

            $service = new AiAssistantService(auth()->id(), 'landlord');

            $service->chatStream(
                $data['message'],
                $data['history'] ?? [],
                function ($chunk) {
                    echo "data: " . json_encode($chunk) . "\n\n";
                    ob_flush();
                    flush();
                }
            );
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }

    public function tenantChat(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'nullable|array',
        ]);

        $tenantUser = auth('tenant')->user();
        $userId = $tenantUser->tenant->user_id;

        $service = new AiAssistantService($userId, 'tenant');
        $result = $service->chat($data['message'], $data['history'] ?? []);

        return response()->json([
            'text' => $result['text'],
            'source' => $result['source'],
        ]);
    }

    public function tenantChatStream(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:2000',
            'history' => 'nullable|array',
        ]);

        $tenantUser = auth('tenant')->user();
        $userId = $tenantUser->tenant->user_id;

        $response = new StreamedResponse(function () use ($data, $userId) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('X-Accel-Buffering: no');

            $service = new AiAssistantService($userId, 'tenant');

            $service->chatStream(
                $data['message'],
                $data['history'] ?? [],
                function ($chunk) {
                    echo "data: " . json_encode($chunk) . "\n\n";
                    ob_flush();
                    flush();
                }
            );
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }

    /**
     * Generate an analytics report for the dashboard
     */
    public function analytics()
    {
        $userId = auth()->id();

        $tools = new AiTools($userId);
        $result = $tools->execute('generate_report', ['focus' => 'global']);

        if (!$result['success']) {
            return response()->json(['report' => 'Impossible de générer le rapport.'], 500);
        }

        // Also fetch raw data for the dashboard widgets
        $overview = $tools->execute('get_all_overview');
        $finances = $tools->execute('get_finances');
        $alerts = $tools->execute('get_alerts');

        return response()->json([
            'report' => $result['data']['report'],
            'overview' => $overview['success'] ? $overview['data'] : null,
            'finances' => $finances['success'] ? $finances['data'] : null,
            'alerts' => $alerts['success'] ? $alerts['data'] : null,
        ]);
    }
}
