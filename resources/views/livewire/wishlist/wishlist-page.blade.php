<div class="container">
    <h2 class="mb-4">My Wishlist</h2>

    <div class="row">
        @if($products->isEmpty())
            <p>Your wishlist is empty.</p>
        @else
            @foreach ($products as $product)
                <livewire:product-card :product="$product" :key="$product->id" />
            @endforeach
        @endif

    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>

