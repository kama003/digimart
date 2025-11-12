<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateway
    |--------------------------------------------------------------------------
    |
    | This option controls the default payment gateway that will be used
    | by the application. You may set this to any of the gateways
    | defined in the "gateways" configuration array below.
    |
    */

    'default' => env('PAYMENT_GATEWAY', 'stripe'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | The default currency for all payment transactions.
    | For Stripe: USD, EUR, GBP, etc.
    | For Paytm: INR
    |
    */

    'currency' => env('PAYMENT_CURRENCY', 'usd'),

    /*
    |--------------------------------------------------------------------------
    | Platform Commission
    |--------------------------------------------------------------------------
    |
    | The percentage commission that the platform takes from each sale.
    | This value should be between 0 and 100.
    |
    */

    'commission_percent' => env('PLATFORM_COMMISSION_PERCENT', 10),

    /*
    |--------------------------------------------------------------------------
    | Payment Gateways
    |--------------------------------------------------------------------------
    |
    | Here you may configure the payment gateways for your application.
    | Each gateway has its own configuration options.
    |
    */

    'gateways' => [
        'stripe',
        'paytm',
    ],

    /*
    |--------------------------------------------------------------------------
    | Stripe Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the Stripe payment gateway.
    |
    */

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Paytm Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the Paytm payment gateway.
    |
    */

    'paytm' => [
        'merchant_id' => env('PAYTM_MERCHANT_ID'),
        'merchant_key' => env('PAYTM_MERCHANT_KEY'),
        'website' => env('PAYTM_WEBSITE', 'WEBSTAGING'),
        'environment' => env('PAYTM_ENVIRONMENT', 'production'),
    ],

];
