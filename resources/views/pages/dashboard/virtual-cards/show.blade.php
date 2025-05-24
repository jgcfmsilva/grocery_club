@extends('layouts.dashboard_app')

@section('title', 'Virtual Card Details')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Virtual Card #{{ $card->id }} - {{ optional($card->user)->name ?? '-' }}</h1>
    <div class="bg-white rounded-lg shadow-lg p-6 border-2 border-gray-300">
        <div class="mb-4">
            <a href="{{ route('dashboard.virtual-cards.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded shadow text-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <p><strong>Card Number:</strong> <span class="font-medium">{{ $card->card_number }}</span></p>
                <p><strong>Owner:</strong> {{ optional($card->user)->name ?? '-' }}</p>
                <p><strong>Balance:</strong> <span class="font-bold text-lg text-green-700">{{ number_format($card->balance, 2, ',', '.') }}€</span></p>
            </div>
            <div>
                <p><strong>Created At:</strong> {{ $card->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Updated At:</strong> {{ $card->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        <div class="mt-8">
            <h2 class="text-lg font-semibold mb-4">Last Transactions</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-primary text-xs uppercase tracking-wider text-white border-b-2 border-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-gray-800">Date</th>
                            <th class="px-4 py-3 text-gray-800">Type</th>
                            <th class="px-4 py-3 text-gray-800">Amount</th>
                            <th class="px-4 py-3 text-gray-800">Description</th>
                            <th class="px-4 py-3 text-gray-800">Reference</th>
                            <th class="px-4 py-3 text-gray-800">Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($card->operations()->latest('created_at')->paginate(10) as $op)
                            <tr class="hover:bg-gray-50 transition border-b border-gray-500">
                                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">{{ \Carbon\Carbon::parse($op->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-sm font-medium">
                                    <span class="inline-block px-2 py-1 rounded 
                                        {{ $op->type === \App\Enums\TransactionType::Debit ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-green-100 text-green-700 border border-green-200' }}">
                                        {{ $op->type->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm font-bold {{ $op->type === \App\Enums\TransactionType::Debit ? 'text-red-500' : 'text-green-600' }}">
                                    {{ $op->type === \App\Enums\TransactionType::Debit ? '-' : '+' }}{{ number_format($op->value, 2) }}€
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($op->type === \App\Enums\TransactionType::Credit)
                                        {{ $op->credit_type->label() }}
                                    @else
                                        {{ $op->debit_type->label() }}
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if($op->credit_type === \App\Enums\CreditType::Payment)
                                        <span class="block font-mono text-xs">{{ $op->payment_type }}</span>
                                        <span class="block font-mono text-xs">{{ $op->payment_reference }}</span>
                                    @else
                                        <span class="text-gray-400 font-medium text-center">No reference</span>
                                    @endif
                                </td>
                                @if($op->order_id)
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2 justify-center">
                                            <a href="{{ route('dashboard.orders.show', $op->order_id) }}"
                                               class="bg-blue-600 hover:bg-blue-700 text-xs px-4 py-2 rounded-md transition text-white shadow">
                                                View
                                            </a>
                                        </div>
                                    </td>
                                @else
                                    <td class="px-4 py-3 text-sm">
                                        <span class="text-gray-400 font-medium text-center">No order</span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $card->operations()->latest('created_at')->paginate(10)->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
