<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nangue\Support\DemoData;

class LandlordDashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Nangue/Landlord/Dashboard', [
            'stats' => DemoData::landlordStats(),
            'company' => DemoData::company(),
            'properties' => DemoData::properties(),
            'publications' => DemoData::publications(),
            'tenants' => DemoData::tenants(),
            'revenueChart' => DemoData::revenueChart(),
            'alerts' => DemoData::alerts(),
        ]);
    }
}
