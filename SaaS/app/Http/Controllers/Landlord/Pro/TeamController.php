<?php

namespace App\Http\Controllers\Landlord\Pro;

use App\Http\Controllers\Controller;
use App\Models\TeamInvitation;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $invitations = TeamInvitation::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $members = TeamMember::where('user_id', $userId)
            ->with('member')
            ->get();

        return Inertia::render('Landlord/Pro/Team/Index', [
            'invitations' => $invitations,
            'members' => $members,
        ]);
    }

    public function invite(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
            'role' => 'required|string|in:agent,viewer,manager',
        ]);

        $userId = auth()->id();

        // Check not inviting self
        if ($data['email'] === auth()->user()->email) {
            return back()->withErrors(['email' => 'Vous ne pouvez pas vous inviter vous-même.']);
        }

        // Check if already invited
        $existing = TeamInvitation::where('user_id', $userId)
            ->where('email', $data['email'])
            ->whereNull('accepted_at')
            ->first();

        if ($existing) {
            return back()->withErrors(['email' => 'Une invitation est déjà en cours pour cet email.']);
        }

        // Check if already a member
        $existingMember = TeamMember::where('user_id', $userId)
            ->whereHas('member', fn($q) => $q->where('email', $data['email']))
            ->first();

        if ($existingMember) {
            return back()->withErrors(['email' => 'Cet utilisateur est déjà membre de votre équipe.']);
        }

        TeamInvitation::create([
            'user_id' => $userId,
            'email' => $data['email'],
            'name' => $data['name'],
            'role' => $data['role'],
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);

        return back()->with('success', 'Invitation envoyée à ' . $data['email']);
    }

    public function resend(TeamInvitation $invitation)
    {
        if ($invitation->user_id !== auth()->id()) abort(403);

        $invitation->update([
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);

        return back()->with('success', 'Invitation renvoyée.');
    }

    public function cancelInvitation(TeamInvitation $invitation)
    {
        if ($invitation->user_id !== auth()->id()) abort(403);

        $invitation->delete();

        return back()->with('success', 'Invitation annulée.');
    }

    public function removeMember(TeamMember $member)
    {
        if ($member->user_id !== auth()->id()) abort(403);

        $member->delete();

        return back()->with('success', 'Membre retiré de l\'équipe.');
    }

    public function acceptInvitation(string $token)
    {
        $invitation = TeamInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();

        // Check the authenticated user's email matches
        if (auth()->user()->email !== $invitation->email) {
            return back()->withErrors(['email' => 'Cette invitation ne vous est pas destinée.']);
        }

        TeamMember::create([
            'user_id' => $invitation->user_id,
            'member_id' => auth()->id(),
            'role' => $invitation->role,
        ]);

        $invitation->update(['accepted_at' => now()]);

        return redirect()->route('dashboard')
            ->with('success', 'Vous avez rejoint l\'équipe avec succès.');
    }
}
