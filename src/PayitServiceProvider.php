<?php

namespace Naykel\Payit;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;

use Naykel\Payit\View\Components\PaymentOptions;

class PayitServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'payit');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->registerComponents();
    }

    /**
     * Register the given component.
     */
    protected function registerComponents()
    {
        Blade::component('payit-payment-options', PaymentOptions::class);
    }
}
