<?php

namespace Naykel\Payit\Database\Seeders;

use Naykel\Payit\Models\PaymentPlatform;
use Illuminate\Database\Seeder;

class PaymentPlatformSeeder extends Seeder
{
    public function run()
    {
        // the method is used to identify which collapse component to display
        PaymentPlatform::create([
            'platform_name' => 'PayPal', // service and class name
            'method' => 'PayPal', // Platform collapse component and label name
            'active' => true,
        ]);

        PaymentPlatform::create([
            'platform_name' => 'Stripe',
            'method' => 'Credit Card',
            'active' => true,
        ]);
    }
}
