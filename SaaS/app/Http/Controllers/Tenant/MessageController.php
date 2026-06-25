<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index()
    {
        $userId = auth('tenant')->user()->tenant->user_id;

        $conversations = Conversation::with([
            'property',
            'lastMessage.user',
            'participants.user',
        ])
            ->whereHas('participants', fn($q) => $q->where('user_id', $userId))
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return Inertia::render('Tenant/Messages/Index', ['conversations' => $conversations]);
    }

    public function show(Conversation $conversation)
    {
        $userId = auth('tenant')->user()->tenant->user_id;

        $isParticipant = $conversation->participants()
            ->where('user_id', $userId)
            ->exists();

        if (!$isParticipant) abort(403);

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $conversation->load(['property', 'participants.user']);

        Message::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return Inertia::render('Tenant/Messages/Show', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    public function reply(Request $request, Conversation $conversation)
    {
        $userId = auth('tenant')->user()->tenant->user_id;

        $isParticipant = $conversation->participants()
            ->where('user_id', $userId)
            ->exists();

        if (!$isParticipant) abort(403);

        $data = $request->validate(['content' => 'required|string']);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $userId,
            'content' => $data['content'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return redirect()->route('tenant.messages.show', $conversation->id);
    }
}
