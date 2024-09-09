<?php

namespace Naykel\Payit;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Naykel\Payit\View\Components\PaymentOptions;

class PayitServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // $this->commands([InstallCommand::class]);
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payit');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->registerComponents();

        $this->publishes([
            __DIR__ . '/config/payit.php' => config_path('payit.php'),
        ], 'payit-config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/payit.php', 'payit');
    }

    /**
     * Register the given component.
     */
    protected function registerComponents()
    {
        Blade::component('payit-payment-options', PaymentOptions::class);
    }
}
