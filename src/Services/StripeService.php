<?php

namespace Naykel\Payit\Services;

use Naykel\Payit\Traits\ConsumesExternalServices;

class StripeService
{
    use ConsumesExternalServices;

    protected $baseUri;

    protected $key;

    protected $secret;

    protected $plans;

    public function __construct()
    {
        $this->baseUri = config('services.stripe.base_uri');
        $this->key = config('services.stripe.key');
        $this->secret = config('services.stripe.secret');
        $this->plans = config('services.stripe.plans');
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        return "Bearer {$this->secret}";
    }


    public function handlePayment($total, $request, $currency = 'AUD')
    {

        $request->validate([
            'payment_method' => 'required',
        ]);

        $intent = $this->createIntent($total, $currency, $request->payment_method);

        session()->put('payment.paymentIntentId', $intent->id);

        return redirect()->route('payment.approval');
    }

    public function handleApproval()
    {
        if (session()->has('payment.paymentIntentId')) {

            $paymentIntentId = session()->get('payment.paymentIntentId');

            $confirmation = $this->confirmPayment($paymentIntentId);

            // if ($confirmation->status === 'requires_action') {
            //     $clientSecret = $confirmation->client_secret;

            //     return view('stripe.3d-secure')->with([
            //         'clientSecret' => $clientSecret,
            //     ]);
            // }

            if ($confirmation->status === 'succeeded') {
                $name = $confirmation->charges->data[0]->billing_details->name;
                $currency = strtoupper($confirmation->currency);
                $amount = $confirmation->amount; // value in cents
                $transactionId = $confirmation->charges->data[0]->id;

                session()->put('payment.transactionId', $transactionId);

                // payment confirmed route handles the order processing
                return redirect()->route('payment.confirmed');
            }
        }

        return redirect()->route('checkout')
            ->withErrors('We are unable to confirm your payment. Try again, please');
    }

    function dollarsToCents(float $value) : int
    {
        return round($value * 100);
    }

    public function createIntent($value, $currency, $paymentMethod)
    {
        return $this->makeRequest(
            'POST',
            '/v1/payment_intents',
            [],
            [
                'amount' => dollarsToCents($value),
                'currency' => strtolower($currency),
                'payment_method' => $paymentMethod,
                'confirmation_method' => 'manual',
            ],
        );
    }

    public function confirmPayment($paymentIntentId)
    {
        return $this->makeRequest(
            'POST',
            "/v1/payment_intents/{$paymentIntentId}/confirm",
        );
    }

    public function createCustomer($name, $email, $paymentMethod)
    {
        return $this->makeRequest(
            'POST',
            '/v1/customers',
            [],
            [
                'name' => $name,
                'email' => $email,
                'payment_method' => $paymentMethod,
            ],
        );
    }

}
