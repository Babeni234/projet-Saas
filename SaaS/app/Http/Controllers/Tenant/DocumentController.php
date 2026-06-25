<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DocumentController extends Controller
{
    public function index()
    {
        $tenant = auth('tenant')->user()->tenant;
        $contracts = $tenant->contracts()->with('property')->get();

        $documents = \App\Models\Document::where(function ($q) use ($contracts) {
            foreach ($contracts as $c) {
                $q->orWhere(function ($sub) use ($c) {
                    $sub->where('documentable_type', 'App\Models\Contract')
                        ->where('documentable_id', $c->id);
                });
                $q->orWhere(function ($sub) use ($c) {
                    $sub->where('documentable_type', 'App\Models\Property')
                        ->where('documentable_id', $c->property_id);
                });
            }
        })->with('category')->orderBy('created_at', 'desc')->get();

        return Inertia::render('Tenant/Documents/Index', ['documents' => $documents]);
    }
}
