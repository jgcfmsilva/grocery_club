@extends('layouts.dashboard_app')

@section('title', 'Supply Orders')

@section('content')
<div class="w-full px-0">
    <div class="flex justify-between items-center mb-8 w-full">
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard.inventory.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <h1 class="text-3xl font-bold">Supply Orders</h1>
        </div>
        <a href="{{ route('dashboard.inventory.supply-orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow text-base font-semibold flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Create Supply Order
        </a>
    </div>
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full">
        <div class="overflow-x-auto rounded-t-lg w-full">
            <table class="min-w-full w-full text-sm text-center text-gray-700 border-r-2 border-l-2 border-b-2 border-gray-800">
                <thead class="bg-gray-800 text-xs uppercase tracking-wider text-white">
                    <tr>
                        <th class="px-4 py-3 text-gr">ID</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Created By</th>
                        <th class="px-4 py-3">Created At</th>
                        <th class="px-4 py-3">Quantity</th>
                        <th class="px-4 py-3">Product</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supplyOrders as $order)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-semibold">{{ $order->id }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'requested') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'canceled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ optional($order->registeredBy)->name ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">{{ $order->quantity }}</td>
                            <td class="px-4 py-3">
                                @if($order->product)
                                    <a href="{{ route('dashboard.products.show', $order->product->id) }}"
                                       class="text-blue-700 hover:underline font-semibold flex items-center gap-1 justify-center">
                                        <i class="fas fa-link text-blue-400"></i>
                                        {{ $order->product->name }}
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 flex flex-wrap gap-2 justify-center">
                                <a href="{{ route('dashboard.inventory.supply-orders.show', $order->id) }}"
                                   class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs flex items-center gap-1">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if($order->status === 'requested')
                                    <form action="{{ route('dashboard.inventory.supply-orders.complete', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs flex items-center gap-1 cursor-pointer">
                                            <i class="fas fa-check"></i> Complete
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.inventory.supply-orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this supply order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs flex items-center gap-1 cursor-pointer">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-gray-500 text-lg">No supply orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-8">
            {{ $supplyOrders->links() }}
        </div>
    </div>
</div>
@endsection