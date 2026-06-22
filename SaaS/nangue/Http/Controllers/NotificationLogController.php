<?php

namespace Nangue\Http\Controllers;

use App\Http\Controllers\Controller;
use Nangue\Models\NotificationLog;
use Inertia\Inertia;
use Illuminate\Http\Request;

class NotificationLogController extends Controller
{
    public function index()
    {
        $logs = NotificationLog::where('user_id', auth()->id())
            ->latest()->paginate(30);

        return Inertia::render('Landlord/Notifications/Index', [
            'logs' => $logs,
        ]);
    }

    public function resend(NotificationLog $notificationLog)
    {
        $notificationLog->update([
            'status' => 'renvoye',
            'sent_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Notification renvoyée.');
    }
}
