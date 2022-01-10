<?php

namespace Naykel\Payit\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlatform extends Model
{
    protected $fillable = [
        'name',
        'image',
        'subscriptions_enabled',
    ];
}
