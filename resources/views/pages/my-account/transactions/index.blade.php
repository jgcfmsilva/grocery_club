@extends('layouts.pages.my-account.layout')

@section('account-content')
    <h4 class="text-3xl font-bold text-gray-800 mb-4">Transactions</h4>

    <div class="container mx-auto p-2">
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg transactions-div">
            <table class="min-w-full text-sm text-center text-gray-700">
                <thead class="bg-primary text-xs uppercase tracking-wider text-white">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Type</th>
                        <th class="px-6 py-4 font-semibold">Amount</th>
                        <th class="px-6 py-4 font-semibold">Description</th>
                        <th class="px-6 py-4 font-semibold">Reference</th>
                        <th class="px-6 py-4 font-semibold">Order</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($operations as $op)
                        <tr class="border-b">
                            <td class="px-4 py-3 text-sm text-dark">{{ \Carbon\Carbon::parse($op->date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-dark">
                                {{ $op->type->label()}}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium {{ $op->type === \App\Enums\TransactionType::Debit ? 'text-red-500' : 'text-green-500' }}">
                                {{ $op->type === \App\Enums\TransactionType::Debit ? '-' : '+' }}{{ number_format($op->value, 2) }}€
                            </td>
                            <td class="px-4 py-3 text-sm text-dark">
                                @if($op->type === \App\Enums\TransactionType::Credit)
                                    {{ $op->credit_type->label() }}
                                @else
                                    {{ $op->debit_type->label() }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-dark">
                                @if($op->credit_type === \App\Enums\CreditType::Payment)
                                    <span class="block">{{ $op->payment_type }}</span>
                                    <span class="block">{{ $op->payment_reference }}</span>
                                @else
                                    <span class="text-dark opacity-60 font-medium text-center">No reference associated</span>
                                @endif
                            </td>
                            
                            @if($op->order_id)
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2 justify-center">
                                        <a href="{{ route('my-account.orders.show', $op->order_id) }}"
                                        class="bg-blue-600 table-order-button hover:bg-blue-700 text-xs px-4 py-2 rounded-md transition text-white">
                                            View
                                        </a>
                                        
                                        @if($op->order->status === \App\Enums\OrderStatus::COMPLETED)
                                            <a href="{{ route('my-account.orders.download', $op->order_id) }}"
                                            class="bg-gray-700 table-order-button hover:bg-gray-800 text-xs px-4 py-2 rounded-md transition text-white">
                                                Receipt
                                            </a>
                                        @else
                                            <a href="#"
                                            class="bg-gray-500 text-white text-xs px-4 py-2 rounded-md transition cursor-not-allowed opacity-60 pointer-events-none">
                                                Receipt
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            @else
                                <td class="px-4 py-3 text-sm">
                                    <span class="text-dark opacity-60 font-medium text-center">No order associated</span>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
    @vite('resources/css/pages/my-account/transactions.css')
@endpush

@push('scripts')
    @vite('resources/js/pages/my-account/transactions.js')
@endpush
