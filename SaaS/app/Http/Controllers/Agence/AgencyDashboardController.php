<?php

namespace App\Http\Controllers\Agence;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgencyDashboardController extends Controller
{
    /**
     * Render Espace Agence main page shell.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->employee || $user->employee->agency_id === null) {
            return redirect()->route('dashboard')->with('error', "Vous n'êtes pas autorisé à accéder à cette agence.");
        }

        $agency = Agency::with('companyProfile')->find($user->employee->agency_id);

        return Inertia::render('agence/Dashboard/Dashboard', [
            'agency' => $agency,
            'initialRoute' => $request->query('route', 'master'),
        ]);
    }
}
