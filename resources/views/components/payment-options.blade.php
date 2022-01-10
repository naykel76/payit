<div class="bx">

    <p class="txt-lg"><strong>Total: </strong>${{ session('cart.total') }}</p>

    <form id="paymentForm" action="{{ route('payments.pay') }}" method="POST">

        @csrf

        <div x-data="{}">

            @foreach($paymentPlatforms as $paymentPlatform)

                <div x-data="{
                        id: {{ $paymentPlatform->id }},
                        get expanded() { return this.active === this.id },
                        set expanded(value) { this.active = value ? this.id : null },
                        }" role="region">

                    <div class="bdr pxy">

                        <div class="flex space-between">

                            <label for="{{ $paymentPlatform->id }}">

                                <input @click="expanded = !expanded" name="payment_platform" id="{{ $paymentPlatform->id }}" value="{{ $paymentPlatform->id }}" type="radio" aria-controls="collapse">
                                <span> {{ $paymentPlatform->name }}</span>

                            </label>

                        </div>

                        <div x-show="expanded" x-collapse>

                            @includeIf("payit::components.$paymentPlatform->alias-collapse")

                        </div>

                    </div>

                </div>

            @endforeach


            @if(isset($errors) && $errors->any())
                <div class="bx danger mt" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



            {{-- @error('payment_platform')
                    <span class="txt-red" role="alert"> {{ $message }} </span>
            @enderror--}}
        </div>

        <div class="text-center mt-3">
            <button type="submit" id="payButton">Pay</button>
        </div>
    </form>

    {{-- <form action="{{ route('payments.pay') }}" method="POST" id="paymentForm">

    @csrf

    <div x-data="{}">

        @foreach($paymentPlatforms as $paymentPlatform)

            <div x-data="{
                        id: {{ $paymentPlatform->id }},
                        get expanded() { return this.active === this.id },
                        set expanded(value) { this.active = value ? this.id : null },
                    }" role="region">

                <div class="bdr pxy">

                    <div class="flex space-between">

                        <label for="{{ $paymentPlatform->id }}">

                            <input @click="expanded = !expanded" name="payment_platform" id="{{ $paymentPlatform->id }}" value="{{ $paymentPlatform->id }}" type="radio" aria-controls="collapse">
                            <span> {{ $paymentPlatform->name }}</span>

                        </label>

                    </div>

                    <div x-show="expanded" x-collapse>
                        @includeIf("components.$paymentPlatform->alias")
                    </div>

                </div>

            </div>

        @endforeach

        @error('payment_platform')
            <span class="txt-red" role="alert"> {{ $message }} </span>
        @enderror
    </div>

    <div class="frm-row">
        <label class="fw4">
            <input name="agree" id="agree" type="checkbox" />&nbsp; I have read and agree to <a href="/terms-of-use" target="_blank">Terms & Conditions</a> and <a href="/privacy" target="_blank">Privacy Policy</a>.

        </label>
        @error('agree')
            <div class="txt-red" role="alert"> {{ $message }} </div>
        @enderror
    </div>



    </form> --}}

</div>
