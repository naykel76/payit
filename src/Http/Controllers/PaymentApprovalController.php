<?php

namespace Naykel\Payit\Http\Controllers;

use Naykel\Payit\PaymentPlatformResolver;

class PaymentApprovalController
{
    public function __construct(protected PaymentPlatformResolver $paymentPlatformResolver) {}

    // this route is currently used by the Stripe implementation
    public function __invoke()
    {
        if (session()->has('payment.ppid')) {
            $service = $this->paymentPlatformResolver
                ->resolveService(session()->get('payment.ppid'));

            return $service->handleApproval();
        }

        // add error checking here to make sure checkout is available
        // NK::TD Refactor to allow for more flexibility and reusability
        return redirect()->route('checkout')
            ->withErrors('We cannot retrieve your payment platform. Try again, please.');
    }
}
