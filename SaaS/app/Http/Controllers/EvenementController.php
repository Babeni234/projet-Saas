<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvenementController extends Controller
{
    /**
     * Display a listing of real estate events.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        if (!$companyProfileId) {
            return response()->json([]);
        }

        $query = Evenement::where('company_profile_id', $companyProfileId)
            ->with(['user.role', 'agency']);

        // Check if user is restricted to an agency (espace agence)
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        if ($isAgent) {
            $query->where('agency_id', $user->employee->agency_id);
        }

        $evenements = $query->orderBy('created_at', 'desc')->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'titre' => $event->titre,
                'description' => $event->description,
                'type' => $event->type,
                'categorie' => $event->categorie,
                'date' => $event->created_at->toDateString(),
                'heure' => $event->created_at->format('H:i'),
                'utilisateur' => $event->user ? $event->user->name : 'Système',
                'role' => $event->user && $event->user->role ? $event->user->role->name : 'N/A',
                'agency_name' => $event->agency ? $event->agency->name : 'Siège général',
                'agency_id' => $event->agency_id,
            ];
        });

        return response()->json($evenements);
    }

    /**
     * Get users and their connection status for the connected company or agency.
     */
    public function usersConnectionStatus(Request $request)
    {
        $user = Auth::user();
        $companyProfileId = $user->company_profile_id;

        if (!$companyProfileId) {
            return response()->json([]);
        }

        $query = \App\Models\User::where('company_profile_id', $companyProfileId)
            ->with(['role', 'employee.agency']);

        // Check if user is restricted to an agency
        $isAgent = $user->employee && $user->employee->agency_id !== null;
        if ($isAgent) {
            $query->whereHas('employee', function ($q) use ($user) {
                $q->where('agency_id', $user->employee->agency_id);
            });
        }

        $sessionDriver = config('session.driver');
        $activeUserIds = null;
        if ($sessionDriver === 'database') {
            try {
                $activeUserIds = \Illuminate\Support\Facades\DB::table('sessions')
                    ->whereNotNull('user_id')
                    ->where('last_activity', '>=', now()->subMinutes(15)->getTimestamp())
                    ->pluck('user_id')
                    ->all();
            } catch (\Exception $e) {
                // Fallback
            }
        }

        $users = $query->get()->map(function ($u) use ($activeUserIds) {
            $isConnected = (bool) $u->is_connected;
            if (is_array($activeUserIds)) {
                $isConnected = $isConnected && in_array($u->id, $activeUserIds);
            }
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'role' => $u->role ? $u->role->name : 'N/A',
                'agency_name' => $u->employee && $u->employee->agency ? $u->employee->agency->name : 'Siège général',
                'is_connected' => $isConnected,
                'last_login_at' => $u->last_login_at ? $u->last_login_at->toDateTimeString() : 'Jamais',
            ];
        });

        return response()->json($users);
    }

    /**
     * Force log out a user.
     */
    public function forceLogoutUser(Request $request, \App\Models\User $user)
    {
        $currentUser = Auth::user();
        
        // Authorize company profile matching
        if ($user->company_profile_id !== $currentUser->company_profile_id) {
            abort(403, 'Accès non autorisé.');
        }

        // Set flags to trigger forced logout on their next request
        $user->update([
            'must_logout' => true,
            'is_connected' => false,
        ]);

        // Log forced logout action immediately
        \App\Helpers\EventLogger::log(
            "Déconnexion forcée",
            "L'utilisateur {$user->name} a été déconnecté de force par {$currentUser->name}",
            'Déconnexion',
            'Utilisateur',
            $user->employee ? $user->employee->agency_id : null,
            $user->company_profile_id,
            $user->id
        );

        return response()->json(['message' => "L'utilisateur a été déconnecté de force."]);
    }
}
