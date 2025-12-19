<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
{
    URL::forceRootUrl(config('app.url'));
    if (str_starts_with(config('app.url'), 'https://')) {
        URL::forceScheme('https');
    }
}

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
  
}
