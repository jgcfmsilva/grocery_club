@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Products - {{ $category->name }}</h2>

        <div class="row">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <p>No products found.</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
@endsection
