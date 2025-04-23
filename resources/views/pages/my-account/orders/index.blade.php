@extends('layouts.pages.my-account.layout')

@section('account-content')
    <h4 class="text-3xl font-bold text-gray-800 mb-8">My Orders</h4>

    @include('layouts.partials.alerts')

    @if($orders->isEmpty())
        <div class="bg-blue-100 text-blue-800 px-4 py-3 rounded-md shadow-sm mb-6">
            You haven't placed any orders yet.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg shadow-md orders-div">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-100 text-xs uppercase tracking-wider text-gray-600">
                    <tr>
                        <th class="px-6 py-4">Order #</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Items</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 table-order-item">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium">{{ $order->id }}</td>
                        <td class="px-6 py-4">{{ $order->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ $order->items->sum('quantity') }}</td>
                        <td class="px-6 py-4">€{{ number_format($order->total, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 text-xs table-order-button rounded-full {{ $order->status->badgeClass() }}">
                                {{ $order->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('my-account.orders.show', $order) }}"
                                class="bg-blue-600 table-order-button hover:bg-blue-700 text-xs px-4 py-2 rounded-md transition">
                                    View
                                </a>
                                @if($order->status === \App\Enums\OrderStatus::COMPLETED)
                                    <a href="{{ route('my-account.orders.download', $order) }}"
                                    class="bg-gray-700 table-order-button hover:bg-gray-800 text-xs px-4 py-2 rounded-md transition">
                                        Receipt
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
@endsection

@push('styles')
    @vite('resources/css/pages/my-account/orders.css')
@endpush

@push('scripts')
    @vite('resources/js/pages/my-account/orders.js')
@endpush
