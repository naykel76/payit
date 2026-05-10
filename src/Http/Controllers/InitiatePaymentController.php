<?php

namespace Naykel\Payit\Http\Controllers;

use Illuminate\Http\Request;
use Naykel\Payit\Models\PaymentPlatform;
use Naykel\Payit\PaymentPlatformResolver;

class InitiatePaymentController
{
    public function __construct(protected PaymentPlatformResolver $paymentPlatformResolver) {}

    public function __invoke(Request $request)
    {
        $platforms = PaymentPlatform::query()->get();
        $platformIds = $platforms->pluck('id')->all();

        $validated = $request->validate([
            'platformId' => ['required', 'integer', 'in:' . implode(',', $platformIds)],
            'agree' => ['accepted'],
            'payment_method' => ['nullable', 'string', 'required_if:platformId,2,12'],
        ]);

        // Resolve the payment service using the provided platform ID.
        // e.g. StripeService or PayPalService
        $service = $this->paymentPlatformResolver->resolveService($validated['platformId']);
        $platform = $platforms->firstWhere('id', (int) $validated['platformId']);

        // store the selected platform ID in the session for later use.
        session()->put('payment.ppid', $validated['platformId']);
        session()->put('payment.provider', $platform?->getProviderName());

        // Use the resolved service to handle the payment process.
        return $service->initiatePayment(session('cart.total'), $request);
    }
}

