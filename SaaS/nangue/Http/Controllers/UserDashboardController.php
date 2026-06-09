<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Nangue\Support\DemoData;

class UserDashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Nangue/User/Dashboard', [
            'stats' => DemoData::userStats(),
            'properties' => DemoData::properties(),
            'publications' => DemoData::publications(),
            'activities' => DemoData::activities(),
        ]);
    }
}
