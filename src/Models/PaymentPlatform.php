<?php

namespace Naykel\Payit\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlatform extends Model
{
    use \Sushi\Sushi;

    protected $rows = [
        [
            'id' => 1,
            'platform_name' => 'PayPal',
            'method' => 'PayPal',
            'standalone' => false,
        ],
        [
            'id' => 2,
            'platform_name' => 'Stripe',
            'method' => 'Credit Card',
            'standalone' => false,
        ],
        // Standalone payment platforms makes it easier to create single payment
        // options. For example, a single PayPal button or a single Stripe button.
        [
            'id' => 11,
            'platform_name' => 'PayPal',
            'method' => 'PayPal',
            'standalone' => true,
        ],
        [
            'id' => 12,
            'platform_name' => 'Stripe',
            'method' => 'Credit Card',
            'standalone' => true,
        ],
    ];

    /**
     * Get the formatted provider name.
     */
    public function getProviderName(): string
    {
        return strtolower(str_replace(' ', '', $this->platform_name));
    }
}
