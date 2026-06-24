<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-subscription-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send renewal reminders to companies whose subscription or trial expires in exactly 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $targetDateStart = now()->addDays(7)->startOfDay();
        $targetDateEnd = now()->addDays(7)->endOfDay();

        $this->info("Checking for subscriptions expiring between {$targetDateStart} and {$targetDateEnd}");

        // 1. Find paying active subscriptions expiring in 7 days
        $expiringSubscriptions = UserSubscription::where('status', 'active')
            ->whereBetween('ends_at', [$targetDateStart, $targetDateEnd])
            ->with(['user.company'])
            ->get();

        $this->info("Found " . $expiringSubscriptions->count() . " active subscriptions expiring in 7 days.");

        foreach ($expiringSubscriptions as $sub) {
            $user = $sub->user;
            if ($user && $user->email) {
                try {
                    Mail::send('emails.subscription_reminder', [
                        'user' => $user,
                        'company' => $user->company,
                        'planName' => $sub->plan->name ?? $sub->plan_slug,
                        'endsAt' => $sub->ends_at->format('d/m/Y'),
                        'type' => 'subscription',
                    ], function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject("Rappel : Votre abonnement Property AI expire dans 7 jours");
                    });
                    $this->info("Email sent to {$user->email} for subscription.");
                } catch (\Exception $e) {
                    $this->error("Failed to send email to {$user->email}: " . $e->getMessage());
                }
            }
        }

        // 2. Find trial accounts expiring in 7 days
        $expiringTrials = User::whereBetween('trial_ends_at', [$targetDateStart, $targetDateEnd])
            ->whereNotIn('account_type', ['Super Admin', 'superadmin', 'super_admin'])
            ->with('company')
            ->get();

        $this->info("Found " . $expiringTrials->count() . " trials expiring in 7 days.");

        foreach ($expiringTrials as $user) {
            if ($user->email && !$user->hasActiveSubscription()) {
                try {
                    Mail::send('emails.subscription_reminder', [
                        'user' => $user,
                        'company' => $user->company,
                        'planName' => 'Période d\'essai gratuit',
                        'endsAt' => $user->trial_ends_at->format('d/m/Y'),
                        'type' => 'trial',
                    ], function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject("Rappel : Votre période d'essai Property AI expire dans 7 jours");
                    });
                    $this->info("Email sent to {$user->email} for trial expiration.");
                } catch (\Exception $e) {
                    $this->error("Failed to send trial email to {$user->email}: " . $e->getMessage());
                }
            }
        }

        $this->info('Done!');
    }
}
