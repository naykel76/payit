<?php

namespace Naykel\Payit\View\Components;

use Illuminate\View\Component;
use Naykel\Payit\Models\PaymentPlatform;

class PaymentOptions extends Component
{
    public function render()
    {
        // filter out the standalone payment platforms used for single payment
        // options. For example, a single PayPal button or a single Stripe button.
        return view('payit::components.payment-options')->with([
            'paymentPlatforms' => PaymentPlatform::where('standalone', false)->get(),
        ]);
    }
}
