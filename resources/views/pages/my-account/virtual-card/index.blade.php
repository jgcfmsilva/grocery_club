@extends('layouts.pages.my-account.layout')

@section('account-content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Virtual Card</h3>

    <div class="container mx-auto px-4 sm:px-6 py-6">
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            <div class="w-full lg:w-2/3 flex justify-center">
                <div class="virtual-card">
                    <div class="virtual-card-inner">
                        <div class="virtual-card-front">
                        <div class="virtual-card-bg"></div>
                        <div class="virtual-card-glow"></div>
                        <svg
                            width="72"
                            height="24"
                            viewBox="0 0 72 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="virtual-card-logo"
                        >
                            <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M52.3973 1.01093L51.5588 5.99054C49.0448 4.56717 43.3231 4.23041 43.3231 6.85138C43.3231 7.89285 44.6177 8.60913 46.178 9.47241C48.5444 10.7817 51.5221 12.4291 51.5221 16.062C51.5221 21.8665 45.4731 24 41.4645 24C37.4558 24 34.8325 22.6901 34.8325 22.6901L35.7065 17.4848C38.1115 19.4688 45.4001 20.032 45.4001 16.8863C45.4001 15.5645 43.9656 14.785 42.3019 13.8811C40.0061 12.6336 37.2742 11.1491 37.2742 7.67563C37.2742 1.30988 44.1978 0 47.1132 0C49.8102 0 52.3973 1.01093 52.3973 1.01093ZM66.6055 23.6006H72L67.2966 0.414276H62.5732C60.3923 0.414276 59.8612 2.14215 59.8612 2.14215L51.0996 23.6006H57.2234L58.4481 20.1566H65.9167L66.6055 23.6006ZM60.1406 15.399L63.2275 6.72235L64.9642 15.399H60.1406ZM14.7942 16.3622L20.3951 0.414917H26.7181L17.371 23.6012H11.2498L6.14551 3.45825C2.83215 1.41281 0 0.807495 0 0.807495L0.108643 0.414917H9.36816C11.9161 0.414917 12.1552 2.50314 12.1552 2.50314L14.1313 12.9281L14.132 12.9294L14.7942 16.3622ZM25.3376 23.6006H31.2126L34.8851 0.414917H29.0095L25.3376 23.6006Z"
                            fill="white"
                            />
                        </svg>
                        <div class="virtual-card-contactless">
                            <svg xmlns="http://www.w3.org/2000/svg" width="46" height="56">
                            <path
                                fill="none"
                                stroke="#f9f9f9"
                                stroke-width="6"
                                stroke-linecap="round"
                                d="m35,3a50,50 0 0,1 0,50M24,8.5a39,39 0 0,1 0,39M13.5,13.55a28.2,28.5
                0 0,1 0,28.5M3,19a18,17 0 0,1 0,18"
                            />
                            </svg>
                        </div>
                        <div class="virtual-card-chip"></div>
                        <div class="virtual-card-holder">{{ auth()->user()->name }}</div>
                        <div class="virtual-card-number">{{ $card->card_number }}</div>
                        <div class="virtual-card-balance">
                            <div class="label">BALANCE</div>
                            <div class="amount">{{ number_format($card->balance, 2, ',', '.') }}€</div>
                        </div>
                        </div>
                        <div class="virtual-card-back">
                        <div class="virtual-card-signature">{{ auth()->user()->name }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:max-w-md">
                <div class="card">
                    <div class="card-header card-header-bg">
                        <h5>Add Funds</h5>
                    </div>
                    <div class="card-body table-order-show-card bg-white border-sm">
                        <form id="topup-card-form" method="POST" action="{{ route('my-account.virtual-card.topup') }}" class="space-y-6">
                            @csrf
                            <div class="form-group">
                                <label for="default_payment_type" class="block font-medium mb-1">Payment Method:</label>
                                <select name="default_payment_type" id="default_payment_type" required class="mt-1 p-3 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                    <option value="">-- Select --</option>
                                    <option value="Visa" {{ old('default_payment_type', auth()->user()->default_payment_type) == 'Visa' ? 'selected' : '' }}>Visa</option>
                                    <option value="PayPal" {{ old('default_payment_type', auth()->user()->default_payment_type) == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                                    <option value="MB WAY" {{ old('default_payment_type', auth()->user()->default_payment_type) == 'MB WAY' ? 'selected' : '' }}>MB WAY</option>
                                </select>
                            </div>

                            <div id="paymentReferenceWrapper" class="form-group hidden">
                                <label for="default_payment_reference" id="paymentReferenceLabel" class="block font-medium mb-1">Payment Reference:</label>
                                <input type="text" name="default_payment_reference" id="default_payment_reference" required
                                    value="{{ old('default_payment_reference', auth()->user()->default_payment_reference) }}"
                                    class="mt-2 p-3 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">

                            </div>

                            <div id="cardVisaCvc" class="form-group hidden">
                                <label for="payment_cvc" id="paymentReferenceLabel" class="block font-medium mb-1">CVC Visa:</label>
                                <input type="text" name="payment_cvc" id="payment_cvc"
                                    class="mt-2 p-3 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">

                            </div>

                            <div class="form-group">
                                <label for="value" class="block font-medium mb-1">Amount to Add (€):</label>
                                <input type="number" name="value" id="value" min="0.01" step="0.01" required class="mt-2 p-3 w-full rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            </div>

                            <button type="submit" id="submitButton" class="btn btn-primary w-full py-4 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Confirm Add Funds
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/css/pages/my-account/virtual-card.css')
@endpush

@push('scripts')
    @vite('resources/js/pages/my-account/virtual-card.js')
@endpush
