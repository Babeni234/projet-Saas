<?php

namespace Nangue\Providers;

use Illuminate\Support\ServiceProvider;

class NangueServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $migrationsPath = base_path('nangue/database/migrations');

        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }
}
