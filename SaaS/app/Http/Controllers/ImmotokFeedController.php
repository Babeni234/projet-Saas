<?php

namespace App\Http\Controllers;

use App\Models\Illustration;
use App\Models\ImmotokClient;
use App\Models\ImmotokLike;
use App\Models\ImmotokFavorite;
use App\Models\ImmotokComment;
use App\Models\Logement;
use App\Models\Batiment;
use App\Models\Evenement;
use App\Models\ImmotokNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImmotokFeedController extends Controller
{
    protected function getClient(Request $request)
    {
        if ($request->attributes->has('immotok_client')) {
            return $request->attributes->get('immotok_client');
        }

        // Try to get token from Authorization header for optional auth routes
        $token = $request->bearerToken();
        if ($token) {
            $client = \App\Models\ImmotokClient::where('api_token', $token)->first();
            if ($client) {
                $request->attributes->set('immotok_client', $client);
                return $client;
            }
        }
        return null;
    }

    protected function getMediaUrl($path)
    {
        if (!$path) {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        return url('/api/immotok/media/' . $path);
    }

    public function getFeed(Request $request)
    {
        $client = $this->getClient($request);
        
        if ($request->filled('random') && $request->random == 1) {
            $query = Illustration::with(['companyProfile', 'agency'])->inRandomOrder();
        } else {
            $query = Illustration::with(['companyProfile', 'agency'])->orderBy('id', 'desc');
        }

        if ($request->filled('tab') && $request->tab === 'subs') {
            if (!$client) {
                return response()->json([]);
            }
            $subscribedCompanyIds = \App\Models\ImmotokSubscription::where('immotok_client_id', $client->id)
                ->pluck('company_profile_id')
                ->toArray();
            $query->whereIn('company_profile_id', $subscribedCompanyIds);
        }

        if ($request->filled('company_id')) {
            $query->where('company_profile_id', $request->company_id);
        }

        // Apply filters
        if ($request->filled('q')) {
            $q = $request->q;
            
            // Find batiment IDs matching city or neighborhood
            $batimentIds = \App\Models\Batiment::where('ville', 'like', "%{$q}%")
                ->orWhere('quartier', 'like', "%{$q}%")
                ->pluck('id')
                ->toArray();
                
            // Find logement IDs matching category name
            $logementIds = \App\Models\Logement::whereHas('categorie', function ($catQuery) use ($q) {
                    $catQuery->where('nom', 'like', "%{$q}%");
                })
                ->pluck('id')
                ->toArray();

            $query->where(function ($sub) use ($q, $batimentIds, $logementIds) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('target_name', 'like', "%{$q}%")
                    ->orWhere(function ($s1) use ($batimentIds) {
                        $s1->where('target_type', 'batiment')
                           ->whereIn('target_id', $batimentIds);
                    })
                    ->orWhere(function ($s2) use ($logementIds) {
                        $s2->where('target_type', 'logement')
                           ->whereIn('target_id', $logementIds);
                    });
            });
        }

        // Filter by Transaction Type or Category
        // Note: Filters join batiments or logements
        $illustrations = $query->get()->map(function ($item) use ($client) {
            $company = $item->companyProfile;
            
            // Build absolute URL for logo
            $logoUrl = null;
            if ($company) {
                $logoUrl = $company->logo_path ? $this->getMediaUrl($company->logo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($company->legal_name) . '&background=random&color=fff';
            }

            // Build media URL
            $mediaUrl = $this->getMediaUrl($item->file_path);

            // Build audio URL
            $audioUrl = $this->getMediaUrl($item->audio_path);

            // Load property metadata
            $propertyDetails = [
                'type' => 'Autre',
                'price' => 0,
                'price_label' => 'Sur demande',
                'surface' => 0,
                'city' => '',
                'neighborhood' => '',
                'transaction' => 'location',
                'rooms' => 0,
                'features' => [],
            ];

            if ($item->target_type === 'logement') {
                $logement = Logement::with(['categorie', 'batiment'])->find($item->target_id);
                if ($logement) {
                    $propertyDetails['type'] = $logement->categorie->nom ?? 'Logement';
                    $propertyDetails['price'] = (float) $logement->loyer;
                    $propertyDetails['price_label'] = number_format($logement->loyer, 0, ',', ' ') . ' FCFA / mois';
                    $propertyDetails['surface'] = $logement->surface ?? 0;
                    $propertyDetails['city'] = $logement->batiment->ville ?? '';
                    $propertyDetails['neighborhood'] = $logement->batiment->quartier ?? '';
                    $propertyDetails['transaction'] = 'location';
                    $propertyDetails['rooms'] = $logement->etage ?? 0;
                }
            } elseif ($item->target_type === 'batiment') {
                $batiment = Batiment::find($item->target_id);
                if ($batiment) {
                    $propertyDetails['type'] = 'Immeuble';
                    $propertyDetails['price'] = 0;
                    $propertyDetails['price_label'] = 'Contactez-nous';
                    $propertyDetails['surface'] = $batiment->surface_totale ?? 0;
                    $propertyDetails['city'] = $batiment->ville ?? '';
                    $propertyDetails['neighborhood'] = $batiment->quartier ?? '';
                    $propertyDetails['transaction'] = 'vente';
                    $propertyDetails['rooms'] = $batiment->etages ?? 0;
                    if ($batiment->ascenseur) $propertyDetails['features'][] = 'Ascenseur';
                    if ($batiment->piscine) $propertyDetails['features'][] = 'Piscine';
                    if ($batiment->gardiennage) $propertyDetails['features'][] = 'Gardiennage';
                    if ($batiment->generatrice) $propertyDetails['features'][] = 'Groupe électrogène';
                }
            }

            $likesCount = ImmotokLike::where('illustration_id', $item->id)->count();
            $favsCount = ImmotokFavorite::where('illustration_id', $item->id)->count();
            $comsCount = ImmotokComment::where('illustration_id', $item->id)->count();

            $hasLiked = $client ? ImmotokLike::where('immotok_client_id', $client->id)->where('illustration_id', $item->id)->exists() : false;
            $hasFavorited = $client ? ImmotokFavorite::where('immotok_client_id', $client->id)->where('illustration_id', $item->id)->exists() : false;
            $hasSubscribed = false;
            if ($client && $company) {
                $hasSubscribed = \App\Models\ImmotokSubscription::where('immotok_client_id', $client->id)
                    ->where('company_profile_id', $company->id)
                    ->exists();
            }

            return [
                'id' => $item->id,
                'description' => $item->description ?? 'Pas de description.',
                'media_url' => $mediaUrl,
                'media_type' => $item->media_type,
                'audio_url' => $audioUrl,
                'company' => [
                    'id' => $company->id ?? null,
                    'name' => $company->legal_name ?? 'Agence Immobilière',
                    'logo' => $logoUrl,
                    'phone' => $company->phone ?? '',
                ],
                'property' => $propertyDetails,
                'likes_count' => $likesCount,
                'favorites_count' => $favsCount,
                'comments_count' => $comsCount,
                'has_liked' => $hasLiked,
                'has_favorited' => $hasFavorited,
                'has_subscribed' => $hasSubscribed,
                'created_at' => $item->created_at->diffForHumans(),
            ];
        });

        // Perform in-memory transaction and type filtering
        if ($request->filled('transaction') && $request->transaction !== 'all') {
            $illustrations = $illustrations->where('property.transaction', $request->transaction);
        }
        if ($request->filled('type') && $request->type !== 'all') {
            $illustrations = $illustrations->filter(function ($item) use ($request) {
                return strtolower($item['property']['type']) === strtolower($request->type);
            });
        }
        if ($request->filled('budget') && (int)$request->budget > 0) {
            $illustrations = $illustrations->filter(function ($item) use ($request) {
                return $item['property']['price'] <= (int)$request->budget;
            });
        }
        if ($request->filled('city')) {
            $city = strtolower($request->city);
            $illustrations = $illustrations->filter(function ($item) use ($city) {
                return str_contains(strtolower($item['property']['city']), $city) || 
                       str_contains(strtolower($item['property']['neighborhood']), $city);
            });
        }
        if ($request->filled('q')) {
            $qLower = strtolower($request->q);
            $illustrations = $illustrations->filter(function ($item) use ($qLower) {
                return str_contains(strtolower($item['description']), $qLower) ||
                       str_contains(strtolower($item['company']['name']), $qLower) ||
                       str_contains(strtolower($item['property']['city']), $qLower) ||
                       str_contains(strtolower($item['property']['neighborhood']), $qLower) ||
                       str_contains(strtolower($item['property']['type']), $qLower) ||
                       str_contains(strtolower($item['property']['price_label']), $qLower);
            });
        }

        if ($request->has('page')) {
            $page = (int) $request->input('page', 1);
            $perPage = (int) $request->input('per_page', 5);
            $sliced = array_slice($illustrations->toArray(), ($page - 1) * $perPage, $perPage);
            return response()->json(array_values($sliced));
        }

        return response()->json(array_values($illustrations->toArray()));
    }

    public function like(Request $request, $id)
    {
        $client = $this->getClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Veuillez vous connecter pour aimer.'], 401);
        }

        $illustration = Illustration::findOrFail($id);

        $like = ImmotokLike::where('immotok_client_id', $client->id)
            ->where('illustration_id', $illustration->id)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            ImmotokLike::create([
                'immotok_client_id' => $client->id,
                'illustration_id' => $illustration->id,
                'company_profile_id' => $illustration->company_profile_id,
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => ImmotokLike::where('illustration_id', $illustration->id)->count()
        ]);
    }

    public function favorite(Request $request, $id)
    {
        $client = $this->getClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Veuillez vous connecter pour sauvegarder.'], 401);
        }

        $illustration = Illustration::findOrFail($id);

        $fav = ImmotokFavorite::where('immotok_client_id', $client->id)
            ->where('illustration_id', $illustration->id)
            ->first();

        if ($fav) {
            $fav->delete();
            $favorited = false;
        } else {
            ImmotokFavorite::create([
                'immotok_client_id' => $client->id,
                'illustration_id' => $illustration->id,
                'company_profile_id' => $illustration->company_profile_id,
            ]);
            $favorited = true;
        }

        return response()->json([
            'success' => true,
            'favorited' => $favorited,
            'favorites_count' => ImmotokFavorite::where('illustration_id', $illustration->id)->count()
        ]);
    }

    public function getComments($id)
    {
        $illustration = Illustration::findOrFail($id);

        $comments = ImmotokComment::with(['client', 'user'])
            ->where('illustration_id', $illustration->id)
            ->whereNull('parent_id')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($comment) {
                return $this->formatComment($comment);
            });

        return response()->json($comments);
    }

    public function comment(Request $request, $id)
    {
        $client = $this->getClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Veuillez vous connecter pour commenter.'], 401);
        }

        $request->validate([
            'text' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:immotok_comments,id',
        ]);

        $illustration = Illustration::findOrFail($id);

        $comment = ImmotokComment::create([
            'immotok_client_id' => $client->id,
            'illustration_id' => $illustration->id,
            'company_profile_id' => $illustration->company_profile_id,
            'parent_id' => $request->parent_id,
            'text' => $request->text,
        ]);

        // Load relationships
        $comment->load(['client']);

        return response()->json([
            'success' => true,
            'comment' => $this->formatComment($comment),
            'comments_count' => ImmotokComment::where('illustration_id', $illustration->id)->count()
        ]);
    }

    public function reserveVisit(Request $request)
    {
        $request->validate([
            'illustration_id' => 'required|exists:illustrations,id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'date' => 'required|date',
            'time' => 'nullable|string',
            'visittype' => 'required|string',
            'message' => 'nullable|string',
        ]);

        $illustration = Illustration::findOrFail($request->illustration_id);

        // We can create an event in the `evenements` table to notify the company/agency!
        // Let's check what fields `evenements` has.
        // Usually, evenements contains: title, description, start_date, etc.
        // Let's save a reservation event
        Evenement::create([
            'company_profile_id' => $illustration->company_profile_id,
            'agency_id' => $illustration->agency_id,
            'titre' => 'ImmoTok : Demande de visite',
            'description' => "Client : " . $request->firstname . " " . $request->lastname . "\nTél : " . $request->phone . "\nEmail : " . $request->email . "\nDate souhaitée : " . $request->date . " " . ($request->time ?? '') . "\nType de visite : " . $request->visittype . "\nMessage : " . $request->message,
            'type' => 'Création',
            'categorie' => 'Locataire',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Votre demande de visite a été transmise avec succès !'
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

        return [
            'id' => $comment->id,
            'name' => $name,
            'avatar' => $avatar ? $this->getMediaUrl($avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=000&color=fff',
            'text' => $comment->text,
            'created_at' => $comment->created_at->diffForHumans(),
            'replies' => $comment->replies ? $comment->replies->map(function ($reply) {
                return $this->formatComment($reply);
            }) : [],
        ];
    }

    public function getCategories()
    {
        $categories = \App\Models\Categorie::where('deleted', false)
            ->orWhereNull('deleted')
            ->pluck('nom')
            ->unique()
            ->values()
            ->toArray();
        return response()->json($categories);
    }

    public function subscribe(Request $request, $id)
    {
        $client = $this->getClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Veuillez vous connecter pour vous abonner.'], 401);
        }

        $sub = \App\Models\ImmotokSubscription::where('immotok_client_id', $client->id)
            ->where('company_profile_id', $id)
            ->first();

        if ($sub) {
            $sub->delete();
            $subscribed = false;
        } else {
            \App\Models\ImmotokSubscription::create([
                'immotok_client_id' => $client->id,
                'company_profile_id' => $id,
            ]);
            $subscribed = true;
        }

        return response()->json([
            'success' => true,
            'subscribed' => $subscribed,
            'subscribers_count' => \App\Models\ImmotokSubscription::where('company_profile_id', $id)->count()
        ]);
    }

    public function getCompanyProfile(Request $request, $id)
    {
        $client = $this->getClient($request);
        $company = \App\Models\CompanyProfile::findOrFail($id);

        $logoUrl = $company->logo_path ? $this->getMediaUrl($company->logo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($company->legal_name) . '&background=random&color=fff';

        $subscribersCount = \App\Models\ImmotokSubscription::where('company_profile_id', $company->id)->count();
        $likesCount = \App\Models\ImmotokLike::where('company_profile_id', $company->id)->count();
        $hasSubscribed = $client ? \App\Models\ImmotokSubscription::where('immotok_client_id', $client->id)->where('company_profile_id', $company->id)->exists() : false;

        $illustrations = Illustration::where('company_profile_id', $company->id)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($item) {
                $mediaUrl = $this->getMediaUrl($item->file_path);
                return [
                    'id' => $item->id,
                    'media_url' => $mediaUrl,
                    'media_type' => $item->media_type,
                    'description' => $item->description,
                ];
            });

        return response()->json([
            'success' => true,
            'company' => [
                'id' => $company->id,
                'name' => $company->legal_name,
                'logo' => $logoUrl,
                'business_type' => $company->business_type,
                'city' => $company->city,
                'country' => $company->country,
                'phone' => $company->phone,
            ],
            'subscribers_count' => $subscribersCount,
            'likes_count' => $likesCount,
            'has_subscribed' => $hasSubscribed,
            'illustrations' => $illustrations,
        ]);
    }
    public function getMyProfile(Request $request)
    {
        $client = $this->getClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Non connecté.'], 401);
        }

        // Get subscribed companies
        $subscriptions = \App\Models\ImmotokSubscription::where('immotok_client_id', $client->id)
            ->with('companyProfile')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($sub) {
                $company = $sub->companyProfile;
                if (!$company) return null;
                $logoUrl = $company->logo_path
                    ? $this->getMediaUrl($company->logo_path)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($company->legal_name) . '&background=random&color=fff';
                return [
                    'id' => $company->id,
                    'name' => $company->legal_name,
                    'logo' => $logoUrl,
                    'business_type' => $company->business_type,
                    'city' => $company->city,
                    'subscribed_at' => $sub->created_at->diffForHumans(),
                ];
            })->filter()->values();

        // Get favorited illustrations
        $favorites = ImmotokFavorite::where('immotok_client_id', $client->id)
            ->with(['illustration.companyProfile'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($fav) {
                $item = $fav->illustration;
                if (!$item) return null;
                $company = $item->companyProfile;
                $mediaUrl = $this->getMediaUrl($item->file_path);
                $logoUrl = null;
                if ($company) {
                    $logoUrl = $company->logo_path
                        ? $this->getMediaUrl($company->logo_path)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($company->legal_name) . '&background=random&color=fff';
                }
                return [
                    'id' => $item->id,
                    'media_url' => $mediaUrl,
                    'media_type' => $item->media_type,
                    'description' => $item->description ?? '',
                    'company_name' => $company->legal_name ?? '',
                    'company_logo' => $logoUrl,
                    'likes_count' => ImmotokLike::where('illustration_id', $item->id)->count(),
                    'favorited_at' => $fav->created_at ? \Carbon\Carbon::parse($fav->created_at)->diffForHumans() : '',
                ];
            })->filter()->values();

        // Get liked illustrations
        $likes = ImmotokLike::where('immotok_client_id', $client->id)
            ->with(['illustration.companyProfile'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($like) {
                $item = $like->illustration;
                if (!$item) return null;
                $company = $item->companyProfile;
                $mediaUrl = $this->getMediaUrl($item->file_path);
                $logoUrl = null;
                if ($company) {
                    $logoUrl = $company->logo_path
                        ? $this->getMediaUrl($company->logo_path)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($company->legal_name) . '&background=random&color=fff';
                }
                return [
                    'id' => $item->id,
                    'media_url' => $mediaUrl,
                    'media_type' => $item->media_type,
                    'description' => $item->description ?? '',
                    'company_name' => $company->legal_name ?? '',
                    'company_logo' => $logoUrl,
                    'likes_count' => ImmotokLike::where('illustration_id', $item->id)->count(),
                    'liked_at' => $like->created_at ? \Carbon\Carbon::parse($like->created_at)->diffForHumans() : '',
                ];
            })->filter()->values();

        return response()->json([
            'success' => true,
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone ?? '',
                'created_at' => $client->created_at->diffForHumans(),
            ],
            'subscriptions' => $subscriptions,
            'favorites' => $favorites,
            'likes' => $likes,
            'stats' => [
                'subscriptions_count' => $subscriptions->count(),
                'favorites_count' => $favorites->count(),
                'likes_count' => $likes->count(),
            ],
        ]);
    }

    public function getNotifications(Request $request)
    {
        $client = $this->getClient($request);
        if (!$client) {
            return response()->json(['success' => false, 'message' => 'Non connecté.'], 401);
        }

        $notifications = ImmotokNotification::where('immotok_client_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'image_url' => $n->image_url,
                    'is_read' => $n->is_read,
                    'created_at' => $n->created_at->diffForHumans(),
                    'company_profile_id' => $n->company_profile_id,
                    'illustration_id' => $n->illustration_id,
                ];
            });

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
        ]);
    }

    public function getUnreadCount(Request $request)
    {
        $client = $this->getClient($request);
        if (!$client) {
            return response()->json(['count' => 0, 'latest' => null]);
        }

        $count = ImmotokNotification::where('immotok_client_id', $client->id)
            ->where('is_read', false)
            ->count();

        $latest = ImmotokNotification::where('immotok_client_id', $client->id)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'count' => $count,
            'latest' => $latest ? $latest->title : null,
        ]);
    }

    public function markNotificationsRead(Request $request)
    {
        $client = $this->getClient($request);
        if (!$client) {
            return response()->json(['success' => false], 401);
        }

        if ($request->input('all') === true) {
            ImmotokNotification::where('immotok_client_id', $client->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } elseif ($request->has('id')) {
            ImmotokNotification::where('immotok_client_id', $client->id)
                ->where('id', $request->id)
                ->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }
}

