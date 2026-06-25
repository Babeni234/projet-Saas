<?php

namespace App\Http\Controllers\Landlord\Pro;

use App\Http\Controllers\Controller;
use App\Services\Legal\LegalFrameworkService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JurisdictionController extends Controller
{
    public function __construct(
        protected LegalFrameworkService $legalService
    ) {}

    public function index()
    {
        $user = auth()->user();

        $applicableTexts = $this->legalService->getApplicableTextsForUser($user);

        return Inertia::render('Landlord/Pro/Jurisdiction/Index', [
            'current_country' => $user->country ?? 'FR',
            'current_region' => $user->region,
            'countries' => $this->legalService->getAvailableCountries(),
            'legal' => $applicableTexts,
            'is_pro' => $user->is_pro,
        ]);
    }

    public function preview(Request $request)
    {
        $data = $request->validate([
            'country' => 'required|string|size:2',
            'region' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();
        $user->country = $data['country'];
        $user->region = $data['region'];

        $applicableTexts = $this->legalService->getApplicableTextsForUser($user);

        return back()->with([
            'preview' => true,
            'preview_country' => $data['country'],
            'preview_legal' => $applicableTexts,
        ]);
    }

    public function save(Request $request)
    {
        $data = $request->validate([
            'country' => 'required|string|size:2',
            'region' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();
        $user->fill($data)->save();

        return redirect()->route('landlord.pro.jurisdiction.index')
            ->with('success', 'Juridiction mise à jour. L\'application est maintenant adaptée à la législation ' . ($this->legalService->getCountryName($data['country'])) . '.');
    }
}
