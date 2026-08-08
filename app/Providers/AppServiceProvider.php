<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ShopifyService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register ShopifyService
        $this->app->singleton(ShopifyService::class, function ($app) {
            return new ShopifyService();
        });

        
    }

    public function boot(): void
    {
        //
    }
}