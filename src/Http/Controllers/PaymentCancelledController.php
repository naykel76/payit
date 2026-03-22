<?php

namespace Naykel\Payit\Http\Controllers;

class PaymentCancelledController
{
    public function __invoke()
    {
        session()->remove('payment');

        return redirect()->route(config('payit.checkout_route'))
            ->withErrors('You cancelled the payment.');
    }
}
