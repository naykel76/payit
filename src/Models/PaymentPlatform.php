<?php

namespace Naykel\Payit\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlatform extends Model
{
    /**
     * ------------------------------------------------------------------------
     * IMPORTANT NOTE
     * ------------------------------------------------------------------------
     * The PaymentPlatform model currently retrieves payment platforms from the
     * `payit.php` config file instead of a database. This avoids the need for a
     * database table.
     * 
     * To switch to using a database table, simply update the PaymentPlatform
     * model. No changes are needed in the components or services.
     */

    protected $guarded = [];

    /**
     * Retrieve all payment platforms from the configuration file.
     * 
     * @param array $columns The columns to retrieve 
     * 
     * Note: $columns are not used in this implementation but must be defined to
     * match the parent method and avoid errors
     */

    public static function all($columns = ['*']): \Illuminate\Support\Collection
    {
        $paymentPlatforms = collect(config('payit.payment_platforms'));

        return $paymentPlatforms->map(function ($item) {
            return new static($item);
        });
    }

    public static function active()
    {
        return static::all()->where('active', true);
    }
}
