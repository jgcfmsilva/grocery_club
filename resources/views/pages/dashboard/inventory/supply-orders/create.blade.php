@extends('layouts.dashboard_app')

@section('title', 'Create Supply Order')

@section('content')
<div class="w-full px-0">
    <div class="flex flex-col md:flex-row items-center justify-between mb-8 w-full gap-4">
        <div class="flex items-center gap-4 w-full md:w-auto">
            <a href="{{ route('dashboard.inventory.supply-orders.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded shadow text-sm flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <h1 class="text-2xl font-bold">Create Supply Order</h1>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 md:gap-4 mt-4 md:mt-0 bg-gray-100 rounded-lg p-3 w-full md:w-auto justify-end">
            <a href="{{ route('dashboard.inventory.supply-orders.create', ['auto' => 1]) }}"
               class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded-lg shadow transition duration-200 flex items-center gap-2 text-base text-center">
                <i class="fas fa-magic"></i>
                Create Automatically
            </a>
            <a href="{{ route('dashboard.inventory.supply-orders.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow transition duration-200 flex items-center gap-2 text-base text-center">
                <i class="fas fa-edit"></i>
                Manual Creation
            </a>
        </div>
    </div>

    @if(request('auto'))
        <div class="bg-white rounded-xl shadow-lg p-4 md:p-8 mb-8 w-full">
            <h2 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-magic mr-2 text-yellow-500"></i> Automatic Supply Order
            </h2>
            @if($autoProducts->count())
                <form action="{{ route('dashboard.inventory.supply-orders.store') }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($autoProducts as $i => $prod)
                                <div class="bg-gray-300 border-2 border-gray-400 rounded-lg shadow p-4 flex flex-col items-center border border-gray-200">
                                    <input type="hidden" name="products[{{ $i }}][id]" value="{{ $prod['id'] }}">
                                    <input type="hidden" name="products[{{ $i }}][quantity]" value="{{ $prod['to_order'] }}">
                                    <div class="font-semibold text-gray-800 mb-2 text-center">{{ $prod['name'] }}</div>
                                    <div class="text-xs text-gray-500 mb-1">
                                        Current Stock: <span class="font-bold">{{ $prod['current_stock'] }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mb-1">
                                        Upper Limit: <span class="font-bold">{{ $prod['stock_upper_limit'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-2">
                                        <label class="text-sm font-medium text-gray-700">To Order:</label>
                                        <input type="number" value="{{ $prod['to_order'] }}" min="1" class="border rounded px-2 py-1 w-20 text-center bg-gray-200 cursor-not-allowed" readonly disabled>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-end mt-8">
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg cursor-pointer shadow transition duration-200 flex items-center gap-2 text-base">
                            <i class="fas fa-magic"></i>
                            Create Automatic Supply Order
                        </button>
                    </div>
                </form>
            @else
                <div class="text-gray-500">All products are above their minimum stock. No automatic supply order needed.</div>
            @endif
        </div>
    @else
        <form action="{{ route('dashboard.inventory.supply-orders.store') }}" method="POST" class="bg-white rounded-xl shadow-lg p-4 md:p-8 w-full">
            @csrf

            <div id="manual-products-list" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="manual-product-row items-end bg-gray-300 border-2 border-gray-400 shadow-lg border-1 border-gray-100 rounded-lg p-4 flex flex-col gap-4">
                    <div class="flex flex-col sm:flex-row gap-4 w-full">
                        <div class="w-full sm:w-2/3 min-w-0 sm:mr-4 flex flex-col justify-end">
                            <div class="flex items-center mb-4">
                                <label class="font-semibold">Product</label>
                                @foreach($products as $prod)
                                    @php
                                        $stockLevel = \App\Enums\ProductStockLevel::fromStock($prod->stock, $prod->stock_lower_limit, $prod->stock_upper_limit);
                                    @endphp
                                    <span class="hidden product-badge" data-product="{{ $prod->id }}">
                                        <span class="{{ $stockLevel->badgeClass() }} px-2 py-1 rounded-full text-xs font-semibold ml-2">
                                            {{ $stockLevel->label() }}
                                        </span>
                                    </span>
                                @endforeach
                            </div>
                            <select name="products[0][id]" class="border rounded px-3 py-3 w-full product-select cursor-pointer" required>
                                <option value="">-- Select a product --</option>
                                @foreach($products as $prod)
                                    @php
                                        $stockLevel = \App\Enums\ProductStockLevel::fromStock($prod->stock, $prod->stock_lower_limit, $prod->stock_upper_limit);
                                    @endphp
                                    <option value="{{ $prod->id }}">
                                        {{ $prod->name }} (Stock: {{ $prod->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full sm:w-1/3 min-w-0 flex flex-col justify-end">
                            <label class="block font-semibold mb-2">Quantity</label>
                            <input type="number" name="products[0][quantity]" min="1" class="border rounded px-3 py-3 w-full" required>
                        </div>
                        <div class="flex-shrink-0 flex items-end pl-0 sm:pl-4 w-full sm:w-auto mt-2 sm:mt-0 justify-end">
                            <button type="button" class="remove-product-row hidden text-red-600 hover:text-red-800 cursor-pointer" title="Remove" style="height: 50px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between mb-8 mt-4 gap-2">
                <button type="button" id="add-product-row" class="bg-green-200 hover:bg-green-300 text-green-800 font-semibold px-4 py-2 rounded shadow flex items-center gap-2 cursor-pointer transition duration-200">
                    <i class="fas fa-plus"></i>
                    Add Product
                </button>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-semibold w-full py-3 rounded-lg shadow transition duration-200 cursor-pointer tracking-wider">
                    <i class="fas fa-plus me-1"></i>
                    Create Supply Order
                </button>
            </div>
        </form>
    @endif
</div>
@endsection