@extends('layouts.pages.my-account.layout')

@section('title', 'My Account - Order #' . $order->id)

@section('account-content')
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-2xl font-semibold text-gray-700">Order #{{ $order->id }}</h4>
        <a href="{{ route('my-account.orders.index') }}" class="btn btn-secondary"
                style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                <i class="bi bi-arrow-left me-1"></i> Back to Orders
        </a>
    </div>

    @include('layouts.partials.alerts.alerts')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="card">
            <div class="card-header card-header-bg">
                <h5>Order Details</h5>
            </div>
            <div class="card-body table-order-show-card bg-white border-sm">
                <p><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Status:</strong>
                    <span class="badge {{ $order->status->badgeClass() }}">
                        {{ $order->status->label() }}
                    </span>
                </p>
                @if($order->status === \App\Enums\OrderStatus::CANCELED && $order->cancel_reason)
                    <p><strong>Cancel Reason:</strong> {{ $order->cancel_reason }}</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header card-header-bg">
                <h5>Delivery Information</h5>
            </div>
            <div class="card-body table-order-show-card bg-white">
                <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
                @if($order->nif)
                    <p><strong>NIF Number:</strong> {{ $order->nif }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="card mb-6">
        <div class="card-header table-order-show-header card-header-bg">
            <h5>Order Items</h5>
        </div>
        <div class="card-body table-order-show-card bg-white p-6 rounded-b-lg shadow-lg">
            <div class="table-responsive">
                <table class="min-w-full text-sm text-left order-table-items">
                    <thead class="text-md uppercase tracking-wider order-table-items">
                        <tr>
                            <th class="px-4 py-3">Product</th>
                            <th class="px-4 py-3">Price</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Discount</th>
                            <th class="px-4 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="order-table-items divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr class="transition duration-200">
                                <td class="px-4 py-3">{{ $item->product->name }}</td>
                                <td class="px-4 py-3">€{{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-4 py-3">{{ $item->quantity }}</td>
                                <td class="px-4 py-3">€{{ number_format($item->discount, 2) }}</td>
                                <td class="px-4 py-3">€{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="order-table-items">
                        <tr>
                            <td colspan="4" class="text-end py-3 px-4 font-medium">Items Total:</td>
                            <td class="py-3 px-4 font-medium">€{{ number_format($order->total_items, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end py-3 px-4 font-medium">Shipping:</td>
                            <td class="py-3 px-4 font-medium">€{{ number_format($order->shipping_cost, 2) }}</td>
                        </tr>
                        <tr class="border-t-2 border-gray-300">
                            <td colspan="4" class="text-end py-3 px-4 font-bold">Order Total:</td>
                            <td class="py-3 px-4 font-bold">€{{ number_format($order->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="flex justify-end items-center gap-3 w-full">
        @if($order->status === \App\Enums\OrderStatus::PENDING)
            <form action="{{ route('my-account.orders.cancel', $order) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm rounded-md transition">
                    <i class="fa fa-ban me-2"></i>Cancel Order
                </button>
            </form>
        @else
            <form action="{{ route('my-account.orders.reorder', $order) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm rounded-md transition">
                    <i class="fas fa-shopping-cart me-2"></i>Reorder These Items
                </button>
            </form>
        @endif

        @if($order->status === \App\Enums\OrderStatus::COMPLETED)
            <a href="{{ route('my-account.orders.download', $order) }}" class="btn btn-info btn-sm rounded-md transition">
                <i class="fas fa-download me-2"></i> Download Receipt
            </a>
        @endif
    </div>
@endsection

@push('styles')
    @vite('resources/css/pages/my-account/orders.css')
@endpush

@push('scripts')
    @vite('resources/js/pages/my-account/orders.js')
@endpush
