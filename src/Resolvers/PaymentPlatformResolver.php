<?php

namespace Naykel\Payit\Resolvers;

use Exception;
use Naykel\Payit\Models\PaymentPlatform;

class PaymentPlatformResolver
{
    /**
     * Resolve the payment service based on the platform id.
     *
     * @param int $ppid The id of the payment platform.
     * @return \Naykel\Payit\Services\PaymentService The resolved service.
     *
     * @throws \Exception If the platform is not in the configuration.
     */
    public function resolveService(int $ppid)
    {
        $provider = strtolower(PaymentPlatform::firstWhere('id', $ppid)->platform_name);
        $provider = str_replace(' ', '', $provider);

        $service = config("services.{$provider}.class");

        if ($service) {
            return resolve($service);
        }

        throw new \Exception('The selected platform is not in the configuration');
    }
}
