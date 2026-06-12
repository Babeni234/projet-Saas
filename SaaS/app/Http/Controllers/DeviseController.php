<?php

namespace App\Http\Controllers;

use App\Models\Devise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviseController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $devise = Devise::where('company_profile_id', $companyProfileId)->first();

        if (!$devise) {
            return response()->json([
                'devise' => 'France - Euro (€)',
                'code' => 'EUR',
                'symbole' => '€',
                'is_default' => true
            ]);
        }

        return response()->json($devise);
    }

    public function storeOrUpdate(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        $validated = $request->validate([
            'devise'  => 'required|string|max:255',
            'code'    => 'required|string|max:10',
            'symbole' => 'required|string|max:10',
        ]);

        $devise = Devise::updateOrCreate(
            ['company_profile_id' => $companyProfileId],
            [
                'devise'  => $validated['devise'],
                'code'    => $validated['code'],
                'symbole' => $validated['symbole'],
            ]
        );

        return response()->json($devise);
    }
}
