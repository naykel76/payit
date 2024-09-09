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

        // fetch platform name to be resolved, convert to lower case and strip spaces
        $provider = strtolower($this->paymentPlatforms->firstWhere('id', $paymentPlatformId)->platform_name);
        $provider = str_replace(' ', '', $provider);

        $service = config("services.{$provider}.class");

        if ($service) {
            return resolve($service);
        }

        throw new \Exception('The selected platform is not in the configuration');
    }
}
