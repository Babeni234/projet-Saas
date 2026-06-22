<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use App\Models\Logement;
use App\Models\Locataire;
use App\Models\Agency;
use App\Models\Batiment;

class SubscriptionHelper
{
    /**
     * Get the active user who owns the subscription (the landlord or company owner).
     */
    public static function getSubscriptionOwner(?User $user = null): ?User
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return null;
        }

        // If the user belongs to a company (owner or employee)
        if ($user->company_profile_id) {
            $company = CompanyProfile::find($user->company_profile_id);
            if ($company && $company->user_id) {
                $owner = User::find($company->user_id);
                if ($owner) {
                    return $owner;
                }
            }
        }

        // Fallback: If the user has their own company profile relation directly
        $directCompany = $user->companyProfile;
        if ($directCompany) {
            return $user;
        }

        // Otherwise, the user itself is the owner (e.g. individual landlord)
        return $user;
    }

    /**
     * Get the active subscription plan model.
     */
    public static function getActivePlan(?User $user = null): ?SubscriptionPlan
    {
        $owner = self::getSubscriptionOwner($user);
        if (!$owner) {
            return null;
        }

        $planSlug = $owner->subscription_plan;
        if ($planSlug) {
            $plan = SubscriptionPlan::where('slug', $planSlug)->first();
            if ($plan) {
                return $plan;
            }
        }

        // Fallback default plans based on user type
        $accountType = $owner->account_type === 'company' ? 'company' : 'individual';
        $fallback = SubscriptionPlan::where('account_type', $accountType)->first();
        return $fallback;
    }

    /**
     * Check if a feature limit is reached.
     * Features: 'logements', 'locataires', 'employees', 'agencies', 'buildings', 'ai'
     */
    public static function checkLimit(string $feature, ?User $user = null): bool
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return false;
        }

        // Super admins are never limited
        if (in_array(strtolower(str_replace([' ', '_'], '', $user->account_type)), ['superadmin', 'super_admin'])) {
            return true;
        }

        $plan = self::getActivePlan($user);
        if (!$plan) {
            return true; // No plan details found, allow by default
        }

        $companyProfileId = $user->company_profile_id;

        switch ($feature) {
            case 'logements':
                if ($plan->max_logements === -1) return true;
                $count = Logement::where('company_profile_id', $companyProfileId)->where('deleted', false)->count();
                return $count < $plan->max_logements;

            case 'locataires':
                if ($plan->max_locataires === -1) return true;
                $count = Locataire::where('company_profile_id', $companyProfileId)->count();
                return $count < $plan->max_locataires;

            case 'employees':
                if ($plan->max_employees === -1) return true;
                // Employees are users who have the same company_profile_id and are not the owner
                $owner = self::getSubscriptionOwner($user);
                $ownerId = $owner ? $owner->id : null;
                $count = User::where('company_profile_id', $companyProfileId)
                    ->where('id', '!=', $ownerId)
                    ->count();
                return $count < $plan->max_employees;

            case 'agencies':
                if ($plan->max_agencies === -1) return true;
                $count = Agency::where('company_profile_id', $companyProfileId)->count();
                return $count < $plan->max_agencies;

            case 'buildings':
                if ($plan->max_buildings === -1) return true;
                $count = Batiment::where('company_profile_id', $companyProfileId)->count();
                return $count < $plan->max_buildings;

            case 'ai':
                return (bool)$plan->has_ai;

            default:
                return true;
        }
    }

    /**
     * Check if a feature limit is reached and abort if it is.
     */
    public static function checkOrAbort(string $feature, ?User $user = null): void
    {
        if (!self::checkLimit($feature, $user)) {
            $plan = self::getActivePlan($user);
            $limitStr = '';
            
            if ($plan) {
                switch ($feature) {
                    case 'logements': $limitStr = "la limite de {$plan->max_logements} logements"; break;
                    case 'locataires': $limitStr = "la limite de {$plan->max_locataires} locataires"; break;
                    case 'employees': $limitStr = "la limite de {$plan->max_employees} collaborateurs"; break;
                    case 'agencies': $limitStr = "la limite de {$plan->max_agencies} agences"; break;
                    case 'buildings': $limitStr = "la limite de {$plan->max_buildings} bâtiments"; break;
                    case 'ai': $limitStr = "l'accès aux fonctionnalités IA"; break;
                }
            }

            $message = "Votre forfait actuel ne vous permet pas d'effectuer cette action (limite atteinte ou fonctionnalité non incluse : {$limitStr}). Veuillez mettre à jour votre abonnement.";
            abort(403, $message);
        }
    }
}
