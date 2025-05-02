<div class="container mx-auto px-4 py-8">
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Cart</h1>
    </header>

    <div class="flex flex-col lg:flex-row gap-8">
        <main class="lg:w-2/3">
            @if (count($cart) > 0)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-primary text-white hidden md:flex px-6 py-4">
                        <div class="w-2/5 font-semibold">Product</div>
                        <div class="w-1/5 font-semibold text-center">Price</div>
                        <div class="w-1/5 font-semibold text-center">Quantity</div>
                        <div class="w-1/5 font-semibold text-right">Total</div>
                    </div>

                    <!-- Lista de itens -->
                    <div id="cart-items">
                        @foreach ($cart as $id => $item)
                            <div class="flex flex-col md:flex-row items-stretch p-6 border-b border-b-gray-400 last:border-b-0 min-h-[120px] md:min-h-0">
                                <div class="w-full md:w-2/5 flex items-center mb-2 md:mb-0">
                                    <a href="{{ route('products.show', $item['id']) }}"><img src="{{ asset('storage/products/' . $item['photo']) }}" alt="{{ $item['name'] }}"
                                        class="w-20 h-20 object-cover rounded"></a>
                                        <div class="ml-4 pt-2">
                                            <a href="{{ route('products.show', $item['id']) }}">
                                                <h4 class="font-semibold text-dark">{{ $item['name'] }}</h4>
                                            </a>
                                            <p class="category-name text-dark opacity-70">{{ $item['category_name'] }}</p>

                                            @if ($item['stock'] === 0 || $item['stock'] < $item['quantity'])
                                                <p class="text-sm text-red-500 font-medium">
                                                    Quantity exceeds stock - delivery may be delayed
                                                </p>
                                            @endif

                                            @if ($item['stock'] > 0 && $item['stock'] >= $item['quantity'])
                                                <p class="text-sm text-green-500 font-medium mb-0.5">
                                                    In Stock
                                                </p>
                                            @endif
                                        </div>
                                </div>

                                <div class="w-full md:w-1/5 flex items-center justify-center py-4">
                                    <div>
                                        <span class="md:hidden font-semibold text-gray-700 mr-2">Price:</span>

                                        @php
                                            $discounted = calculate_discounted_price(
                                                $item['price'],
                                                $item['discount'] ?? 0,
                                                $item['quantity'],
                                                $item['discount_min_qty'] ?? PHP_INT_MAX
                                            );
                                        @endphp

                                        <div class="text-center">
                                            @if($item['discount'] > 0 && $item['quantity'] >= $item['discount_min_qty'])
                                                <span class="text-sm text-red-500 line-through block">
                                                    €{{ number_format($item['price'], 2) }}
                                                </span>
                                                <span class="font-semibold text-green-600">
                                                    €{{ number_format($discounted, 2) }}
                                                </span>
                                            @else
                                                <span class="font-semibold">
                                                    €{{ number_format($item['price'], 2) }}
                                                </span>
                                            @endif
                                        </div>
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

                                    @php
                                        $discounted = calculate_discounted_price(
                                            $item['price'],
                                            $item['discount'] ?? 0,
                                            $item['quantity'],
                                            $item['discount_min_qty'] ?? PHP_INT_MAX
                                        );
                                        $totalWithDiscount = $discounted * $item['quantity'];
                                    @endphp

                                    <div class="flex items-center">
                                        <span class="md:hidden font-semibold text-gray-700 mr-2">Total:</span>
                                        <span
                                            class="font-semibold">€{{ number_format($totalWithDiscount, 2) }}</span>
                                    </div>
                                    <button wire:click="removeItem('{{ $id }}')"
                                        class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-gray-600">
                    <h2 class="text-xl font-semibold mb-2">Your cart is empty</h2>
                    <p class="mb-4">Browse our store and add some products!</p>
                    <a href="{{ route('home') }}" class="text-green-600 hover:text-green-800 font-medium underline">
                        Go to Store
                    </a>
                </div>
            @endif
        </main>

        <!-- Order Summary -->
        <aside class="lg:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Order Summary</h2>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-dark">Subtotal</span>
                        <span class="font-semibold">€{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-dark">Discounts</span>
                        <span class="text-green-600">-€{{ number_format($discount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-dark">Shipping</span>
                        <span class="font-semibold">€{{ number_format($shipping, 2) }}</span>
                    </div>
                    <div class="border-t border-t-gray-400 pt-4 flex justify-between">
                        <span class="text-lg font-bold text-dark">Total</span>
                        <span class="text-xl font-bold text-dark">€{{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>

                <button
                    class="w-full py-3 bg-green-600 rounded text-white hover:bg-green-700 transition-colors font-semibold">
                    Purchase
                </button>
            </div>
        </aside>
    </div>
</div>
