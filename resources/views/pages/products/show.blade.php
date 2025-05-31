@extends('layouts.app')

@section('title', 'Product - Product Name')

@section('content')
    <div class="container">
        <h2 class="mb-4">Products</h2>

        <form method="GET" action="{{ route('products.index') }}" class="mb-4">
            <select name="category_id" onchange="this.form.submit()" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </form>

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
