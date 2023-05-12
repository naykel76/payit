<?php

namespace Naykel\Payit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Naykel\Payit\Resolvers\PaymentPlatformResolver;

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

    /**
     * Obtain a payment details.
     */
    public function pay(Request $request)
    {

        // make sure payment type, and agree to conditions are set
        $request->validate([
            'payment_platform' => ['required', 'exists:payment_platforms,id'],
            'agree' => 'accepted'
        ]);

        // make payment_platform more relatable because it is an id number
        $paymentPlatformId = $request->payment_platform;

        // resolve the payment gateway url and keys
        $paymentPlatformCredentials = $this->paymentPlatformResolver
            ->resolveService($paymentPlatformId);

        session()->put('payment.paymentPlatformId', $paymentPlatformId);

        // NK::TD fix this work around for different cart totals
        // not all platforms will require values from the $request but it is still required!
        if (session('cart')->total) {
            return $paymentPlatformCredentials->handlePayment(session('cart')->total, $request);
        } else {
            return $paymentPlatformCredentials->handlePayment(session('cart.total'), $request);
        }
    }

    public function approval()
    {
        if (session()->has('payment.paymentPlatformId')) {
            $paymentPlatform = $this->paymentPlatformResolver
                ->resolveService(session()->get('payment.paymentPlatformId'));

            return $paymentPlatform->handleApproval();
        }

        return redirect()->route('checkout')
            ->withErrors('We cannot retrieve your payment platform. Try again, please.');
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
