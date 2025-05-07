@extends('layouts.app')

@section('title', 'Procucts - ' . config('vars.app_name'))

@section('content')
    <div class="container">
        <h2 class="mb-4">Products</h2>

        {{-- Filtro por categoria --}}
        <form method="GET" action="{{ route('products.index') }}" class="mb-4">
            <select name="category_id" onchange="this.form.submit()" class="form-select">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') === $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Lista de produtos --}}
        <div class="row">
            @forelse($products as $product)
                <livewire:product-card :product="$product" :key="$product->id" />
            @empty
                <p>No products found.</p>
            @endforelse
        </div>

        {{-- Paginação --}}
        <div class="mt-4">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
@endsection
