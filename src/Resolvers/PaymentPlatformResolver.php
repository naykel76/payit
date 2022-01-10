<?php

namespace Naykel\Payit\Resolvers;

use Naykel\Payit\Models\PaymentPlatform;

class PaymentPlatformResolver
{
    protected $paymentPlatforms;

    public function __construct()
    {
        $this->paymentPlatforms = PaymentPlatform::all();
    }

    public function resolveService($paymentPlatformId)
    {

        // no need to convert 'name' to lower, just use alias
        // convert to lower
        $name = strtolower($this->paymentPlatforms->firstWhere('id', $paymentPlatformId)->name);
        // remove spaces
        $name = str_replace(' ', '', $name);


        $service = config("services.{$name}.class");

        if ($service) {
            return resolve($service);
        }

        throw new \Exception('The selected platform is not in the configuration');
    }
}
