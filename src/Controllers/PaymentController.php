<?php

namespace Naykel\Payit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Naykel\Payit\Resolvers\PaymentPlatformResolver;

class PaymentController extends Controller
{
    protected $paymentPlatformResolver;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    /**
     * Obtain a payment details.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function pay(Request $request)
    {

        $rules = [
            'payment_platform' => ['required', 'exists:payment_platforms,id'],
            'agree' => 'accepted'
        ];

        $request->validate($rules);

        $paymentPlatform = $this->paymentPlatformResolver
            ->resolveService($request->payment_platform);

        session()->put('payment.paymentPlatformId', $request->payment_platform);

        // NK::TD fix this work around for different cart totals

        // not all platforms will require values from the $request but it is still required!
        if (session('cart')->total) {
            return $paymentPlatform->handlePayment(session('cart')->total, $request);
        } else {
            return $paymentPlatform->handlePayment(session('cart.total'), $request);
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
