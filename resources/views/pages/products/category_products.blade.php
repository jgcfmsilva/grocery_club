@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Products - {{ $category->name }}</h2>

        <div class="row">
            @forelse($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        @if ($product->photo)
                        <img src="{{ asset('storage/products/' . $product->photo) }}"
                            alt="{{ $product->name }}"
                            class="w-full h-48 object-cover object-center rounded-t-xl">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ number_format($product->price, 2, ',', '.') }} €</p>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
            @empty
                <p>No products found.</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
@endsection
