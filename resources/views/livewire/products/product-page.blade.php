<div class="container mx-auto px-4 py-8 mt-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden h-[600px] w-full max-w-[600px] mx-auto">
            <div class="relative w-full h-full">
                @if ($product->photo)
                    <img src="{{ asset('storage/products/' . $product->photo) }}" alt="{{ $product->name }}"
                        class="absolute inset-0 w-full h-full object-cover">
                @else
                    <img src="{{ asset('storage/products/product_no_image.png') }}" alt="No image available"
                        class="absolute inset-0 w-full h-full object-cover">
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-6 relative max-h-[600px] max-w-[600px] w-full mx-auto overflow-y-auto">
            <!-- Wishlist Heart Icon -->
            @if (Auth::check())
                @if (!isEmployee())
                    <button type="button" wire:click="editWishList"
                        class="absolute top-4 right-4 p-2 text-gray-400 hover:text-red-500 focus:outline-none {{ $inWishList ? 'text-red-500 hover:text-red-400' : 'text-gray-500 hover:text-red-500' }} mt-15">
                        <i class="{{ $inWishList ? 'fas' : 'far' }} fa-heart fa-lg"></i>
                    </button>
                @endif
            @else
                <button wire:click="redirectToLogin" type="button"
                    class="mt-15 absolute top-4 right-4 p-2 text-gray-500 hover:text-red-500 focus:outline-none">
                    <i class="far fa-heart fa-lg"></i>
                </button>
            @endif

            <h1 class="text-3xl font-bold text-gray-800 mb-1 mt-10">{{ $product->name }}</h1>

            <p class="text-gray-500 mb-4">{{ $product->category->name }}</p>

            <!-- Price Section -->
            <div class="mt-10">
                @if ($product->discount)
                    <div class="flex items-center mb-2">
                        <span class="text-2xl font-bold text-gray-800">
                            €{{ number_format(calculate_price_with_discount($product->price, $product->discount), 2) }}
                        </span>
                        <span class="ml-2 text-lg text-red-500 line-through">
                            €{{ number_format($product->price, 2) }}
                        </span>
                        <span class="ml-2 bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">
                            -{{ $product->discount }}% OFF
                        </span>
                    </div>

                    @if ($product->discount_min_qty > 1)
                        <p class="text-sm text-green-600 mt-1">
                            Buy {{ $product->discount_min_qty }} units for discount
                        </p>
                    @endif
                @else
                    <span class="text-2xl font-bold text-gray-800">
                        €{{ number_format($product->price, 2) }}
                    </span>
                @endif
            </div>

            <!-- Stock Status -->
            <div class="mb-4">
                @if($product -> stock <= $product -> stock_lower_limit)
                    <span class="text-red-500 font-semibold">Stock: {{ $product->stock }} available</span>
                @else
                    <span class="text-green-500 font-medium">Stock: {{ $product->stock }} available</span>
                @endif
            </div>

            <!-- Description -->
            <div class="mt-14">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Description</h3>
                <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
            </div>

            <!-- Add to Cart Form -->
            @if (!isEmployee())
                <form wire:submit.prevent="addToCart" class="flex gap-4 w-full items-center mt-12">
                    <input type="number" wire:model="quantity" min="1"
                        class="w-20 p-2 border-1 border-gray-400 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 ease-in-out select-none"
                        placeholder="Qt.">

                    <button type="submit"
                        class="btn btn-primary w-full py-2 px-3 text-white font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300 ease-in-out">
                        Add to Cart
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>