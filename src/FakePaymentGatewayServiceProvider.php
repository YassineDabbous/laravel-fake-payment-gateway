<?php

namespace YassineDabbous\FakePaymentGateway;

use Illuminate\Support\ServiceProvider;

class FakePaymentGatewayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 1. Load config
        $this->mergeConfigFrom(__DIR__.'/../config/fake-gateway.php', 'fake-gateway');

        // 2. Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        if (config('fake-gateway.enable_demo_routes')) {
            $this->loadRoutesFrom(__DIR__.'/../routes/demo.php');
        }

        // 3. Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'fake-payment-gateway');


        // 4. Make resources publishable
        if ($this->app->runningInConsole()) {
            // Publish config file
            $this->publishes([
                __DIR__.'/../config/fake-gateway.php' => config_path('fake-gateway.php'),
            ], 'fake-gateway-config');

            // Publish views
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/fake-payment-gateway'),
            ], 'fake-gateway-views');
        }
    }
}