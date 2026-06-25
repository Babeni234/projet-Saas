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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImmotokFeedController extends Controller
{
    protected function getClient()
    {
        if (session()->has('immotok_client_id')) {
            return ImmotokClient::find(session()->get('immotok_client_id'));
        }
        return null;
    }

    public function getFeed(Request $request)
    {
        $client = $this->getClient();
        $query = Illustration::with(['companyProfile', 'agency'])->orderBy('id', 'desc');

        // Apply filters
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('target_name', 'like', "%{$q}%");
            });
        }

        // Filter by Transaction Type or Category
        // Note: Filters join batiments or logements
        $illustrations = $query->get()->map(function ($item) use ($client) {
            $company = $item->companyProfile;
            
            // Build absolute URL for logo
            $logoUrl = null;
            if ($company) {
                $logoUrl = $company->logo_path ? asset('storage/' . $company->logo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($company->legal_name) . '&background=random&color=fff';
            }

            // Build media URL
            $mediaUrl = $item->file_path;
            if (!str_starts_with($mediaUrl, 'http')) {
                $mediaUrl = asset('storage/' . $mediaUrl);
            }

            // Build audio URL
            $audioUrl = $item->audio_path;
            if ($audioUrl && !str_starts_with($audioUrl, 'http')) {
                $audioUrl = asset('storage/' . $audioUrl);
            }

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

        return response()->json(array_values($illustrations->toArray()));
    }

    public function like($id)
    {
        $client = $this->getClient();
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

    public function favorite($id)
    {
        $client = $this->getClient();
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
        $client = $this->getClient();
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
            'avatar' => $avatar ? asset('storage/' . $avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=000&color=fff',
            'text' => $comment->text,
            'created_at' => $comment->created_at->diffForHumans(),
            'replies' => $comment->replies ? $comment->replies->map(function ($reply) {
                return $this->formatComment($reply);
            }) : [],
        ];
    }
}
