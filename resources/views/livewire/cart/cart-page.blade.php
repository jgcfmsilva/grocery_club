<div class="container mx-auto px-4 py-8">
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Cart</h1>
    </header>

    <div class="flex flex-col lg:flex-row gap-8">
        <main class="lg:w-2/3">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="hidden md:flex bg-gray-50 px-6 py-4 border-b">
                    <div class="w-2/5 font-semibold text-gray-700">Product</div>
                    <div class="w-1/5 font-semibold text-gray-700 text-center">Price</div>
                    <div class="w-1/5 font-semibold text-gray-700 text-center">Quantity</div>
                    <div class="w-1/5 font-semibold text-gray-700 text-right">Total</div>
                </div>

                <!-- Lista de itens -->
                <div id="cart-items">
                    @foreach ($cart as $id => $item)
                        <div class="flex flex-col md:flex-row items-stretch p-6 border-b min-h-[120px] md:min-h-0">
                            <div class="w-full md:w-2/5 flex items-center mb-4 md:mb-0">
                                <img src="{{ asset('storage/products/' . $item['photo']) }}" alt="{{ $item['name'] }}"
                                    class="w-20 h-20 object-cover rounded">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $item['name'] }}</h3>
                                </div>
                            </div>

                            <div class="w-full md:w-1/5 flex items-center justify-center py-4">
                                <div>
                                    <span class="md:hidden font-semibold text-gray-700 mr-2">Price:</span>
                                    <span
                                        class="font-semibold block text-center">€{{ number_format($item['price'], 2) }}</span>
                                </div>
                            </div>

                            <div class="w-full md:w-1/5 flex items-center justify-center py-4">
                                <div class="flex items-center border rounded-lg h-10">
                                    <button wire:click="decrement('{{ $id }}')"
                                        class="quantity-btn decrease px-3 py-1 text-gray-600 hover:bg-gray-100 h-full flex items-center">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="text" value="{{ $item['quantity'] }}"
                                        class="w-12 text-center border-0 focus:ring-0 h-full" readonly>
                                    <button wire:click="increment('{{ $id }}')"
                                        class="quantity-btn increase px-3 py-1 text-gray-600 hover:bg-gray-100 h-full flex items-center">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="w-full md:w-1/5 flex items-center justify-between md:justify-end py-4">
                                <div class="flex items-center">
                                    <span class="md:hidden font-semibold text-gray-700 mr-2">Total:</span>
                                    <span
                                        class="font-semibold">€{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                                <button wire:click="removeItem('{{ $id }}')"
                                    class="remove-btn ml-4 text-red-500 hover:text-red-700">
                                    &nbsp;<i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>

        <!-- Order Summary -->
        <aside class="lg:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Order Summary</h2>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">€{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Discounts</span>
                        <span class="text-green-600">-€{{ number_format($discount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-semibold">€{{ number_format($shipping, 2) }}</span>
                    </div>
                    <div class="border-t pt-4 flex justify-between">
                        <span class="text-lg font-bold text-gray-800">Total</span>
                        <span class="text-xl font-bold text-gray-800">€{{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>

                <button
                    class="w-full py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    Checkout
                </button>

                <div class="mt-6 text-sm text-gray-500">
                    <p class="mb-2">By completing your purchase, you agree to our <a href="#"
                            class="text-blue-600 hover:underline">Terms of Service</a>.</p>
                    <p>Shipping and taxes calculated at checkout.</p>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h3 class="font-semibold text-gray-800 mb-4">Payment Methods</h3>
                <div class="flex space-x-4">
                    <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                        <i class="fab fa-cc-visa text-2xl text-blue-900"></i>
                    </div>
                    <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                        <i class="fab fa-cc-mastercard text-2xl text-red-900"></i>
                    </div>
                    <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                        <i class="fab fa-cc-paypal text-2xl text-blue-700"></i>
                    </div>
                    <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                        <i class="fab fa-cc-apple-pay text-2xl text-black"></i>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
