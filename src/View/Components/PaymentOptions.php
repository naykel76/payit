<?php

namespace Naykel\Payit\View\Components;

use Illuminate\View\Component;
use Naykel\Payit\Models\PaymentPlatform;

class PaymentOptions extends Component
{
    public function render()
    {
        $paymentPlatforms = PaymentPlatform::where('active', true)->get();

        return view('payit::components.payment-options')->with([
            'paymentPlatforms' => $paymentPlatforms,
        ]);
    }
}
