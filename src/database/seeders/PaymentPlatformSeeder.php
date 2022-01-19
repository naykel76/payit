<?php

namespace Naykel\Payit\Database\Seeders;


use Naykel\Payit\Models\PaymentPlatform;
use Illuminate\Database\Seeder;

class PaymentPlatformSeeder extends Seeder
{
    public function run()
    {
        PaymentPlatform::create([
            'platform_name' => 'PayPal', // service and class name
            'method' => 'PayPal', // Display and collapse component name
            'active' => true,
        ]);

        PaymentPlatform::create([
            'platform_name' => 'Stripe',
            'method' => 'Credit Card',
            'active' => true,
        ]);

    }
}
