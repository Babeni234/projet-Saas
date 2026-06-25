<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReceiptController extends Controller
{
    public function index()
    {
        $tenant = auth('tenant')->user()->tenant;
        $receipts = $tenant->receipts()
            ->with('contract.property')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return Inertia::render('Tenant/Receipts/Index', ['receipts' => $receipts]);
    }
}
