<?php

namespace Naykel\Payit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaymentPlatform extends Model
{
    use \Sushi\Sushi;

    protected $rows = [
        [
            'id' => 1,
            'platform_name' => 'PayPal',
            'method' => 'PayPalApi',
            'label' => 'PayPal',
            // 'service_class' => Naykel\Payit\Services\PayPalService::class,
            'active' => true,
        ],
        [
            'id' => 2,
            'platform_name' => 'StripeApi',
            'method' => 'stripe-card-element',
            'label' => 'Credit Card',
            // 'service_class' => Naykel\Payit\Services\StripeApiService::class,
            'active' => true,
        ],
        [
            'id' => 3,
            'platform_name' => 'Stripe',
            'method' => 'stripe-elements',
            'label' => 'Select Option', // uses options defined in stripe
            // 'service_class' => Naykel\Payit\Services\StripePhpService::class,
            'active' => false,
        ],
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
}
