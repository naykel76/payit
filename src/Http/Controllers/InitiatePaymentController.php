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
            // payment_method is a value returned from the stripe.js client-side
            // script. It is required only when the platform ID is 2 (Stripe).
            // This will be reviewed in a future iteration as it is not a good
            // practice to have this validation and hardcoded value here.
            'payment_method' => 'required_if:platformId,2',
        ]);

        // Resolve the payment service using the provided platform ID.
        // e.g. StripeService or PayPalService
        $service = $this->paymentPlatformResolver->resolveService($request->platformId);

        // Use the resolved service to handle the payment process.
        return $service->handlePayment(session('cart.total'), $request);
    }
}
