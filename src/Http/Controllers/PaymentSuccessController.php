<?php

namespace Naykel\Payit\Http\Controllers;

class PaymentSuccessController
{
    public function __invoke()
    {
        // NK::TD Refactor to allow for more flexibility and reusability. Maybe
        // return a payment success dto.
        return redirect()->route('user.dashboard')
            ->withSuccess('Your payment has been processed');
    }
}
