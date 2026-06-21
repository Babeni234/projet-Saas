<?php

namespace App\Console\Commands;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Console\Command;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class SendPushNotifications extends Command
{
    protected $signature = 'app:send-push-notifications';
    protected $description = 'Check penalties and send push notifications to all subscribers';

    public function handle()
    {
        $vapidPublic = env('VAPID_PUBLIC_KEY');
        $vapidPrivate = env('VAPID_PRIVATE_KEY');
        $appUrl = env('APP_URL', 'http://localhost');

        if (!$vapidPublic || !$vapidPrivate) {
            $this->error('VAPID keys not set in .env');
            return 1;
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => $appUrl,
                'publicKey' => $vapidPublic,
                'privateKey' => $vapidPrivate,
            ],
        ]);

        $subscriptions = PushSubscription::with('user')->get();
        if ($subscriptions->isEmpty()) {
            $this->info('No subscriptions to notify.');
            return 0;
        }

        $now = new \DateTime();
        $day = (int) $now->format('j');
        $month = $now->format('m');
        $year = (int) $now->format('Y');

        $sent = 0;

        foreach ($subscriptions as $sub) {
            $user = $sub->user;
            if (!$user) continue;

            $payload = $this->buildPayload($user, $day, $month, $year);
            if (!$payload) continue;

            $notification = [
                'title' => 'HABITATUM — Mise à jour loyer',
                'body' => $payload,
                'icon' => '/favicon.ico',
                'tag' => 'hab-penalty',
                'requireInteraction' => true,
            ];

            try {
                $webPush->queueNotification(
                    Subscription::create([
                        'endpoint' => $sub->endpoint,
                        'authToken' => $sub->auth,
                        'publicKey' => $sub->p256dh,
                        'contentEncoding' => 'aesgcm',
                    ]),
                    json_encode($notification)
                );
                $sent++;
            } catch (\Exception $e) {
                $this->error("Failed to queue for user {$user->id}: {$e->getMessage()}");
            }
        }

        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();
            if (!$report->isSuccess()) {
                $this->warn("Push failed for {$endpoint}: {$report->getReason()}");
                PushSubscription::where('endpoint', $endpoint)->delete();
            }
        }

        $this->info("Sent {$sent} push notifications.");
        return 0;
    }

    private function buildPayload(User $user, int $day, int $month, int $year): ?string
    {
        if ($day <= 10) return null;

        $penaltyRates = [11 => 5, 16 => 10, 21 => 15];
        $currentRate = 0;
        foreach ($penaltyRates as $d => $rate) {
            if ($day >= $d) $currentRate = $rate;
        }

        if ($currentRate === 0) return null;

        $monthName = \DateTime::createFromFormat('!m', $month)->format('F');
        $rent = 185000;
        $penaltyAmount = ($rent * $currentRate) / 100;
        $totalDue = $rent + $penaltyAmount;

        return "Pénalité {$monthName} {$year} : {$currentRate}% ({$penaltyAmount} XAF). Total dû : {$totalDue} XAF. Régularisez au plus vite.";
    }
}
