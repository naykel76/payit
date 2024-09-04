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
        $ppid = $request->ppid;

        $request->validate([
            'ppid' => ['required', 'exists:payment_platforms,id'],
            // 'agree' => 'accepted',
        ], [
            'ppid.required' => 'Please select a payment method',
            // 'agree.accepted' => 'You must agree to the terms and conditions.'
        ]);

        // Resolve the payment service for the given platform ID (ppid)
        $paymentService = $this->paymentPlatformResolver->resolveService($ppid);

        session()->put('payment.paymentPlatformId', $ppid);

        // NK::TD fix this work around for different cart totals not all
        // platforms will require values from the $request but it is still
        // required!
        // if (session('cart')->total) {
        //     return $paymentService->handlePayment(session('cart')->total, $request);
        // } else {
        //     return $paymentService->handlePayment(session('cart.total'), $request);
        // }
        return $paymentService->handlePayment(88, $request);
    }

    public function approval()
    {
        if (session()->has('payment.paymentPlatformId')) {
            $paymentPlatform = $this->paymentPlatformResolver
                ->resolveService(session()->get('payment.paymentPlatformId'));

            return $paymentPlatform->handleApproval();
        }

        return redirect()->back()
            ->withErrors('We cannot retrieve your payment platform. Try again, please.');
        // return redirect()->route('checkout')
        //     ->withErrors('We cannot retrieve your payment platform. Try again, please.');
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

        // this needs some more thought. in the case of FOL, redirecting to the
        // dashboard with a message might be good enough, on the other hand it
        // may be better to redirect to a dedicated page with a message and
        // order details.

        return redirect()->route(config('payit.return_route'))
            ->withSuccess('Your payment has been processed');
    }
}
