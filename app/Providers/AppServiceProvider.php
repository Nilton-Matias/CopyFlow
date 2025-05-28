<?php

namespace App\Providers;

use App\Models\GeneratedText;
use App\Observers\GeneratedTextObserver;
use Illuminate\Support\ServiceProvider;

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
        GeneratedText::observe(GeneratedTextObserver::class);
    }
}
