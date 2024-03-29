<?php

namespace Naykel\Payit\Services;

class StripeService extends PaymentService
{

    public function __construct()
    {
        parent::__construct('stripe');
    }

    protected function mapApiConfigKeys(): array
    {
        return [
            'base_uri' => 'base_uri',
            'key' => 'key',
            'secret' => 'secret'
        ];
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

        // manually set the payment_method to ??
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

    function dollarsToCents(float $value): int
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
