<?php

namespace App\Providers;

use Google\Client as GoogleClient;
use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(GoogleClient::class, function ($app) {
            $client = new GoogleClient();
            // Configure the Google API client here (e.g., set scopes, authentication, etc.)
            return $client;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
