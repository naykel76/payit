<?php

namespace Naykel\Payit\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlatform extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory()
    {
        return \Naykel\Payit\Database\Factories\PaymentPlatformFactory::new();
    }
}
