<?php

return [

    'return_route' => 'user.account', // route to redirect to after payment

    /**
     * ------------------------------------------------------------------------
     * Third party API settings
     * ------------------------------------------------------------------------
     *
     */

    'paypal' => [
        'base_uri' => env('PAYPAL_BASE_URI'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
    ],

    'stripe' => [
        'base_uri' => env('STRIPE_BASE_URI'),
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
];
