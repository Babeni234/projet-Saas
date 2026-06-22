<?php

namespace Nangue\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nangue\Models\Conversation;
use Nangue\Models\Message;

class MessageController extends Controller
{
    public function index(): Response
    {
        $conversations = Conversation::where('user_id', auth()->id())
            ->orWhereHas('participants', fn ($q) => $q->where('user_id', auth()->id()))
            ->with(['user', 'messages' => fn ($q) => $q->latest()->limit(1)])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->user->name,
                'avatar' => null,
                'last_message' => $c->messages->first()?->content ?? '',
                'time' => $c->last_message_at?->diffForHumans() ?? $c->created_at->format('H:i'),
                'type' => 'inquiry',
                'property' => $c->property?->title,
                'property_url' => $c->property ? route('immo.property.show', $c->property_id) : null,
                'unread' => $c->messages->whereNull('read_at')->where('user_id', '!=', auth()->id())->count(),
                'online' => false,
            ]);

        return Inertia::render('Nangue/User/Messages', [
            'conversations' => $conversations,
            'selectedConversation' => $conversations->first(),
            'messages' => [],
        ]);
    }

    public function show($id): Response
    {
        $conversation = Conversation::with(['messages' => fn ($q) => $q->with('user')->oldest()])
            ->findOrFail($id);

        Message::where('conversation_id', $id)
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return Inertia::render('Nangue/User/Messages', [
            'conversations' => [],
            'selectedConversation' => [
                'id' => $conversation->id,
                'name' => $conversation->user->name,
                'property' => $conversation->property?->title,
            ],
            'messages' => $conversation->messages->map(fn ($m) => [
                'id' => $m->id,
                'content' => $m->content,
                'time' => $m->created_at->format('H:i'),
                'is_me' => $m->user_id === auth()->id(),
                'read' => (bool) $m->read_at,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'conversation_id' => $validated['conversation_id'],
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        $message->conversation->update(['last_message_at' => now()]);

        return back();
    }

    public function markAsRead($id)
    {
        Message::where('conversation_id', $id)
            ->where('user_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back();
    }
}
