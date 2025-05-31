@extends('layouts.app')

@section('title', 'Procucts - ' . config('vars.app_name'))

@section('content')
    <div class="container">
        <h2 class="mb-4">Products</h2>

        <form method="GET" action="{{ route('products.index') }}" class="mb-8 row g-3 align-items-center">
            <div class="col-md-4">
                <select name="category_id" class="form-select !border-2 !border-gray-500 !rounded-xl cursor-pointer" onchange="this.form.submit()">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" name="min_price" class="form-control !border-2 !border-gray-500 !rounded-xl" placeholder="Min price" min="0" step="0.01" value="{{ request('min_price', $min_price ?? '') }}">
            </div>
            <div class="col-md-2">
                <input type="number" name="max_price" class="form-control !border-2 !border-gray-500 !rounded-xl" placeholder="Max price" min="0" step="0.01" value="{{ request('max_price', $max_price ?? '') }}">
            </div>
            <div class="col-md-3">
                <select name="sort" class="form-select !border-2 !border-gray-500 !rounded-xl cursor-pointer" onchange="this.form.submit()">
                    <option value="name_asc" {{ (request('sort', $sort ?? '') == 'name_asc') ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="name_desc" {{ (request('sort', $sort ?? '') == 'name_desc') ? 'selected' : '' }}>Name (Z-A)</option>
                    <option value="price_asc" {{ (request('sort', $sort ?? '') == 'price_asc') ? 'selected' : '' }}>Price (Low to High)</option>
                    <option value="price_desc" {{ (request('sort', $sort ?? '') == 'price_desc') ? 'selected' : '' }}>Price (High to Low)</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="w-full bg-secondary text-white font-semibold py-3 rounded text-lg">
                    Filter
                </button>
            </div>
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
