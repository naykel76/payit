<?php

namespace Naykel\Payit\Traits;

use GuzzleHttp\Client;

trait ConsumesExternalServices
{
    // This method makes an HTTP request and returns the response
    public function makeRequest($method, $requestUrl, $queryParams = [], $formParams = [], $headers = [], $isJsonRequest = false)
    {
        // Create a new Guzzle HTTP client with the base URI set to $this->baseUri
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        // If the class using this trait has a resolveAuthorization method, call it
        if (method_exists($this, 'resolveAuthorization')) {
            $this->resolveAuthorization($queryParams, $formParams, $headers);
        }

        // Make the HTTP request
        $response = $client->request($method, $requestUrl, [
            // If $isJsonRequest is true, send the form parameters as JSON, otherwise send them as form parameters
            $isJsonRequest ? 'json' : 'form_params' => $formParams,
            'headers' => $headers,
            'query' => $queryParams,
        ]);

        // Get the contents of the response body
        $response = $response->getBody()->getContents();

        // If the class using this trait has a decodeResponse method, call it
        if (method_exists($this, 'decodeResponse')) {
            $response = $this->decodeResponse($response);
        }

        return $response;
    }
}
