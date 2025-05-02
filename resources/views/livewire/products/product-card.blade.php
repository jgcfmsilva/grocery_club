<div class="col-md-3 mb-4">
    <div class="card h-100 shadow-lg flex flex-col">
        @if ($product->photo)
            <a href="{{ route('products.show', $product->id) }}">
                <img src="{{ asset('storage/products/' . $product->photo) }}"
                class="w-full h-48 object-cover object-center rounded-t-xl" alt="{{ $product->name }}">
            </a>
        @else
            <a href="{{ route('products.show', $product->id) }}">
                <img src="{{ asset('storage/products/product_no_image.png') }}"
                class="w-full h-48 object-cover object-center rounded-t-xl" alt="{{ $product->name }}">
            </a>
        @endif
        <div class="card-body flex flex-col justify-between flex-grow">
            <!-- Product Name and Category -->
            <a href="{{ route('products.show', $product->id) }}">
                <h5 class="card-title text-xl font-semibold text-gray-800">{{ $product->name }}</h5>
            </a>

            <p class="text-gray-500 text-sm">{{ $product->category->name }}</p>

            <!-- Price and Discount -->
            @if ($product->discount)
                <small class="text-sm text-green-700">
                    Buy {{$product->discount_min_qty}}
                    @if ($product->discount_min_qty == 1)
                        unit
                    @else
                        units
                    @endif
                    and get {{ number_format($product->discount, 2, ',', '.') }} € discount
                </small>
            @endif

            <p class="card-text text-gray-600">
                @if ($product->discount)
                    <span class="line-through text-red-500">{{ number_format($product->price, 2, ',', '.') }} €</span>
                    <span class="text-dark font-semibold ml-2">{{ number_format(calculate_price_with_discount($product->price, $product->discount), 2, ',', '.') }} €</span>
                @else
                    <span class="text-dark font-semibold">{{ number_format($product->price, 2, ',', '.') }} € / unit</span>
                @endif
            </p>

            <!-- Stock Status -->
            <p class="text-gray-500">
                @if ($product->stock <= 0)
                    <span class="text-red-500">Out of Stock</span>
                @else
                    <span class="text-green-500">In Stock</span>
                @endif
            </p>

            <!-- Product Description -->
            <p class="text-gray-500 text-sm truncate">{{ $product->description }}</p>

            <!-- Quantity Selector & Add to Cart -->
            @if ($product->stock > 0 || $product->stock <= 0)
                <div class="flex flex-col gap-4 mt-4">
                    <!-- Button and Wishlist Icon -->
                    <div class="flex justify-between items-center mt-2 gap-4">
                        <!-- Add to Cart Button and Input Quantity -->
                        <livewire:add-to-cart :productId="$product->id" />

                        <!-- Wishlist Icon -->
                        <livewire:wishlist-button :productId="$product->id" />
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
