@extends('layouts.dashboard_app')

@section('title', 'Order Details')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Order #{{ $order->id }}</h1>
        <a href="{{ url()->previous() }}" class="flex items-center bg-gray-600 hover:bg-gray-700 text-white font-medium px-6 py-2 rounded shadow">
            <i class="fas fa-arrow-left mr-2"></i> Back to Orders
        </a>
    </div>

    <!-- Order Details -->
    <div class="bg-gray-800 p-6 rounded-xl shadow-lg mb-6 text-white">
        <h2 class="text-2xl font-bold mb-6 border-b border-gray-700 pb-2">Order Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p><strong>Member:</strong> {{ $order->member->name }}</p>
            <p><strong>Status:</strong>
                <span class="px-3 py-1 rounded-full {{ $order->status->badgeClass() }}">
                    {{ $order->status->label() }}
                </span>
            </p>
            <p><strong>Date:</strong> {{ $order->date->format('Y-m-d') }}</p>
            <p><strong>Total Items:</strong> {{ number_format($order->total_items, 2, ',', '') }}€</p>
            <p><strong>Shipping Cost:</strong> {{ number_format($order->shipping_cost, 2, ',', '') }}€</p>
            <p><strong>Total:</strong> {{ number_format($order->total, 2, ',', '') }}€</p>
            <p><strong>NIF:</strong> {{ $order->nif }}</p>
            <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
        </div>
        @if($order->isCompleted())
            <a href="{{ route('dashboard.orders.invoice', $order->id) }}" target="_blank" rel="noopener noreferrer" class="mt-6 inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded shadow">
                <i class="fas fa-file-pdf mr-2"></i> View Invoice
            </a>
        @endif
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="hover:bg-gray-700">
                        <td class="border border-gray-600 px-6 py-4 text-md text-gray-300">
                            <a href="{{ route('dashboard.products.show', $item->product->id) }}" class="text-blue-400 hover:text-blue-500 hover:text-blue-600">
                                {{ $item->product->name }}
                            </a>
                        </td>
                        <td class="border border-gray-600 px-6 py-4 text-md text-gray-300">{{ $item->quantity }}</td>
                        <td class="border border-gray-600 px-6 py-4 text-md text-gray-300">{{ number_format($item->unit_price, 2, ',', '') }}€</td>
                        <td class="border border-gray-600 px-6 py-4 text-md text-gray-300">{{ number_format($item->discount, 2, ',', '') }}€</td>
                        <td class="border border-gray-600 px-6 py-4 text-md text-gray-300">{{ number_format($item->subtotal, 2, ',', '') }}€</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 bg-gray-700 p-6 rounded-lg text-white">
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-semibold">Shipping Cost:</span>
                <span class="text-lg">{{ number_format($order->shipping_cost, 2, ',', '') }}€</span>
            </div>
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-semibold">Total Discounts:</span>
                <span class="text-lg">{{ number_format($order->items->sum('discount'), 2, ',', '') }}€</span>
            </div>
            <div class="flex justify-between items-center border-t border-gray-600 pt-4">
                <span class="text-xl font-bold">Grand Total:</span>
                <span class="text-xl font-bold">{{ number_format($order->total, 2, ',', '') }}€</span>
            </div>
        </div>
    </div>
</div>
@endsection
