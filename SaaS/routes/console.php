<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Envoyer les notifications push toutes les 30 minutes
Schedule::command('app:send-push-notifications')->everyThirtyMinutes();
