<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
            'keys' => 'required|array',
            'keys.auth' => 'required|string',
            'keys.p256dh' => 'required|string',
        ]);

        $sub = PushSubscription::updateOrCreate(
            ['user_id' => $request->user()->id, 'endpoint' => $validated['endpoint']],
            [
                'endpoint' => $validated['endpoint'],
                'auth' => $validated['keys']['auth'],
                'p256dh' => $validated['keys']['p256dh'],
            ]
        );

        return response()->json(['success' => true, 'subscription' => $sub]);
    }

    public function destroy(Request $request)
    {
        $request->validate(['endpoint' => 'required|string']);
        PushSubscription::where('user_id', $request->user()->id)
            ->where('endpoint', $request->input('endpoint'))
            ->delete();

        return response()->json(['success' => true]);
    }
}
