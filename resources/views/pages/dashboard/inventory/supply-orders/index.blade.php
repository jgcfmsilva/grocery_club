@extends('layouts.dashboard_app')

@section('title', 'Supply Orders')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-8">
            <a href="{{ route('dashboard.inventory.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded shadow text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <h1 class="text-2xl font-bold">Supply Orders</h1>
        </div>
        <a href="{{ route('dashboard.inventory.supply-orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">
            Create Supply Order
        </a>
    </div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <table class="min-w-full text-sm text-center text-gray-700">
            <thead class="bg-primary text-xs uppercase tracking-wider text-white">
                <tr>
                    <th>ID</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Quantity</th>
                    <th>Product</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($supplyOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>
                            <span class="px-2 py-1 rounded {{ $order->status === 'completed' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ optional($order->registeredBy)->name ?? '-' }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ optional($order->product)->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('dashboard.inventory.supply-orders.show', $order->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs">View</a>
                            @if($order->status === 'requested')
                                <form action="{{ route('dashboard.inventory.supply-orders.complete', $order->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs cursor-pointer">Complete</button>
                                </form>
                                <form action="{{ route('dashboard.inventory.supply-orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this supply order?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs cursor-pointer">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-6 text-gray-500">No supply orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $supplyOrders->links() }}
        </div>
    </div>
</div>
@endsection