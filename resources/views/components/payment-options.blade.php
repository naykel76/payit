<div class="bx">
    <form id="payment-form" action="{{ route('payment.initiate') }}" method="POST">
        @csrf
        <div x-data="{}">
            @foreach ($paymentPlatforms as $paymentPlatform)
                <div x-data="{
                    id: {{ $paymentPlatform->id }},
                    get expanded() { return this.active === this.id },
                    set expanded(value) { this.active = value ? this.id : null },
                }" role="region">
                    <div class="bdr" style="{{ $loop->first ? 'margin-bottom: -1px' : '' }}">
                        <label for="{{ $paymentPlatform->id }}" style="margin: 0" class="bg-neutral-50 pxy-05 cursor-pointer w-full">
                            <input x-on:click="expanded = !expanded" name="platformId"
                                id="{{ $paymentPlatform->id }}" value="{{ $paymentPlatform->id }}" type="radio" aria-controls="collapse"
                                {{ old('platformId') == $paymentPlatform->id ? 'checked' : '' }}>
                            @if ($paymentPlatform->method === 'PayPal')
                                <x-gt-icon name="paypal-favicon" type="payment" class="h-1.5 mr-05" />
                                <span class="txt-1 fw6"> {{ $paymentPlatform->method }}</span>
                            @elseif($paymentPlatform->method === 'Credit Card')
                                <x-gt-icon name="credit-card" class="h-1.5 mr-05" />
                                <span class="txt-1 fw6"> Credit/Debit Card</span>
                            @endif
                        </label>
                        <div x-show="expanded" x-collapse class="pxy">
                            <?php $providerComponent = str_replace(' ', '-', strtolower($paymentPlatform->method)); ?>
                            @includeIf("payit::components.$providerComponent-collapse")
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <x-gt-checkbox for="agree" ignoreErrors>
            I have read and agree to <a href="/terms-of-use" class="txt-underline" target="_blank">&nbsp;Terms & Conditions</a> &nbsp; and &nbsp;
            <a href="/privacy-policy" class="txt-underline" target="_blank">Privacy Policy</a>.
        </x-gt-checkbox>

        <x-gt-errors />

        <button type="submit" id="pay-button" class="btn primary w-full">Process Payment</button>
    </form>
</div>
