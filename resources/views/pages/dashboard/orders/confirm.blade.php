@extends('layouts.dashboard_app')

@section('title', 'Confirm Order')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Confirm Order #{{ $order->id }}</h1>
        <a href="{{ url()->previous() }}" class="flex items-center bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded shadow">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <!-- Order Details -->
    <div class="bg-gray-800 p-6 rounded-xl shadow-lg mb-6 text-white">
        <h2 class="text-2xl font-bold mb-6 border-b border-gray-700 pb-2">Order Information 
            <span class="ml-2 text-lg font-normal px-3 py-1 rounded-full {{ $order->status->badgeClass() }}">
                <span class="text-white">{{ $order->status->label() }}</span>
            </span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p><strong>Member:</strong> <span class="text-gray-300">{{ $order->member->name }}</span></p>
            <p><strong>Total Items:</strong> <span class="text-gray-300">{{ number_format($order->total_items, 2, ',', '') }}€</span></p>
            <p><strong>Date:</strong> <span class="text-gray-300">{{ $order->date->format('d-m-Y') }}</span></p>
            <p><strong>Discounts:</strong> <span class="text-gray-300">{{ number_format($order->calculate_order_total_discount(), 2, ',', '') }}€</span></p>
            <p><strong>NIF:</strong> <span class="text-gray-300">{{ $order->nif }}</span></p>
            <p><strong>Shipping Cost:</strong> <span class="text-gray-300">{{ number_format($order->shipping_cost, 2, ',', '') }}€</span></p>
            <p><strong>Delivery Address:</strong> <span class="text-gray-300">{{ $order->delivery_address }}</span></p> 
            <p><strong>Total:</strong> <span class="text-gray-300">{{ number_format($order->total, 2, ',', '') }}€</span></p>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-gray-800 p-6 rounded-xl shadow-lg mb-8 text-white">
        <h2 class="text-2xl font-bold mb-6 border-b border-gray-700 pb-2">Order Items</h2>
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-700 rounded-lg">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="border border-gray-600 px-6 py-3 text-left text-lg font-bold text-white">Product</th>
                        <th class="border border-gray-600 px-6 py-3 text-left text-lg font-bold text-white">Quantity</th>
                        <th class="border border-gray-600 px-6 py-3 text-left text-lg font-bold text-white">Unit Price</th>
                        <th class="border border-gray-600 px-6 py-3 text-left text-lg font-bold text-white">Discount</th>
                        <th class="border border-gray-600 px-6 py-3 text-left text-lg font-bold text-white">Subtotal</th>
                        <th class="border border-gray-600 px-6 py-3 text-left text-lg font-bold text-white">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itemsWithStock as $itemStock)
                    <tr class="hover:bg-gray-700">
                        <td class="border border-gray-600 px-6 py-4 text-md text-gray-300">
                            <a href="{{ route('dashboard.products.show', $itemStock['item']->product->id) }}" class="text-blue-400 hover:text-blue-500 hover:text-blue-600">
                                {{ $itemStock['item']->product->name }}
                            </a>
                        </td>
                        <td class="border border-gray-600 px-6 py-4 text-md text-gray-300">{{ $itemStock['item']->quantity }}</td>
                        <td class="border border-gray-600 px-6 py-4 text-md text-gray-300">{{ number_format($itemStock['item']->unit_price, 2, ',', '') }}€</td>
                        <td class="border border-gray-600 px-6 py-4 text-md text-gray-300">{{ number_format($itemStock['item']->discount, 2, ',', '') }}€</td>
                        <td class="border border-gray-600 px-6 py-4 text-md text-gray-300">{{ number_format($itemStock['item']->subtotal, 2, ',', '') }}€</td>
                        <td class="border border-gray-600 px-6 py-4 text-md text-center">
                            @php
                                $currentStock = $itemStock['item']->product->stock;
                                $destStock = $currentStock - $itemStock['item']->quantity;
                            @endphp
                            @if($itemStock['has_stock'])
                                <span class="text-green-500 text-xl font-bold">&#10003;</span>
                                <span class="ml-2 text-gray-200">
                                    <span class="text-red-500">{{ $currentStock }}</span>
                                    <span class="mx-1 text-gray-200">&#8594;</span>
                                    <span class="font-bold text-green-500">{{ $destStock }}</span>
                                </span>
                            @else
                                <span class="text-red-500 text-xl font-bold">&#10007;</span>
                                <span class="ml-2 text-gray-200">{{ $currentStock }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 bg-gray-700 p-6 rounded-lg text-white">
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-semibold">Total Items:</span>
                <span class="text-lg">{{ number_format($order->total_items, 2, ',', '') }}€</span>
            </div>
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-semibold">Total Discounts:</span>
                <span class="text-lg">
                    {{ number_format($order->calculate_order_total_discount(), 2, ',', '') }}€
                </span>
            </div>
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-semibold">Shipping Cost:</span>
                <span class="text-lg">{{ number_format($order->shipping_cost, 2, ',', '') }}€</span>
            </div>
            <div class="flex justify-between items-center border-t border-gray-600 pt-4">
                <span class="text-xl font-bold">Order Total:</span>
                <span class="text-xl font-bold">{{ number_format($order->total, 2, ',', '') }}€</span>
            </div>
        </div>
    </div>

    @if($can_complete)
        <form method="POST" action="{{ route('dashboard.orders.complete', $order->id) }}">
            @csrf
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded shadow text-lg mt-4 cursor-pointer">
              <i class="fas fa-check mr-2"></i> Complete Order
            </button>
        </form>
    @else
        <div class="text-red-500 font-bold text-lg mb-4 mt-4">
            Cannot complete the order: at least one product is out of stock.
        </div>
    @endif
</div>
@endsection
