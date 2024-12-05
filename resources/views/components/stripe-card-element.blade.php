<div class="bx">
    @php
        $paymentPlatform = \Naykel\Payit\Models\PaymentPlatform::find(12);
    @endphp
    <form id="payment-form" action="{{ route('payment.initiate') }}" method="POST">
        @csrf
        {{-- this is only required to set the platformId --}}
        <input name="platformId" id="{{ $paymentPlatform->id }}" value="{{ $paymentPlatform->id }}" type="hidden">

        <div id="card-element"></div>
        <small class="txt-red" id="card-errors" role="alert"></small>

        {{-- what is this for? --}}
        <input type="hidden" name="payment_method" id="paymentMethod">

        <x-gt-checkbox for="agree" ignoreErrors>
            I have read and agree to <a href="/terms-of-use" class="txt-underline" target="_blank">&nbsp;Terms & Conditions</a> &nbsp; and &nbsp;
            <a href="/privacy-policy" class="txt-underline" target="_blank">Privacy Policy</a>.
        </x-gt-checkbox>

        <x-gt-errors />

        <button type="submit" id="pay-button" class="btn primary w-full">Process Payment</button>
    </form>
</div>

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        // Create an instance of Stripe
        const stripe = Stripe('{{ config('payit.stripe.key') }}');

        // initialize stripe elements
        const elements = stripe.elements();

        // Create an instance of the card Element. 
        // Can add optional styles object as second parameter
        const card = elements.create('card');

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');
    </script>

    <script>
        const form = document.getElementById('payment-form');
        const payButton = document.getElementById('pay-button');

        payButton.addEventListener('click', async (e) => {
            if (form.elements.platformId.value === "{{ $paymentPlatform->id }}") {
                e.preventDefault();

                const {
                    paymentMethod,
                    error
                } = await stripe.createPaymentMethod(
                    'card', card, {
                        billing_details: {
                            "name": "{{ auth()->user()->name }}",
                            "email": "{{ auth()->user()->email }}"
                        }
                    }
                );

                if (error) {
                    const displayError = document.getElementById('card-errors');
                    displayError.textContent = error.message;
                } else {
                    const tokenInput = document.getElementById('paymentMethod');
                    tokenInput.value = paymentMethod.id;
                    form.submit();
                }
            }
        });
    </script>
@endpush

@push('head')
    <style type="text/css">
        .StripeElement {
            height: 40px;
            padding: 10px 12px;
            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>
@endpush
