<?php

namespace App\Http\Controllers;

use App\Models\ImmotokClient;
use App\Models\ImmotokLike;
use App\Models\ImmotokFavorite;
use App\Models\ImmotokComment;
use App\Models\ImmotokMessage;
use App\Models\CompanyProfile;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImmotokDashboardController extends Controller
{
    private function getScope(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;
        
        // Determine if agency portal or employee locked to agency
        $isAgency = $request->is('agence/*') || ($user->employee && $user->employee->agency_id !== null);
        $agencyId = $isAgency && $user->employee ? $user->employee->agency_id : null;

        return [
            'company_profile_id' => $companyProfileId,
            'is_agency' => $isAgency,
            'agency_id' => $agencyId
        ];
    }

    public function getInteractions(Request $request)
    {
        $scope = $this->getScope($request);
        $companyId = $scope['company_profile_id'];
        $agencyId = $scope['agency_id'];

        // 1. Query Comments
        $commentQuery = ImmotokComment::with(['client', 'user', 'illustration'])
            ->where('company_profile_id', $companyId)
            ->whereNull('parent_id');

        if ($scope['is_agency'] && $agencyId) {
            $commentQuery->whereHas('illustration', function ($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            });
        }
        $comments = $commentQuery->orderBy('created_at', 'desc')->get()->map(function ($c) {
            return $this->formatComment($c);
        });

        // 2. Query Likes
        $likeQuery = ImmotokLike::with(['client', 'illustration'])
            ->where('company_profile_id', $companyId);
        
        if ($scope['is_agency'] && $agencyId) {
            $likeQuery->whereHas('illustration', function ($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            });
        }
        $likes = $likeQuery->orderBy('created_at', 'desc')->get()->map(function ($l) {
            $mediaUrl = $l->illustration->file_path;
            if (!str_starts_with($mediaUrl, 'http')) $mediaUrl = asset('storage/' . $mediaUrl);

            return [
                'id' => $l->id,
                'client_name' => $l->client->name ?? 'Client mystère',
                'client_email' => $l->client->email ?? '',
                'illustration' => [
                    'id' => $l->illustration->id,
                    'target_name' => $l->illustration->target_name,
                    'media_url' => $mediaUrl,
                    'media_type' => $l->illustration->media_type,
                ],
                'created_at' => $l->created_at ? $l->created_at->diffForHumans() : '',
            ];
        });

        // 3. Query Favorites
        $favQuery = ImmotokFavorite::with(['client', 'illustration'])
            ->where('company_profile_id', $companyId);
        
        if ($scope['is_agency'] && $agencyId) {
            $favQuery->whereHas('illustration', function ($q) use ($agencyId) {
                $q->where('agency_id', $agencyId);
            });
        }
        $favorites = $favQuery->orderBy('created_at', 'desc')->get()->map(function ($f) {
            $mediaUrl = $f->illustration->file_path;
            if (!str_starts_with($mediaUrl, 'http')) $mediaUrl = asset('storage/' . $mediaUrl);

            return [
                'id' => $f->id,
                'client_name' => $f->client->name ?? 'Client mystère',
                'client_email' => $f->client->email ?? '',
                'illustration' => [
                    'id' => $f->illustration->id,
                    'target_name' => $f->illustration->target_name,
                    'media_url' => $mediaUrl,
                    'media_type' => $f->illustration->media_type,
                ],
                'created_at' => $f->created_at ? $f->created_at->diffForHumans() : '',
            ];
        });

        return response()->json([
            'comments' => $comments,
            'likes' => $likes,
            'favorites' => $favorites,
        ]);
    }

    public function replyComment(Request $request, $id)
    {
        $request->validate(['text' => 'required|string|max:1000']);
        $parent = ImmotokComment::findOrFail($id);

        $reply = ImmotokComment::create([
            'user_id' => Auth::user()->id,
            'illustration_id' => $parent->illustration_id,
            'company_profile_id' => $parent->company_profile_id,
            'parent_id' => $parent->id,
            'text' => $request->text,
        ]);

        $reply->load(['user']);

        return response()->json([
            'success' => true,
            'comment' => $this->formatComment($reply)
        ]);
    }

    public function getChats(Request $request)
    {
        $scope = $this->getScope($request);
        $companyId = $scope['company_profile_id'];
        $agencyId = $scope['agency_id'];

        $query = ImmotokMessage::where('company_profile_id', $companyId);
        if ($scope['is_agency'] && $agencyId) {
            $query->where('agency_id', $agencyId);
        }

        // Fetch distinct clients who messaged
        $clientIds = $query->orderBy('created_at', 'desc')->pluck('immotok_client_id')->unique();

        $chats = [];
        foreach ($clientIds as $id) {
            $client = ImmotokClient::find($id);
            if (!$client) continue;

            $latestQuery = ImmotokMessage::where('immotok_client_id', $id)
                ->where('company_profile_id', $companyId);
            if ($scope['is_agency'] && $agencyId) {
                $latestQuery->where('agency_id', $agencyId);
            }
            $latest = $latestQuery->orderBy('created_at', 'desc')->first();

            $unreadCount = $latestQuery->where('sender', 'client')->where('is_read', false)->count();

            $chats[] = [
                'client' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'email' => $client->email,
                    'phone' => $client->phone,
                    'avatar' => $client->avatar ? asset('storage/' . $client->avatar) : null,
                ],
                'latest_message' => $latest->message ?? '',
                'sender' => $latest->sender ?? '',
                'unread_count' => $unreadCount,
                'created_at' => $latest ? $latest->created_at->diffForHumans() : '',
            ];
        }

        // Retrieve AI Settings state
        $aiEnabled = false;
        if ($scope['is_agency'] && $agencyId) {
            $aiEnabled = (bool) (Agency::find($agencyId)->immotok_ai_enabled ?? false);
        } else {
            $aiEnabled = (bool) (CompanyProfile::find($companyId)->immotok_ai_enabled ?? false);
        }

        return response()->json([
            'chats' => $chats,
            'ai_enabled' => $aiEnabled
        ]);
    }

    public function getChatMessages(Request $request, $clientId)
    {
        $scope = $this->getScope($request);
        $companyId = $scope['company_profile_id'];
        $agencyId = $scope['agency_id'];

        $query = ImmotokMessage::where('immotok_client_id', $clientId)
            ->where('company_profile_id', $companyId);

        if ($scope['is_agency'] && $agencyId) {
            $query->where('agency_id', $agencyId);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        // Mark as read
        $query->where('sender', 'client')->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function replyMessage(Request $request, $clientId)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        $scope = $this->getScope($request);
        $companyId = $scope['company_profile_id'];
        $agencyId = $scope['agency_id'];

        $msg = ImmotokMessage::create([
            'immotok_client_id' => $clientId,
            'company_profile_id' => $companyId,
            'agency_id' => $agencyId,
            'sender' => $scope['is_agency'] ? 'agency' : 'company',
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => $msg
        ]);
    }

    public function toggleAi(Request $request)
    {
        $scope = $this->getScope($request);
        $companyId = $scope['company_profile_id'];
        $agencyId = $scope['agency_id'];

        $request->validate(['enabled' => 'required|boolean']);
        $enabled = (bool) $request->enabled;

        if ($scope['is_agency'] && $agencyId) {
            $agency = Agency::findOrFail($agencyId);
            $agency->update(['immotok_ai_enabled' => $enabled]);
        } else {
            $company = CompanyProfile::findOrFail($companyId);
            $company->update(['immotok_ai_enabled' => $enabled]);
        }

        return response()->json([
            'success' => true,
            'ai_enabled' => $enabled
        ]);
    }

    private function formatComment($comment)
    {
        $name = 'Utilisateur';
        $avatar = null;

        if ($comment->client) {
            $name = $comment->client->name;
            $avatar = $comment->client->avatar;
        } elseif ($comment->user) {
            $name = $comment->user->name . ' (Gestionnaire)';
            $avatar = $comment->user->avatar;
        }

        $mediaUrl = $comment->illustration->file_path ?? '';
        if ($mediaUrl && !str_starts_with($mediaUrl, 'http')) $mediaUrl = asset('storage/' . $mediaUrl);

        return [
            'id' => $comment->id,
            'name' => $name,
            'avatar' => $avatar ? asset('storage/' . $avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=000&color=fff',
            'text' => $comment->text,
            'created_at' => $comment->created_at->diffForHumans(),
            'illustration' => $comment->illustration ? [
                'id' => $comment->illustration->id,
                'target_name' => $comment->illustration->target_name,
                'media_url' => $mediaUrl,
                'media_type' => $comment->illustration->media_type,
            ] : null,
            'replies' => $comment->replies ? $comment->replies->map(function ($reply) {
                return $this->formatComment($reply);
            }) : [],
        ];
    }

    public function getSubscribers(Request $request)
    {
        $scope = $this->getScope($request);
        $companyId = $scope['company_profile_id'];

        $subs = \App\Models\ImmotokSubscription::with('client')
            ->where('company_profile_id', $companyId)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($sub) {
                if (!$sub->client) return null;
                return [
                    'id' => $sub->id,
                    'client' => [
                        'id' => $sub->client->id,
                        'name' => $sub->client->name,
                        'email' => $sub->client->email,
                        'phone' => $sub->client->phone,
                        'avatar' => $sub->client->avatar ? asset('storage/' . $sub->client->avatar) : null,
                    ],
                    'subscribed_at' => $sub->created_at ? \Carbon\Carbon::parse($sub->created_at)->diffForHumans() : '',
                ];
            })->filter()->values();

        return response()->json([
            'subscribers' => $subs,
            'count' => $subs->count(),
        ]);
    }
}
