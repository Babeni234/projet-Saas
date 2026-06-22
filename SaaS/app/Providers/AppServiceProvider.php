<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Relation::morphMap([
            'Nangue\Models\Property' => \Nangue\Models\Property::class,
            'Nangue\Models\Tenant' => \Nangue\Models\Tenant::class,
            'Nangue\Models\Contract' => \Nangue\Models\Contract::class,
            'Nangue\Models\Incident' => \Nangue\Models\Incident::class,
            'Nangue\Models\Inspection' => \Nangue\Models\Inspection::class,
            'Nangue\Models\Receipt' => \Nangue\Models\Receipt::class,
            'Nangue\Models\Document' => \Nangue\Models\Document::class,
            'Nangue\Models\Subscription' => \Nangue\Models\Subscription::class,
            'Nangue\Models\Deposit' => \Nangue\Models\Deposit::class,
            'Nangue\Models\InsuranceGuarantee' => \Nangue\Models\InsuranceGuarantee::class,
        ]);
    }
}
