@push('head')
    <style type="text/css">
        .StripeElement {
            box-sizing: border-box;

            height: 40px;

            padding: 10px 12px;

            border: 1px solid transparent;
            border-radius: 4px;
            background-color: white;

            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
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

<div id="card-element"></div>
<small class="form-text text-muted" id="cardErrors" role="alert"></small>
<input type="hidden" name="payment_method" id="paymentMethod">

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('payit.stripe.key') }}');

        const elements = stripe.elements();

        const cardElement = elements.create('card');
        // const cardNumberElement = elements.create('cardNumber');
        // const cardExpiryElement = elements.create('cardExpiry');
        // const cardCvcElement = elements.create('cardCvc');

        cardElement.mount('#card-element');
        // cardNumberElement.mount('#card-number');
        // cardExpiryElement.mount('#card-expiry');
        // cardCvcElement.mount('#card-cvc');
    </script>

    <script>
        // set paymentForm from payment-options component
        const form = document.getElementById('paymentForm');
        // set payButton from payment-options component
        const payButton = document.getElementById('payButton');

        payButton.addEventListener('click', async (e) => {
            if (form.elements.payment_platform.value === "{{ $paymentPlatform->id }}") {
                e.preventDefault();

                const {
                    paymentMethod,
                    error
                } = await stripe.createPaymentMethod(
                    'card', cardElement, {
                        billing_details: {
                            "name": "{{ auth()->user()->name }}",
                            "email": "{{ auth()->user()->email }}"
                        }
                    }
                );

                if (error) {
                    const displayError = document.getElementById('cardErrors');

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
