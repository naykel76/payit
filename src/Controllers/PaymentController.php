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
            // 'agree' => 'accepted'
        ];

        $request->validate($rules);

        $paymentPlatform = $this->paymentPlatformResolver
            ->resolveService($request->payment_platform);

        session()->put('paymentPlatformId', $request->payment_platform);

        // not all platforms will require values from the $request but it is still required!
        return $paymentPlatform->handlePayment(session('cart.total'), $request);
    }

    public function approval()
    {
        if (session()->has('paymentPlatformId')) {
            $paymentPlatform = $this->paymentPlatformResolver
                ->resolveService(session()->get('paymentPlatformId'));

            return $paymentPlatform->handleApproval();
        }

        return redirect()
            ->route('home')
            ->withErrors('We cannot retrieve your payment platform. Try again, please.');
    }

    public function cancelled()
    {
        return redirect()
            ->route('home')
            ->withErrors('You cancelled the payment.');
    }

    public function confirmed()
    {
        return redirect()
            ->route('home')
            ->withSuccess(['payment' => "We received your payment."]);
    }
}
