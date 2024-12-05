<?php

return [

    /**
     * ----------------------------------------------------------------------
     * Payment Gateway
     * ----------------------------------------------------------------------
     */
    'return_route' => 'user.account', // route to redirect to after payment

    /**
     * ----------------------------------------------------------------------
     * Third party API settings
     * ----------------------------------------------------------------------
     */
    'paypal' => [
        // don't set default uri here to make sure the user sets it to either
        // sandbox or live in the .env file
        'base_uri' => env('PAYPAL_BASE_URI'),
        'key' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_CLIENT_SECRET'),
        'class' => Naykel\Payit\Services\PayPalService::class,
    ],

    'stripe' => [
        'base_uri' => env('STRIPE_BASE_URI', 'https://api.stripe.com'),
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'class' => Naykel\Payit\Services\StripeService::class,
    ],
];
