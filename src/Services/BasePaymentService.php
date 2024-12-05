<?php

namespace Naykel\Payit\Services;

use Naykel\Payit\Traits\ConsumesExternalServices;

abstract class BasePaymentService
{
    use ConsumesExternalServices;

    protected string $baseUri;
    protected string $key;
    protected string $secret;

    /**
     * Initialize the payment service with the configuration for the specific service.
     *
     * @param  string  $serviceConfigKey  The key for the specific service in the configuration.
     */
    public function __construct(string $serviceConfigKey)
    {
        $config = config("payit.{$serviceConfigKey}");
        $this->baseUri = $config['base_uri'];
        $this->key = $config['key'];
        $this->secret = $config['secret'];
    }

    abstract public function handlePayment($amount, $currency);
}
