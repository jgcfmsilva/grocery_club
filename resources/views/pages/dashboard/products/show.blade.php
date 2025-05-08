@extends('layouts.dashboard_app')

@section('title', 'Product Details')

@section('content')
<div class="container mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ url()->previous() }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm flex items-center space-x-1">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
        <h1 class="text-3xl font-bold ml-4">{{ $product->name }}</h1>
        <div class="ml-auto">
            <a href="{{ route('dashboard.products.edit', $product->id) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow text-sm flex items-center space-x-1">
                <i class="fas fa-edit"></i>
                <span>Edit</span>
            </a>
        </div>
    </div>
    <div class="bg-white p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex justify-center">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-3/4 rounded">
            </div>
            <div>
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Product Information</h2>
                    <hr class="my-2">
                </div>
                <p class="mb-2"><strong>Category:</strong> {{ $product->category->name }}</p>
                <p class="mb-2"><strong>Price:</strong> {{ number_format($product->price, 2, ',', '') }}€</p>
                <p class="mb-2"><strong>Stock:</strong>
                    @if($product->stock <= $product->stock_lower_limit)
                        <span class="text-red-600 font-bold">{{ number_format($product->stock, 0, ',', '') }} <span class="text-sm">(Low stock)</span></span>
                    @elseif($product->stock >= $product->stock_upper_limit)
                        <span class="text-green-600 font-bold">{{ number_format($product->stock, 0, ',', '') }} <span class="text-sm">(High stock)</span></span>
                    @else
                        <span class="text-gray-800">{{ number_format($product->stock, 0, ',', '') }}</span>
                    @endif
                </p>
                <p class="mb-2"><strong>Stock Lower Limit:</strong> {{ number_format($product->stock_lower_limit, 0, ',', '') }}</p>
                <p class="mb-2"><strong>Stock Upper Limit:</strong> {{ number_format($product->stock_upper_limit, 0, ',', '') }}</p>
                <p class="mb-2"><strong>Discount:</strong>
                    @if($product->hasDiscount())
                        {{ number_format($product->discount, 2, ',', '') }}€ (Min Qty: {{ $product->discount_min_qty }})
                    @else
                        <span class="text-gray-500">No Discount</span>
                    @endif
                </p>
                <p class="mb-2"><strong>Discounted Price:</strong>
                    @if($product->hasDiscount())
                        {{ number_format($product->getPriceWithDiscount(), 2, ',', '') }}€
                    @else
                        <span class="text-gray-500">N/A</span>
                    @endif
                </p>
                <p class="mb-2"><strong>Description:</strong> {{ $product->description }}</p>
                <p class="mb-2"><strong>Created At:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                <p class="mb-2"><strong>Updated At:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
