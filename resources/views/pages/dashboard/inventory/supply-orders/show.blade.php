@extends('layouts.dashboard_app')

@section('title', 'Supply Order Details')

@section('content')
<div class="w-full px-0">
    <div class="flex items-center space-x-8 mb-8">
        <a href="{{ url()->previous() }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded shadow text-sm flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <h1 class="text-2xl font-bold">Supply Order #{{ $supplyOrder->id }}</h1>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8 w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <p class="mb-4">
                    <span class="font-semibold">Status:</span>
                    @php
                        $statusColors = [
                            'completed' => 'bg-green-100 text-green-800 border-green-300',
                            'requested' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                            'canceled'  => 'bg-red-100 text-red-800 border-red-300',
                        ];
                        $status = $supplyOrder->status;
                        $color = $statusColors[$status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                    @endphp
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold border {{ $color }}">
                        <i class="
                            @if($status === 'completed') fas fa-check-circle mr-1
                            @elseif($status === 'requested') fas fa-hourglass-half mr-1
                            @elseif($status === 'canceled') fas fa-times-circle mr-1
                            @else fas fa-info-circle mr-1
                            @endif
                        "></i>
                        {{ ucfirst($status) }}
                    </span>
                </p>
                <p class="mb-2"><span class="font-semibold">Created By:</span> {{ optional($supplyOrder->registeredBy)->name ?? '-' }}</p>
                <p class="mb-2"><span class="font-semibold">Created At:</span> {{ $supplyOrder->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <div class="bg-gray-200 rounded-lg p-6 shadow-inner">
                    <h2 class="text-lg font-semibold mb-3 text-gray-800 flex items-center">
                        <i class="fas fa-box mr-2 text-gray-500"></i> Product & Quantity
                    </h2>
                    @if($supplyOrder->product)
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700">Product:</span>
                                <a href="{{ route('dashboard.products.show', $supplyOrder->product->id) }}"
                                   class="text-blue-700 hover:underline font-semibold flex items-center gap-1">
                                    {{ $supplyOrder->product->name }}
                                </a>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700">Quantity:</span>
                                <span class="text-gray-900 font-bold text-xl">{{ $supplyOrder->quantity }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-gray-500 py-2">No product found for this supply order.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection