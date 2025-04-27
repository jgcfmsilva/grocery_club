<div class="col-md-3 mb-4">
    <div class="card h-100 shadow-lg flex flex-col">
        @if ($product->photo)
            <img src="{{ asset('storage/products/' . $product->photo) }}" class="w-full h-48 object-cover object-center rounded-t-xl" alt="{{ $product->name }}">
        @else
            <img src="{{ asset('storage/products/product_no_image.png') }}" class="w-full h-48 object-cover object-center rounded-t-xl" alt="{{ $product->name }}">
        @endif
        <div class="card-body flex flex-col justify-between flex-grow">
            <!-- Product Name and Category -->
            <h5 class="card-title text-xl font-semibold text-gray-800">{{ $product->name }}</h5>
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
                    <span class="text-gray-800 font-semibold ml-2">{{ number_format($product->price - $product->discount, 2, ',', '.') }} €</span>
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
                <form>
                    @csrf
                    <div class="flex flex-col gap-4 mt-4">
                        <!-- Button and Wishlist Icon -->
                        <div class="flex justify-between items-center mt-2 gap-4">
                            <!-- Input Quantity -->
                            <input type="number" name="quantity" value="1" min="1" class="w-20 p-2 border-1 border-gray-400 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 ease-in-out select-none" placeholder="Qt.">
                            
                            <!-- Add to Cart Button -->
                            <button type="submit" class="btn btn-primary w-full py-2 px-3 text-white font-semibold rounded-lg shadow-lg transform hover:scale-105 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300 ease-in-out">
                                Add to Cart
                            </button>
                            
                            <!-- Wishlist Icon -->
                            @if (1 == 1)
                                <button type="button" class="text-gray-500 hover:text-red-500 focus:outline-none">
                                    <i class="far fa-heart fa-lg"></i>
                                </button>
                            @else
                                <button type="button" class="text-red-500 hover:text-red-400 focus:outline-none">
                                    <i class="fas fa-heart fa-lg"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
