<?php

namespace Innoboxrr\OmniBillingPaypal\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        
        // $this->mergeConfigFrom(__DIR__ . '/../../config/innoboxrromnibillingpaypal.php', 'innoboxrromnibillingpaypal');

    }

    public function boot()
    {
        
        // $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // $this->loadViewsFrom(__DIR__.'/../../resources/views', 'innoboxrromnibillingpaypal');

        if ($this->app->runningInConsole()) {
            
            // $this->publishes([__DIR__.'/../../resources/views' => resource_path('views/vendor/innoboxrromnibillingpaypal'),], 'views');

            // $this->publishes([__DIR__.'/../../config/innoboxrromnibillingpaypal.php' => config_path('innoboxrromnibillingpaypal.php')], 'config');

        }

    }
    
}