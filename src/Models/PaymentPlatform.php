<?php

namespace Naykel\Payit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaymentPlatform extends Model
{
    // use \Sushi\Sushi;

    // protected $rows = [
    //     [
    //         'id' => 1,
    //         'platform_name' => 'PayPal',
    //         'method' => 'PayPal',
    //         'active' => true,
    //     ],
    //     [
    //         'id' => 2,
    //         'platform_name' => 'Stripe',
    //         'method' => 'Credit Card',
    //         'active' => true,
    //     ],
    // ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Get the formatted provider name.
     */
    public function getProviderName(): string
    {
        return strtolower(str_replace(' ', '', $this->platform_name));
    }
}
