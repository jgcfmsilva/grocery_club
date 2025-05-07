@extends('layouts.dashboard_app')

@section('title', 'Order Details')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Order #{{ $order->id }}</h1>
        <a href="{{ route('dashboard.orders.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium px-6 py-2 rounded shadow">
            Back to Orders
        </a>
    </div>

    <!-- Order Details -->
    <div class="bg-gray-800 p-5 rounded-xl shadow-lg mb-6 text-white">
        <h2 class="text-xl font-bold mb-4">Order Information</h2>
        <p><strong>Member:</strong> {{ $order->member->name }}</p>
        <p><strong>Status:</strong> {{ $order->status->label() }}</p>
        <p><strong>Date:</strong> {{ $order->date->format('Y-m-d') }}</p>
        <p><strong>Total Items:</strong> {{ $order->total_items }}</p>
        <p><strong>Shipping Cost:</strong> {{ $order->shipping_cost }}€</p>
        <p><strong>Total:</strong> {{ $order->total }}€</p>
        <p><strong>NIF:</strong> {{ $order->nif }}</p>
        <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
    </div>

    <!-- Order Items -->
    <div class="overflow-x-auto mb-8">
        <h2 class="text-xl font-bold mb-4">Order Items</h2>
        <table class="table-auto w-full border-collapse border border-gray-200 shadow-lg rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Product</th>
                    <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Quantity</th>
                    <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Unit Price</th>
                    <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Discount</th>
                    <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $item->product->name }}</td>
                    <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $item->quantity }}</td>
                    <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $item->unit_price }}€</td>
                    <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $item->discount }}€</td>
                    <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $item->subtotal }}€</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
