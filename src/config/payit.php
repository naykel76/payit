<?php

return [

    /**
     * ----------------------------------------------------------------------
     * Payment Gateway
     * ----------------------------------------------------------------------
     *
     */

    'return_route' => 'user.account', // route to redirect to after payment

    /**
     * ----------------------------------------------------------------------
     * Third party API settings
     * ----------------------------------------------------------------------
     *
     */

    'paypal' => [
        'base_uri' => env('PAYPAL_BASE_URI'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'class' => Naykel\Payit\Services\PayPalService::class,
        'plans' => [
            'monthly' => env('PAYPAL_MONTHLY_PLAN'),
            'yearly' => env('PAYPAL_YEARLY_PLAN'),
        ],
    ],

    'stripe' => [
        'base_uri' => env('STRIPE_BASE_URI'),
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'class' => Naykel\Payit\Services\StripeService::class,
        'plans' => [
            'monthly' => env('STRIPE_MONTHLY_PLAN'),
            'yearly' => env('STRIPE_YEARLY_PLAN'),
        ],
    ],
];
