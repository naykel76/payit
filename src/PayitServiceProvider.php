<?php

namespace Naykel\Payit;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

use Naykel\Payit\View\Components\PaymentOptions;

class PayitServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // $this->commands([InstallCommand::class]); 
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payit');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->registerComponents();

        $this->publishes([
            __DIR__ . '/config/payit.php' => config_path('payit.php'),
        ], 'payit-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/payit.php', 'payit');
    }

    protected function registerComponents()
    {
        Blade::component('payit-payment-options', PaymentOptions::class);
    }
}
