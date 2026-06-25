<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Ai\AiAssistantService;
use Illuminate\Http\Request;

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
}
