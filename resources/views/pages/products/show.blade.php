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
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        @if ($product->image)
                            <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Ver mais</a>
                        </div>
                    </div>
                </div>
            @empty
                <p>Nenhum produto encontrado.</p>
            @endforelse
        </div>

        {{-- Paginação --}}
        <div class="mt-4">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
@endsection
