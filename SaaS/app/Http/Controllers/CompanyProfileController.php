<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CompanyProfile;

class CompanyProfileController extends Controller
{
    /**
     * Update the company profile and associated promoter name.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'legal_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'tax_id' => 'required|string|max:255',
            'country' => 'required|string|size:2',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'legal_representative_name' => 'required|string|max:255',
            'legal_representative_id_number' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'promoter_name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048', // max 2MB
        ]);

        // 1. Update promoter's user name
        $user->update([
            'name' => $validated['promoter_name'],
        ]);

        // 2. Find or create the company profile
        $company = $user->company;
        if (!$company) {
            $company = new CompanyProfile();
            $company->user_id = $user->id;
        }

        // 3. Handle logo upload
        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $company->logo_path = $path;
        }

        // 4. Update the rest of the company profile fields
        $company->fill([
            'legal_name' => $validated['legal_name'],
            'business_type' => $validated['business_type'],
            'registration_number' => $validated['registration_number'],
            'tax_id' => $validated['tax_id'],
            'country' => $validated['country'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'],
            'legal_representative_name' => $validated['legal_representative_name'],
            'legal_representative_id_number' => $validated['legal_representative_id_number'],
            'phone' => $validated['phone'],
        ]);
        
        $company->save();

        // Ensure user's company_profile_id is linked
        if (!$user->company_profile_id) {
            $user->update([
                'company_profile_id' => $company->id,
            ]);
        }

        return redirect()->back()->with('success', 'Profil de l\'entreprise mis à jour avec succès.');
    }
}
