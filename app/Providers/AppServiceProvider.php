<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- 1. Agrega esto

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        URL::forceScheme('https'); // <-- 2. Agrega esto
    }
}