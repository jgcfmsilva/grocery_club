@extends('layouts.dashboard_app')

@section('title', 'Orders')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Orders</h1>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 p-5 rounded-xl shadow-lg mb-6">
        <form method="GET" action="{{ route('dashboard.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="col-span-1">
                <label for="status" class="block font-semibold text-white">Status</label>
                <select name="status" id="status"
                        class="mt-2 block w-full h-12 text-white border-2 border-gray-300 px-3 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option class="text-black" value="">All</option>
                    @foreach(\App\Enums\OrderStatus::cases() as $status)
                        <option class="text-black" value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                            {{ ucfirst($status->label()) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-1">
                <label for="date" class="block font-semibold text-white">Date</label>
                <input type="date" name="date" id="date" value="{{ request('date') }}"
                    class="mt-2 block w-full h-12 text-white px-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="col-span-1">
                <label for="member" class="block font-semibold text-white">Member</label>
                <input type="text" name="member" id="member" value="{{ request('member') }}"
                    class="mt-2 block w-full h-12 text-white px-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="col-span-1 flex items-end space-x-2">
                <button type="submit"
                        class="w-full bg-indigo-600 tracking-wider hover:bg-indigo-700 text-white px-8 py-3 rounded-xl shadow-md font-semibold cursor-pointer">
                    Filter
                </button>
                <a href="{{ route('dashboard.orders.index') }}"
                   class="w-full bg-red-500 tracking-wider hover:bg-red-600 text-white px-8 py-3 rounded-xl shadow-md font-semibold text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto mb-8">
        @if($orders->isEmpty())
            <div class="text-center text-gray-500 text-lg font-semibold py-6">
                No orders found.
            </div>
        @else
            <table class="table-auto w-full border-collapse border border-gray-200 shadow-lg rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">
                            <a href="{{ route('dashboard.orders.index', array_merge(request()->except('page'), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1">
                                <span class="me-2">Order ID</span>
                                @if(request('sort') === 'id')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Member</th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">
                            <a href="{{ route('dashboard.orders.index', array_merge(request()->except('page'), ['sort' => 'date', 'direction' => request('sort') === 'date' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1">
                                <span class="me-2">Date</span>
                                @if(request('sort') === 'date')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Status</th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">
                            <a href="{{ route('dashboard.orders.index', array_merge(request()->except('page'), ['sort' => 'total', 'direction' => request('sort') === 'total' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="flex items-center space-x-1">
                                <span class="me-2">Total</span>
                                @if(request('sort') === 'total')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $order->id }}</td>
                        <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">
                            {{ optional($order->member)->name ?? '-' }}
                        </td>
                        <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">
                            <span class="px-3 py-1 rounded-full {{ $order->status->badgeClass() }}">
                                {{ method_exists($order->status, 'label') ? $order->status->label() : ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $order->total }}€</td>
                        <td class="border border-gray-300 px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('dashboard.orders.show', $order->id) }}" class="bg-gray-700 hover:bg-gray-800 text-white px-3 py-1.5 rounded shadow text-sm cursor-pointer flex items-center space-x-1">
                                    <i class="fas fa-eye"></i>
                                    <span>View</span>
                                </a>
                                @if($order->isPending())
                                    <a href="{{ route('dashboard.orders.confirm', $order->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded shadow text-sm cursor-pointer flex items-center space-x-1">
                                        <i class="fas fa-check"></i>
                                        <span>Complete</span>
                                    </a>
                                @endif
                                <form action="{{ route('dashboard.orders.destroy', $order->id) }}" method="POST" class="inline delete-item-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="bg-red-700 hover:bg-red-800 text-white px-3 py-1.5 rounded shadow text-sm cursor-pointer flex items-center space-x-1 delete-item-btn">
                                        <i class="fas fa-trash text-sm"></i>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    {{ $orders->appends(request()->except('page'))->links() }}
</div>
@endsection
