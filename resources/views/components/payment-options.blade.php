<div class="bx">

    @if (session('cart')->total)
        <p class="txt-lg"><strong>Total: </strong>${{ number_format(session('cart')->total, 2) }}</p>
    @else
        <p class="txt-lg"><strong>Total: </strong>${{ session('cart.total') }}</p>
    @endif

    <form id="paymentForm" action="{{ route('payment.pay') }}" method="POST">

        @csrf

        <div x-data="{ active: 0 }">

            @foreach ($paymentPlatforms as $paymentPlatform)
                <div x-data="{
                    id: {{ $paymentPlatform->id }},
                    get expanded() { return this.active === this.id },
                    set expanded(value) { this.active = value ? this.id : null },
                }" role="region">

                    <div class="bdr pxy">

                        <div class="flex space-between">
                            <label for="{{ $paymentPlatform->id }}">
                                <input @click="expanded = !expanded" name="payment_platform" id="{{ $paymentPlatform->id }}" value="{{ $paymentPlatform->id }}" type="radio" aria-controls="collapse">
                                <span> {{ $paymentPlatform->method }}</span>
                            </label>
                        </div>

                        <div x-show="expanded" x-collapse>
                            <?php $providerComponent = str_replace(' ', '-', strtolower($paymentPlatform->method)); ?>
                            @includeIf("payit::components.$providerComponent")
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="frm-row mt">
            <label class="fw4">
                <input name="agree" id="agree" type="checkbox" />&nbsp; I have read and agree to <a href="/terms-of-use" target="_blank">Terms & Conditions</a> and <a href="/privacy-policy"
                    target="_blank">Privacy Policy</a>.
            </label>
        </div>

        <x-gt-errors />

        <button type="submit" id="payButton" class="btn primary w-full">Process Payment</button>

    </form>

</div>
