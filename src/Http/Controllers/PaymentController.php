<?php

namespace Naykel\Payit\Http\Controllers;

use App\Http\Controllers\Controller;
use Naykel\Payit\PaymentPlatformResolver;

class PaymentController extends Controller
{
    protected $paymentPlatformResolver;

    /**
     * Create a new controller instance.
     */
    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    public function cancelled()
    {
        session()->remove('payment');

        return redirect()->route('checkout')
            ->withErrors('You cancelled the payment.');
    }

    /**
     * Confirmed payment actions
     */
    public function confirmed()
    {
        // add order processing here!
        // override by adding payment.process route in local web.php

        return redirect()->route('user.dashboard')
            ->withSuccess('Your payment has been processed');
    }
}
