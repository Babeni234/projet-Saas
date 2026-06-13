<?php

namespace App\Console\Commands;

use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Console\Command;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class SendPushNotifications extends Command
{
    protected $signature = 'app:send-push-notifications
                            {--date= : Simuler une date (Y-m-d) pour les tests sans changer l\'horloge système}';
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

        $dateOption = $this->option('date');
        if ($dateOption) {
            $now = new \DateTime($dateOption);
            $this->info("Date simulée : {$now->format('Y-m-d')}");
        } else {
            $now = new \DateTime();
        }
        $day = (int) $now->format('j');
        $month = (int) $now->format('m');
        $year = (int) $now->format('Y');

        $sent = 0;

        foreach ($subscriptions as $sub) {
            $user = $sub->user;
            if (!$user) continue;

            $notifications = $this->buildNotifications($user, $day, $month, $year);
            if (empty($notifications)) continue;

            $userTags = [];

            foreach ($notifications as $notif) {
                $tag = $notif['tag'];

                // Éviter un doublon par utilisateur dans le même run
                if (in_array($tag, $userTags)) continue;
                $userTags[] = $tag;

                try {
                    $webPush->queueNotification(
                        Subscription::create([
                            'endpoint' => $sub->endpoint,
                            'authToken' => $sub->auth,
                            'publicKey' => $sub->p256dh,
                            'contentEncoding' => 'aesgcm',
                        ]),
                        json_encode($notif)
                    );
                    $sent++;
                } catch (\Exception $e) {
                    $this->error("Failed to queue for user {$user->id}: {$e->getMessage()}");
                }
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

    private function buildNotifications(User $user, int $day, int $month, int $year): array
    {
        $monthPadded = sprintf('%02d', $month);
        $monthName = \DateTime::createFromFormat('!m', $monthPadded)->format('F');
        $rent = 185000;
        $daysInMonth = (int) (new \DateTime("{$year}-{$monthPadded}-01"))->format('t');
        $notifications = [];

        // Jour 1 : Nouvelle facture
        if ($day === 1) {
            $notifications[] = [
                'title' => 'Nouvelle facture disponible',
                'body' => "La facture de {$monthName} {$year} est disponible. Consultez votre espace factures.",
                'icon' => '/favicon.ico',
                'tag' => 'hab-invoice',
                'requireInteraction' => true,
            ];
        }

        // Jour 5 : Rappel échéance (5 jours avant le 10)
        if ($day === 5) {
            $notifications[] = [
                'title' => 'Rappel échéance loyer',
                'body' => "Le loyer de {$monthName} {$year} (" . number_format($rent, 0, '.', ' ') . " XAF) est à payer avant le 10.",
                'icon' => '/favicon.ico',
                'tag' => 'hab-reminder',
                'requireInteraction' => true,
            ];
        }

        // Fin de mois : rappel du mois suivant
        if ($day >= $daysInMonth - 3 && $day <= $daysInMonth) {
            $nextMonth = $month + 1;
            $nextYear = $year;
            if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }
            $nextMonthName = \DateTime::createFromFormat('!m', sprintf('%02d', $nextMonth))->format('F');
            $notifications[] = [
                'title' => 'Rappel fin de mois',
                'body' => "Le loyer de {$nextMonthName} {$nextYear} (" . number_format($rent, 0, '.', ' ') . " XAF) sera à payer sous peu.",
                'icon' => '/favicon.ico',
                'tag' => 'hab-endmonth',
                'requireInteraction' => true,
            ];
        }

        // Pénalités (11, 16, 21)
        $penaltyRates = [11 => 5, 16 => 10, 21 => 15];
        $currentRate = 0;
        foreach ($penaltyRates as $d => $rate) {
            if ($day >= $d) $currentRate = $rate;
        }

        if ($currentRate > 0) {
            $penaltyAmount = ($rent * $currentRate) / 100;
            $totalDue = $rent + $penaltyAmount;
            $notifications[] = [
                'title' => "Pénalité de {$currentRate}% activée",
                'body' => "Pénalité {$monthName} {$year} : {$currentRate}% (" . number_format($penaltyAmount, 0, '.', ' ') . " XAF). Total dû : " . number_format($totalDue, 0, '.', ' ') . " XAF. Régularisez au plus vite.",
                'icon' => '/favicon.ico',
                'tag' => 'hab-penalty',
                'requireInteraction' => true,
            ];
        }

        return $notifications;
    }
}
