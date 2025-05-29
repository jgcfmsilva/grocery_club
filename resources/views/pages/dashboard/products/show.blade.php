@extends('layouts.dashboard_app')

@section('title', 'Product Details')

@section('content')
<div class="container mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ url()->previous() }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm flex items-center space-x-1">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
        <h1 class="text-3xl font-bold ml-4">{{ $product->name }}</h1>
        <div class="ml-auto">
            @if(!isEmployee())
            <a href="{{ route('dashboard.products.edit', $product->id) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow text-sm flex items-center space-x-1">
                <i class="fas fa-edit"></i>
                <span>Edit</span>
            </a>
            @endif
        </div>
    </div>
    <div class="bg-white p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex justify-center">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-3/4 rounded max-h-64 object-contain">
            </div>
            <div>
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Product Information</h2>
                    <hr class="my-2">
                </div>
                <p class="mb-2"><strong>Category:</strong> {{ $product->category->name }}</p>
                <p class="mb-2"><strong>Price:</strong> {{ number_format($product->price, 2, ',', '') }}€</p>
                <p class="mb-2"><strong>Stock:</strong>
                    @if($product->stock <= $product->stock_lower_limit)
                        <span class="text-red-600 font-bold">{{ number_format($product->stock, 0, ',', '') }} <span class="text-sm">(Low stock)</span></span>
                    @elseif($product->stock >= $product->stock_upper_limit)
                        <span class="text-green-600 font-bold">{{ number_format($product->stock, 0, ',', '') }} <span class="text-sm">(High stock)</span></span>
                    @else
                        <span class="text-gray-800">{{ number_format($product->stock, 0, ',', '') }}</span>
                    @endif
                </p>
                <p class="mb-2"><strong>Stock Lower Limit:</strong> {{ number_format($product->stock_lower_limit, 0, ',', '') }}</p>
                <p class="mb-2"><strong>Stock Upper Limit:</strong> {{ number_format($product->stock_upper_limit, 0, ',', '') }}</p>
                <p class="mb-2"><strong>Discount:</strong>
                    @if($product->hasDiscount())
                        {{ number_format($product->discount, 2, ',', '') }}€ (Min Qty: {{ $product->discount_min_qty }})
                    @else
                        <span class="text-gray-500">No Discount</span>
                    @endif
                </p>
                <p class="mb-2"><strong>Discounted Price:</strong>
                    @if($product->hasDiscount())
                        {{ number_format($product->getPriceWithDiscount(), 2, ',', '') }}€
                    @else
                        <span class="text-gray-500">N/A</span>
                    @endif
                </p>
                <p class="mb-2"><strong>Description:</strong> {{ $product->description }}</p>
                <p class="mb-2"><strong>Created At:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                <p class="mb-2"><strong>Updated At:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        {{-- Stock Adjustments --}}
        <div class="mt-10">
            <h2 class="text-lg font-bold mb-4 text-gray-800 flex items-center">
                <i class="fas fa-history mr-2 text-gray-500"></i> Stock Adjustments
            </h2>
            @php
                $adjustments = $product->stockAdjustments()->with('registeredBy')->orderByDesc('created_at')->paginate(10);
            @endphp
            @if($adjustments->isEmpty())
                <div class="text-gray-500">No stock adjustments found for this product.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-center bg-white shadow border-1 border-gray-400">
                        <thead class="bg-gray-800">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-white tracking-wider">Date</th>
                                <th class="px-4 py-3 font-semibold text-white tracking-wider">Changed By</th>
                                <th class="px-4 py-3 font-semibold text-white tracking-wider">Old</th>
                                <th class="px-4 py-3 font-semibold text-white tracking-wider">New</th>
                                <th class="px-4 py-3 font-semibold text-white tracking-wider">Change</th>
                                <th class="px-4 py-3 font-semibold text-white tracking-wider">Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($adjustments as $adj)
                                @php
                                    $custom = [];
                                    if ($adj->custom) {
                                        $custom = is_array($adj->custom) ? $adj->custom : json_decode($adj->custom, true);
                                    }
                                    $oldStock = $adj->old_stock ?? ($custom['old_stock'] ?? null);
                                    $newStock = $adj->new_stock ?? ($custom['new_stock'] ?? null);
                                    $diff = null;
                                    if(isset($oldStock) && isset($newStock)) {
                                        $diff = $newStock - $oldStock;
                                    } elseif(isset($adj->quantity_changed)) {
                                        $diff = $adj->quantity_changed;
                                    }
                                    $type = $custom['type'] ?? 'manual_adjustment';
                                    $reason = $custom['reason'] ?? null;
                                    $supplyOrderId = $custom['supply_order_id'] ?? null;
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-2 text-gray-700 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-2">
                                            <i class="fas fa-calendar-alt text-gray-400"></i>
                                            {{ $adj->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-gray-700 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-2">
                                            <i class="fas fa-user text-gray-400"></i>
                                            {{ optional($adj->registeredBy)->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-block px-2 py-1 rounded bg-gray-200 text-gray-800 font-mono min-w-[40px]">
                                            {{ $oldStock !== null ? $oldStock : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-block px-2 py-1 rounded bg-gray-200 text-gray-800 font-mono min-w-[40px]">
                                            {{ $newStock !== null ? $newStock : '-' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($diff !== null)
                                            <span class="inline-block px-2 py-1 rounded font-semibold min-w-[40px]
                                                {{ $diff > 0 ? 'bg-green-100 text-green-700' : ($diff < 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                                                {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($type === 'supply_order_completed' && $supplyOrderId)
                                            <a href="{{ route('dashboard.inventory.supply-orders.show', $supplyOrderId) }}"
                                               class="inline-flex items-center gap-1 px-2 py-1 rounded bg-green-100 text-green-800 hover:bg-green-200 transition">
                                                <i class="fas fa-truck text-green-500"></i>
                                                Supply Order #{{ $supplyOrderId }}
                                            </a>
                                        @elseif($type === 'manual_adjustment')
                                            @if($reason)
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-blue-100 text-blue-800">
                                                    <i class="fas fa-comment-dots text-blue-400"></i>
                                                    {{ $reason }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-blue-100 text-blue-800">
                                                    <i class="fas fa-tools text-blue-400"></i>
                                                    Manual Adjustment
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $adjustments->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
