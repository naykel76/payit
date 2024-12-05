<?php

namespace Naykel\Payit\Http\Controllers;

use Illuminate\Http\Request;
use Naykel\Payit\PaymentPlatformResolver;

class InitiatePaymentController
{
    public function __construct(protected PaymentPlatformResolver $paymentPlatformResolver) {}

    public function __invoke(Request $request)
    {
        // Validate the incoming request for platform selection and agreement.
        $request->validate([
            'platformId' => ['required'],
            'agree' => 'accepted',
        ]);

        // Resolve the payment service using the provided platform ID.
        $service = $this->paymentPlatformResolver->resolveService($request->platformId);

        // Use the resolved service to handle the payment process.
        // The service is responsible for implementing the `handlePayment` logic.
        return $service->handlePayment(session('cart.total'), $request);
    }
}
