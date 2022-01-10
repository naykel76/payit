<?php

namespace Naykel\Payit\Database\Seeders;


use Naykel\Payit\Models\PaymentPlatform;
use Illuminate\Database\Seeder;

class PaymentPlatformSeeder extends Seeder
{
    public function run()
    {
        PaymentPlatform::create([
            'name' => 'PayPal',
            'alias' => 'paypal',
            'active' => true,
        ]);
        PaymentPlatform::create([
            'name' => 'Stripe',
            'alias' => 'stripe',
            'active' => true
        ]);
        PaymentPlatform::create([
            'name' => 'Visa',
            'alias' => 'visa',
            'active' => false
        ]);
        PaymentPlatform::create([
            'name' => 'MasterCard',
            'alias' => 'mastercard',
            'active' => false
        ]);
        PaymentPlatform::create([
            'name' => 'American Express',
            'alias' => 'american-express',
            'active' => false
        ]);
        PaymentPlatform::create([
            'name' => 'AfterPay',
            'alias' => 'afterpay',
            'active' => false
        ]);
        PaymentPlatform::create([
            'name' => 'ZipPay',
            'alias' => 'zippay',
            'active' => false
        ]);
        PaymentPlatform::create([
            'name' => 'Direct Deposit',
            'alias' => 'direct-deposit',
            'active' => false
        ]);
    }
}
