@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Produtos</h2>

        {{-- Filtro por categoria --}}
        <form method="GET" action="{{ route('products.index') }}" class="mb-4">
            <select name="category_id" onchange="this.form.submit()" class="form-select">
                <option value="">Todas as categorias</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Lista de produtos --}}
        <div class="row">
            @forelse($products as $product)
                <x-product-card :product="$product" />
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
