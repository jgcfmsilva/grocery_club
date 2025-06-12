@extends('layouts.dashboard_app')

@section('title', 'Order Details')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Order #{{ $order->id }}</h1>
        <a href="{{ url()->previous() }}" class="flex items-center bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded shadow">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <!-- Order Details -->
    <div class="bg-gray-800 p-6 rounded-xl shadow-lg mb-6 text-white">
        <div class="flex items-center justify-between mb-6 border-b border-gray-700 pb-2">
            <div class="flex items-center">
                <h2 class="text-2xl font-bold">Order Information
                    <span class="ml-2 text-lg font-normal px-3 py-1 rounded-full {{ $order->status->badgeClass() }}">
                        <span class="text-white">{{ $order->status->label() }}</span>
                    </span>
                    @if($order->isCanceled() && $order->cancel_reason)
                        <span class="ml-2 text-red-400 text-lg">(Reason: {{ $order->cancel_reason }})</span>
                    @endif
                </h2>
            </div>
            @if($order->isPending())
                <a href="{{ route('dashboard.orders.confirm', $order->id) }}" class="bg-indigo-700 hover:bg-indigo-800 text-white px-3 py-1.5 rounded shadow text-sm cursor-pointer flex items-center space-x-1">
                    <i class="fas fa-check"></i>
                    <span>Go To Complete Page</span>
                </a>
            @endif
        </div>
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
                            <span class="ml-2 text-xs text-gray-400">(Stock: {{ $item->product->stock }})</span>
                        </td>
                        <td class="border border-gray-600 px-6 py-4 text-md text-gray-300">
                            {{ $item->quantity }}
                            @if($order->isPending())
                                @if($item->product->stock >= $item->quantity)
                                    <span class="ml-2 text-green-400" title="Sufficient stock">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @else
                                    <span class="ml-2 text-red-400" title="Insufficient stock">
                                        <i class="fas fa-times-circle"></i>
                                    </span>
                                @endif
                            @endif
                        </td>
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
</div>
@endsection
