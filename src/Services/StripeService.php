<?php

namespace Naykel\Payit\Services;

class StripeService extends BasePaymentService
{
    public function __construct()
    {
        parent::__construct('stripe');
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

    public function initiatePayment($total, $request, $currency = 'AUD')
    {
        $intent = $this->createIntent($total, $currency, $request->payment_method);

        session()->put('payment.intentId', $intent->id); // "pi_3QSmI9EVrE67hRSs0HWnqIyp"

        return redirect()->route('payment.approval');
    }

    public function handleApproval()
    {
        if (session()->has('payment.intentId')) {

            $paymentIntentId = session()->get('payment.intentId');

            $confirmation = $this->confirmPayment($paymentIntentId);

            if ($confirmation->status === 'succeeded') {
                $name = $confirmation->charges->data[0]->billing_details->name;
                $currency = strtoupper($confirmation->currency);
                $amount = $confirmation->amount; // value in cents
                $transactionId = $confirmation->charges->data[0]->id;

                session()->put('payment.transactionId', $transactionId);

                // payment success route handles the order processing
                return redirect()->route('payment.success');
            }
        }

        return redirect()->route('checkout')
            ->withErrors('We are unable to confirm your payment. Try again, please');
    }

    public function dollarsToCents(float $value): int
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
                'amount' => $this->dollarsToCents($value),
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
