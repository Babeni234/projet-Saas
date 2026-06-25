<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with([
            'property',
            'lastMessage.user',
            'participants.user',
        ])
            ->whereHas('participants', fn($q) => $q->where('user_id', auth()->id()))
            ->orderBy('last_message_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Landlord/Messages/Index', ['conversations' => $conversations]);
    }

    public function show(Conversation $conversation)
    {
        $isParticipant = $conversation->participants()
            ->where('user_id', auth()->id())
            ->exists();

        if (!$isParticipant) abort(403);

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $conversation->load(['property', 'participants.user', 'creator']);

        Message::where('conversation_id', $conversation->id)
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $participant = $conversation->participants()
            ->where('user_id', auth()->id())
            ->first();
        if ($participant) {
            $participant->update(['last_read_at' => now()]);
        }

        return Inertia::render('Landlord/Messages/Show', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        $tenant = Tenant::findOrFail($data['tenant_id']);

        $conversation = Conversation::create([
            'subject' => $data['subject'],
            'user_id' => auth()->id(),
            'last_message_at' => now(),
        ]);

        ConversationParticipant::insert([
            ['conversation_id' => $conversation->id, 'user_id' => auth()->id(), 'created_at' => now(), 'updated_at' => now()],
            ['conversation_id' => $conversation->id, 'user_id' => $tenant->user_id, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'content' => $data['content'],
        ]);

        return redirect()->route('landlord.messages.show', $conversation->id)
            ->with('success', 'Message envoyé.');
    }

    public function reply(Request $request, Conversation $conversation)
    {
        $isParticipant = $conversation->participants()
            ->where('user_id', auth()->id())
            ->exists();

        if (!$isParticipant) abort(403);

        $data = $request->validate([
            'content' => 'required|string',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'content' => $data['content'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return redirect()->route('landlord.messages.show', $conversation->id);
    }

    public function create()
    {
        $tenants = Tenant::whereHas('contracts', fn($q) => $q->whereHas('property', fn($p) => $p->where('user_id', auth()->id())))
            ->get();

        return Inertia::render('Landlord/Messages/Create', ['tenants' => $tenants]);
    }
}
