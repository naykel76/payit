<?php

namespace Naykel\Payit\Services;

class PayPalService extends BasePaymentService
{
    public function __construct()
    {
        parent::__construct('paypal');
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
        $credentials = base64_encode("{$this->key}:{$this->secret}");

        return "Basic {$credentials}";
    }

    // request not required for paypal but it is for stripe. This will be
    // reviewed in a future iteration.
    public function initiatePayment($total, $request, $currency = 'AUD')
    {
        // paypal api urls(self, approve, update, capture)
        $order = $this->createOrder($total, $currency);
        // create collection of order links
        $orderLinks = collect($order->links);
        // get the approval url
        $approve = $orderLinks->where('rel', 'approve')->first();
        // add the paypal response approval id to session

        // is the payment completed? here can i redirect to the payment confirmation page?
        session()->put('payment.approvalId', $order->id); // "1RA11209B4347653J"

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

            // payment success route handles the order processing
            return redirect()->route('payment.success');
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
