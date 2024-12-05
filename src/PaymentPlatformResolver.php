<?php

namespace Naykel\Payit;

use Naykel\Payit\Models\PaymentPlatform;

class PaymentPlatformResolver
{
    /**
     * Resolve the payment platform service based on the platform ID.
     *
     * @param  int  $ppid  The ID of the payment platform.
     * @return mixed The resolved service instance, e.g., StripeService or PayPalService.
     *
     * @throws \Exception If the platform is not properly configured in payit.php.
     */
    public function resolveService($ppid)
    {
        // Retrieve the provider name from the PaymentPlatform model
        $provider = PaymentPlatform::findOrFail($ppid)->getProviderName();

        // Retrieve the service class name from the configuration
        $serviceClass = config("payit.{$provider}.class");

        if ($serviceClass && class_exists($serviceClass)) {
            // Instantiate and return the service class defined in the
            // configuration. The service class contains the necessary
            // configuration such as baseUri, clientId, clientSecret.
            return resolve($serviceClass);
        }

        // Throw an exception if the platform is not found in the configuration
        throw new \Exception('The selected platform is not set up in the payit.php configuration file.');
    }
}
