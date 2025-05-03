@extends('layouts.app')

@section('title', $category->name . ' - Products')

@section('content')
    <div class="container">
        <h2 class="mb-4">Products - {{ $category->name }}</h2>

        <div class="row">
            @forelse($products as $product)
                <livewire:product-card :product="$product" :key="$product->id" />
            @empty
                <p>No products found.</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
@endsection
