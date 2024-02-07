<?php

namespace Naykel\Payit\Services;

use Naykel\Payit\Traits\ConsumesExternalServices;

abstract class PaymentService
{
    use ConsumesExternalServices;

    protected string $baseUri;
    protected string $key;
    protected string $secret;
    protected array $plans;

    /**
     * PaymentService constructor.
     *
     * Initializes the payment service with the configuration for the specific service.
     *
     * @param string $serviceConfigKey The key for the specific service in the configuration.
     */
    public function __construct(string $serviceConfigKey)
    {
        $configKeys = $this->mapApiConfigKeys();
        $config = config("services.{$serviceConfigKey}");

        $this->baseUri = $config[$configKeys['base_uri']];
        $this->key = $config[$configKeys['key']];
        $this->secret = $config[$configKeys['secret']];
        $this->plans = $config['plans'];
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    /**
     * Map the keys for a specific service.
     *
     * This method should be implemented by each specific service class to
     * return the correct map of configuration keys.
     *
     * Solves the problem of having different keys for different services.
     *
     * @return array The map of configuration keys for the specific service.
     */
    abstract protected function mapApiConfigKeys(): array;
    abstract protected function resolveAccessToken();
    abstract public function handlePayment($total, $request);
    abstract public function handleApproval();
}
