<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Arsip;
use App\Observers\ArsipObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the ArsipObserver
        Arsip::observe(ArsipObserver::class);
    }
}
