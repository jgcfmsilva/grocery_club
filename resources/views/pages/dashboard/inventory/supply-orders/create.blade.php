@extends('layouts.dashboard_app')

@section('title', 'Create Supply Order')

@section('content')
<div class="w-full max-w-xl mx-auto">
    <div class="flex items-center mb-8">
        <a href="{{ route('dashboard.inventory.supply-orders.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded shadow text-sm flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <h1 class="text-2xl font-bold ml-4">Create Supply Order</h1>
    </div>

    <form action="{{ route('dashboard.inventory.supply-orders.store') }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
        @csrf

        <div class="mb-6">
            <label for="product_id" class="block font-semibold mb-2">Product</label>
            <select name="products[0][id]" id="product_id" class="border rounded px-3 py-3 w-full searchable-select" required>
                <option value="">-- Select a product --</option>
                @foreach($products as $prod)
                    <option value="{{ $prod->id }}">{{ $prod->name }} (Stock: {{ $prod->stock }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-8">
            <label for="quantity" class="block font-semibold mb-2">Quantity to Order</label>
            <input type="number" name="products[0][quantity]" id="quantity" min="1" class="border rounded px-3 py-2 w-full" required>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-semibold w-full py-3 rounded-lg shadow transition duration-200 cursor-pointer tracking-wider">
                <i class="fas fa-plus me-1"></i>
                Create Supply Order
            </button>
        </div>
    </form>
</div>
@endsection