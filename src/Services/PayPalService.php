<?php

namespace Naykel\Payit\Services;

use Naykel\Payit\Traits\ConsumesExternalServices;

class PayPalService
{
    use ConsumesExternalServices;

    protected $baseUri;

    protected $clientId;

    protected $clientSecret;

    protected $plans;

    public function __construct()
    {
        $this->baseUri = config('services.paypal.base_uri');
        $this->clientId = config('services.paypal.client_id');
        $this->clientSecret = config('services.paypal.client_secret');
        $this->plans = config('services.paypal.plans');
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
        $credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");

        return "Basic {$credentials}";
    }

    // request not required for paypal but other platforms it included
    // check api credentials and redirect to service 'approved' uri
    public function handlePayment($total, $request, $currency = 'AUD')
    {
        // paypal api urls(self, approve, update, capture)
        $order = $this->createOrder($total, $currency);
        // create collection of order links
        $orderLinks = collect($order->links);
        // get the approval url
        $approve = $orderLinks->where('rel', 'approve')->first();
        // add the paypal response approval id to session
        session()->put('payment.approvalId', $order->id);

        // open paypal login and pay
        return redirect($approve->href);
    }

    public function handleApproval()
    {
        if (session()->has('payment.approvalId')) {

            $approvalId = session()->get('payment.approvalId');

            $payment = $this->capturePayment($approvalId);

            $transactionId = $payment->purchase_units[0]->payments->captures[0]->id;

            session()->put('payment.transactionId', $transactionId);

            // payment confirmed route handles the order processing
            return redirect()->route('payment.confirmed');
        }

        return redirect()->route('checkout')->withErrors('We cannot capture the payment. Try again, please');
    }

    public function createOrder($value, $currency)
    {

        return $this->makeRequest(
            'POST',
            '/v2/checkout/orders',
            [],
            [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    0 => [
                        'amount' => [
                            'currency_code' => strtoupper($currency),
                            'value' => $value,
                        ],
                    ],
                ],
                'application_context' => [
                    'brand_name' => config('app.name'),
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => route('payment.approval'),
                    'cancel_url' => route('payment.cancelled'),
                ],
            ],
            [],
            $isJsonRequest = true,
        );
    }

    public function capturePayment($approvalId)
    {
        return $this->makeRequest(
            'POST',
            "/v2/checkout/orders/{$approvalId}/capture",
            [],
            [],
            [
                'Content-Type' => 'application/json',
            ],
        );
    }
}
