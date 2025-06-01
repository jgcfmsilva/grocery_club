@extends('layouts.dashboard_app')

@section('title', 'All Transactions')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">All Transactions</h1>

    <div class="bg-white rounded-lg shadow-lg p-6 border-2 border-gray-300">
        <form method="GET" action="{{ route('dashboard.virtual-cards.transactions') }}" class="flex gap-2 w-full max-w-xl mb-6">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by payment reference or user name..." class="border rounded px-3 py-2 flex-1 min-w-0" />
            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer">
                <i class="fas fa-search text-white"></i>
                Search
            </button>
            <a href="{{ route('dashboard.virtual-cards.transactions') }}" class="bg-orange-400 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer">
                <i class="fas fa-undo"></i>
                Reset
            </a>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-center text-gray-700">
                <thead class="bg-primary text-xs uppercase tracking-wider text-white border-b-2 border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-gray-800">#</th>
                        <th class="px-4 py-3 text-gray-800">User</th>
                        <th class="px-4 py-3 text-gray-800">Card Number</th>
                        <th class="px-4 py-3 text-gray-800">Type</th>
                        <th class="px-4 py-3 text-gray-800">Credit/Debit Type</th>
                        <th class="px-4 py-3 text-gray-800">Value</th>
                        <th class="px-4 py-3 text-gray-800">Payment Reference</th>
                        <th class="px-4 py-3 text-gray-800">Order</th>
                        <th class="px-4 py-3 text-gray-800">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($operations as $op)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ $op->id }}</td>
                            <td class="px-4 py-3">
                                @if($op->card && $op->card->user)
                                    <a href="{{ route('dashboard.users.show', $op->card->user->id) }}" class="text-blue-700 hover:underline">
                                        {{ $op->card->user->name }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 font-mono">{{ $op->card ? $op->card->card_number : '-' }}</td>
                            <td class="px-4 py-3">
                                @if(is_object($op->type) && method_exists($op->type, 'label'))
                                    {{ $op->type->label() }}
                                @elseif(is_string($op->type))
                                    {{ ucfirst($op->type) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if((is_object($op->type) && $op->type->value === \App\Enums\TransactionType::Credit) || $op->type === \App\Enums\TransactionType::Credit)
                                    @if(is_object($op->credit_type) && method_exists($op->credit_type, 'label'))
                                        {{ $op->credit_type->label() }}
                                    @elseif(is_string($op->credit_type))
                                        {{ ucfirst($op->credit_type) }}
                                    @else
                                        -
                                    @endif
                                @elseif((is_object($op->type) && $op->type->value === \App\Enums\TransactionType::Debit) || $op->type === \App\Enums\TransactionType::Debit)
                                    @if(is_object($op->debit_type) && method_exists($op->debit_type, 'label'))
                                        {{ $op->debit_type->label() }}
                                    @elseif(is_string($op->debit_type))
                                        {{ ucfirst($op->debit_type) }}
                                    @else
                                        -
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 {{ (is_object($op->type) && $op->type->value === \App\Enums\TransactionType::Credit) || $op->type === \App\Enums\TransactionType::Credit ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}">
                                {{ (is_object($op->type) && $op->type->value === \App\Enums\TransactionType::Credit) || $op->type === \App\Enums\TransactionType::Credit ? '+' : '-' }}
                                {{ number_format($op->value, 2, ',', '.') }}€
                            </td>
                            <td class="px-4 py-3">{{ $op->payment_reference ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($op->order)
                                    <a href="{{ route('dashboard.orders.show', $op->order->id) }}" class="text-blue-700 hover:underline">#{{ $op->order->id }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $op->created_at ? $op->created_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-6 text-gray-500">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $operations->links() }}
        </div>
    </div>
</div>
@endsection
