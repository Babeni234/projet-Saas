<?php

namespace App\Http\Controllers;

use App\Models\Illustration;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\Blob;
use Gemini\Enums\MimeType;

class IllustrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;
        
        $query = Illustration::where('company_profile_id', $companyProfileId);
        
        // Check if we are on the agency portal or if user is locked to an agency
        $isAgency = $request->is('agence/*') || ($user->employee && $user->employee->agency_id !== null);
        
        if ($isAgency) {
            $agencyId = $user->employee->agency_id;
            $query->where('agency_id', $agencyId);
        }

        if ($companyProfileId && !app()->environment('testing')) {
            if (Illustration::where('company_profile_id', $companyProfileId)->count() === 0) {
                $agencyId = $user->employee ? $user->employee->agency_id : null;
                
                // Seed Immeuble A facade image
                Illustration::create([
                    'company_profile_id' => $companyProfileId,
                    'agency_id' => $agencyId,
                    'target_type' => 'batiment',
                    'target_id' => '1',
                    'target_name' => 'Immeuble A',
                    'file_path' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=600&q=80',
                    'file_name' => 'Facade_Immeuble_A.jpg',
                    'media_type' => 'image',
                    'mime_type' => 'image/jpeg',
                    'file_size' => 102400,
                    'description' => 'Vue extérieure principale du bâtiment Immeuble A.',
                ]);

                // Seed APT-A101 living room image
                Illustration::create([
                    'company_profile_id' => $companyProfileId,
                    'agency_id' => $agencyId,
                    'target_type' => 'logement',
                    'target_id' => '1',
                    'target_name' => 'APT-A101',
                    'file_path' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=600&q=80',
                    'file_name' => 'Salon_A101.jpg',
                    'media_type' => 'image',
                    'mime_type' => 'image/jpeg',
                    'file_size' => 153600,
                    'description' => 'Salon spacieux et très lumineux.',
                ]);

                // Seed APT-A101 bedroom image
                Illustration::create([
                    'company_profile_id' => $companyProfileId,
                    'agency_id' => $agencyId,
                    'target_type' => 'logement',
                    'target_id' => '1',
                    'target_name' => 'APT-A101',
                    'file_path' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=600&q=80',
                    'file_name' => 'Chambre_A101.jpg',
                    'media_type' => 'image',
                    'mime_type' => 'image/jpeg',
                    'file_size' => 128000,
                    'description' => 'Chambre à coucher chaleureuse.',
                ]);

                // Seed APT-A101 virtual tour video
                Illustration::create([
                    'company_profile_id' => $companyProfileId,
                    'agency_id' => $agencyId,
                    'target_type' => 'logement',
                    'target_id' => '1',
                    'target_name' => 'APT-A101',
                    'file_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
                    'file_name' => 'Visite_Virtuelle_A101.mp4',
                    'media_type' => 'video',
                    'mime_type' => 'video/mp4',
                    'file_size' => 2048000,
                    'description' => 'Vidéo de présentation et visite virtuelle.',
                ]);
            }
        }

        $illustrations = $query->orderBy('created_at', 'desc')->get();

        // Get list of agencies for dropdown (only relevant for enterprise side)
        $agencies = Agency::where('company_profile_id', $companyProfileId)->get();

        if ($request->is('agence/*')) {
            $agency = Agency::with('companyProfile')->find($user->employee->agency_id);
            return Inertia::render('agence/Dashboard/Dashboard', [
                'agency' => $agency,
                'initialRoute' => 'immobilier/illustrations',
                'illustrations' => $illustrations,
            ]);
        }

        return Inertia::render('entreprise/Dashboard/Dashboard', [
            'initialRoute' => 'immobilier/illustrations',
            'illustrations' => $illustrations,
            'agencies' => $agencies,
        ]);
    }

    /**
     * Return illustrations as JSON for SPA AJAX calls.
     */
    public function fetchJson(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $query = Illustration::where('company_profile_id', $companyProfileId);

        $isAgency = $request->is('agence/*') || ($user->employee && $user->employee->agency_id !== null);

        if ($isAgency) {
            $agencyId = $user->employee->agency_id;
            $query->where('agency_id', $agencyId);
        }

        $illustrations = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'illustrations' => $illustrations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'target_type' => 'required|string|in:batiment,logement',
            'target_id' => 'required|string',
            'target_name' => 'required|string',
            'agency_id' => 'nullable|integer',
            'photos' => 'nullable|array|max:100',
            'photos.*' => 'required|file|image|mimes:jpg,jpeg,png,gif,webp,svg|max:10240', // 10MB max per photo
            'videos' => 'nullable|array|max:5',
            'videos.*' => 'required|file|mimes:mp4,mov,avi,webm,ogg|max:51200', // 50MB max per video
            'description' => 'nullable|string',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,aac,m4a,mpga,wma|max:10240', // 10MB max for audio
        ]);

        if (!$request->hasFile('photos') && !$request->hasFile('videos')) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'photos' => 'Veuillez sélectionner au moins une photo ou une vidéo.',
            ]);
        }

        $companyProfileId = $user->company_profile_id;
        
        // If agency employee, force their agency_id
        $agencyId = $request->input('agency_id');
        if ($user->employee && $user->employee->agency_id !== null) {
            $agencyId = $user->employee->agency_id;
        }

        $audioPath = null;
        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store('illustrations/audio', 'public');
        }

        $uploadedCount = 0;

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('illustrations', 'public');
                Illustration::create([
                    'company_profile_id' => $companyProfileId,
                    'agency_id' => $agencyId,
                    'target_type' => $request->input('target_type'),
                    'target_id' => $request->input('target_id'),
                    'target_name' => $request->input('target_name'),
                    'file_path' => $path,
                    'audio_path' => $audioPath,
                    'file_name' => $file->getClientOriginalName(),
                    'media_type' => 'image',
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'description' => $request->input('description'),
                ]);
                $uploadedCount++;
            }
        }

        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $file) {
                $path = $file->store('illustrations', 'public');
                Illustration::create([
                    'company_profile_id' => $companyProfileId,
                    'agency_id' => $agencyId,
                    'target_type' => $request->input('target_type'),
                    'target_id' => $request->input('target_id'),
                    'target_name' => $request->input('target_name'),
                    'file_path' => $path,
                    'audio_path' => $audioPath,
                    'file_name' => $file->getClientOriginalName(),
                    'media_type' => 'video',
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'description' => $request->input('description'),
                ]);
                $uploadedCount++;
            }
        }

        return redirect()->back()->with('success', "{$uploadedCount} fichier(s) affecté(s) avec succès.");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Illustration $illustration)
    {
        $request->validate([
            'description' => 'nullable|string',
            'target_name' => 'nullable|string',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,aac,m4a,mpga,wma|max:10240',
            'remove_audio' => 'nullable|boolean',
        ]);

        $audioPath = $illustration->audio_path;

        if ($request->boolean('remove_audio')) {
            if ($audioPath && Storage::disk('public')->exists($audioPath)) {
                Storage::disk('public')->delete($audioPath);
            }
            $audioPath = null;
        }

        if ($request->hasFile('audio')) {
            if ($audioPath && Storage::disk('public')->exists($audioPath)) {
                Storage::disk('public')->delete($audioPath);
            }
            $audioPath = $request->file('audio')->store('illustrations/audio', 'public');
        }

        $illustration->update([
            'description' => $request->input('description', $illustration->description),
            'target_name' => $request->input('target_name', $illustration->target_name),
            'audio_path' => $audioPath,
        ]);

        return redirect()->back()->with('success', 'Média mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Illustration $illustration)
    {
        if (Storage::disk('public')->exists($illustration->file_path)) {
            Storage::disk('public')->delete($illustration->file_path);
        }

        if ($illustration->audio_path && Storage::disk('public')->exists($illustration->audio_path)) {
            Storage::disk('public')->delete($illustration->audio_path);
        }

        $illustration->delete();

        return redirect()->back()->with('success', 'Média supprimé de la galerie avec succès.');
    }

    /**
     * Describe media using Gemini AI Vision.
     */
    public function describeMedia(Request $request)
    {
        $request->validate([
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,svg,mp4,mov,avi,webm,ogg|max:30720',
            'illustration_id' => 'nullable|integer|exists:illustrations,id',
        ]);

        $fileContent = null;
        $mimeType = null;
        $fileName = '';

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mimeType = $file->getMimeType();
            $fileContent = $file->get();
            $fileName = $file->getClientOriginalName();
        } elseif ($request->has('illustration_id')) {
            $illustration = Illustration::findOrFail($request->input('illustration_id'));
            
            if (str_starts_with($illustration->file_path, 'http')) {
                try {
                    $fileContent = file_get_contents($illustration->file_path);
                    $mimeType = $illustration->mime_type ?? 'image/jpeg';
                    $fileName = $illustration->file_name;
                } catch (\Exception $urlEx) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de récupérer l\'image distante pour l\'analyse : ' . $urlEx->getMessage()
                    ], 400);
                }
            } else {
                if (!Storage::disk('public')->exists($illustration->file_path)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Le fichier média n\'existe pas sur le serveur.'
                    ], 400);
                }
                $fileContent = Storage::disk('public')->get($illustration->file_path);
                $mimeType = $illustration->mime_type ?? Storage::disk('public')->mimeType($illustration->file_path);
                $fileName = $illustration->file_name;
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez fournir un fichier média ou l\'identifiant d\'une illustration existante.'
            ], 400);
        }

        $isImage = str_starts_with($mimeType, 'image/');
        $apiKey = config('gemini.api_key');

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'La clé API Gemini n\'est pas configurée.'
            ], 400);
        }

        try {
            $geminiMime = MimeType::tryFrom($mimeType) ?? ($isImage ? MimeType::IMAGE_JPEG : MimeType::VIDEO_MP4);

            $prompt = "Tu es un expert en gestion immobilière et marketing. Rédige une description très courte (maximum 2 phrases, environ 15-25 mots), attractive, professionnelle et précise en français pour cette image/vidéo d'un bien immobilier ou d'un bâtiment (par exemple : 'Salon lumineux avec parquet et grandes fenêtres', ou 'Façade moderne d'un immeuble résidentiel'). Décris ce que tu vois de manière valorisante. Réponds DIRECTEMENT avec la description, sans formule de politesse ni introduction.";

            if ($isImage) {
                $response = Gemini::generativeModel(model: config('ai.gemini_model', 'gemini-2.0-flash'))
                    ->generateContent([
                        $prompt,
                        new Blob(
                            mimeType: $geminiMime,
                            data: base64_encode($fileContent),
                        )
                    ]);
                $description = trim($response->text());
            } else {
                try {
                    $response = Gemini::generativeModel(model: config('ai.gemini_model', 'gemini-2.0-flash'))
                        ->generateContent([
                            $prompt,
                            new Blob(
                                mimeType: $geminiMime,
                                data: base64_encode($fileContent),
                            )
                        ]);
                    $description = trim($response->text());
                } catch (\Exception $vidEx) {
                    $cleanName = str_replace(['_', '-'], ' ', pathinfo($fileName, PATHINFO_FILENAME));
                    $description = "Vidéo de présentation montrant " . strtolower($cleanName) . ".";
                }
            }

            return response()->json([
                'success' => true,
                'description' => $description
            ]);
        } catch (\Exception $e) {
            logger()->error("Error generating media description: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'analyse par l\'IA : ' . $e->getMessage()
            ], 500);
        }
    }
}
