<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Receipt;

class ReceiptPdfController extends Controller
{
    public function download(Receipt $receipt)
    {
        $tenant = auth('tenant')->user()->tenant;

        $receipt = $receipt->load('contract.property', 'contract.tenant');
        if ($receipt->contract->tenant_id !== $tenant->id) abort(403);
        if ($receipt->status !== 'paid') abort(403);

        return view('pdf.receipt', ['receipt' => $receipt]);
    }
}
