<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $tenantUser = auth('tenant')->user();
        $tenant = $tenantUser->tenant;
        $contracts = $tenant->contracts()->with('property')->get();
        $activeContract = $contracts->where('status', 'active')->first();
        $receipts = $tenantUser->tenant->receipts()->with('contract.property')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $incidents = $tenantUser->tenant->incidents()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Tenant/Dashboard', [
            'tenant' => $tenant,
            'activeContract' => $activeContract,
            'receipts' => $receipts,
            'incidents' => $incidents,
        ]);
    }
}
