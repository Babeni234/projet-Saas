<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Services\Ai\AiTools;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $tools = new AiTools($userId);

        $overview = $tools->execute('get_all_overview');
        $finances = $tools->execute('get_finances');
        $alerts = $tools->execute('get_alerts');
        $report = $tools->execute('generate_report', ['focus' => 'global']);

        return Inertia::render('Landlord/Ai/Analytics', [
            'overview' => $overview['success'] ? $overview['data'] : null,
            'finances' => $finances['success'] ? $finances['data'] : null,
            'alerts' => $alerts['success'] ? $alerts['data'] : null,
            'report' => $report['success'] ? $report['data']['report'] : null,
        ]);
    }
}
