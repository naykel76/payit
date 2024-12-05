<div class="bx">
    @php
        $paymentPlatform = \Naykel\Payit\Models\PaymentPlatform::find(11);
    @endphp
    <form id="paymentForm" action="{{ route('payment.initiate') }}" method="POST">
        @csrf
        <input name="platformId" id="{{ $paymentPlatform->id }}" value="{{ $paymentPlatform->id }}" type="hidden">

        <x-gt-checkbox for="agree" ignoreErrors>
            I have read and agree to <a href="/terms-of-use" class="txt-underline" target="_blank">&nbsp;Terms & Conditions</a> &nbsp; and &nbsp;
            <a href="/privacy-policy" class="txt-underline" target="_blank">Privacy Policy</a>.
        </x-gt-checkbox>

        <x-gt-errors />

        <button type="submit" class="btn yellow w-full">
            <x-gt-icon name="paypal-favicon" type="payment" class="h-1.5 mr-05" />
            <span class="txt-1 fw7">Checkout With {{ $paymentPlatform->method }}</span>
        </button>
    </form>
</div>