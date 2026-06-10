<?php

namespace App\Http\Controllers;

use App\Models\Illustration;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
            'media_files' => 'required|array',
            'media_files.*' => 'required|file|mimes:jpg,jpeg,png,gif,webp,svg,mp4,mov,avi,webm,ogg|max:51200', // 50MB max
            'description' => 'nullable|string',
        ]);

        $companyProfileId = $user->company_profile_id;
        
        // If agency employee, force their agency_id
        $agencyId = $request->input('agency_id');
        if ($user->employee && $user->employee->agency_id !== null) {
            $agencyId = $user->employee->agency_id;
        }

        $uploadedCount = 0;

        foreach ($request->file('media_files') as $file) {
            $mime = $file->getMimeType();
            $mediaType = str_starts_with($mime, 'video/') ? 'video' : 'image';
            
            $path = $file->store('illustrations', 'public');
            
            Illustration::create([
                'company_profile_id' => $companyProfileId,
                'agency_id' => $agencyId,
                'target_type' => $request->input('target_type'),
                'target_id' => $request->input('target_id'),
                'target_name' => $request->input('target_name'),
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'media_type' => $mediaType,
                'mime_type' => $mime,
                'file_size' => $file->getSize(),
                'description' => $request->input('description'),
            ]);

            $uploadedCount++;
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
        ]);

        $illustration->update([
            'description' => $request->input('description', $illustration->description),
            'target_name' => $request->input('target_name', $illustration->target_name),
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

        $illustration->delete();

        return redirect()->back()->with('success', 'Média supprimé de la galerie avec succès.');
    }
}
