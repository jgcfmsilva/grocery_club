@extends('layouts.dashboard_app')

@section('title', 'Virtual Cards')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Virtual Cards</h1>

    <div class="bg-white rounded-lg shadow-lg p-6 border-2 border-gray-300">
        <div class="flex justify-between items-center mb-6">
            <form method="GET" action="{{ route('dashboard.virtual-cards.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by card number..." class="border rounded px-3 py-2" />
                <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-search text-white"></i>
                    Search
                </button>
            </form>
            <a href="{{ route('dashboard.virtual-cards.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm">
                Add Virtual Card
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-center text-gray-700">
                <thead class="bg-primary text-xs uppercase tracking-wider text-white border-b-2 border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-gray-800">#</th>
                        <th class="px-4 py-3 text-gray-800">Card Number</th>
                        <th class="px-4 py-3 text-gray-800">Owner</th>
                        <th class="px-4 py-3 text-gray-800">Balance</th>
                        <th class="px-4 py-3 text-gray-800">Last Transaction</th>
                        <th class="px-4 py-3 text-gray-800">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($virtualCards as $card)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ $card->id }}</td>
                            <td class="px-4 py-3 font-mono">{{ $card->card_number }}</td>
                            <td class="px-4 py-3">{{ optional($card->user)->name ?? '-' }}</td>
                            <td class="px-4 py-3 font-semibold">{{ number_format($card->balance, 2, ',', '.') }}€</td>
                            <td class="px-4 py-3">
                                @php
                                    $lastOp = $card->operations()->latest('created_at')->first();
                                    $isCredit = false;
                                    if ($lastOp) {
                                        if (is_object($lastOp->type) && method_exists($lastOp->type, 'value')) {
                                            $isCredit = $lastOp->type->value === \App\Enums\TransactionType::Credit;
                                        } elseif (is_string($lastOp->type)) {
                                            $isCredit = $lastOp->type === \App\Enums\TransactionType::Credit;
                                        }
                                    }
                                @endphp
                                @if($lastOp)
                                    <span class="block text-xs text-gray-800">
                                        {{ $lastOp->created_at->format('d/m/Y H:i') }}<br>
                                        <span class="font-semibold">
                                            {{ method_exists($lastOp->type, 'label') ? $lastOp->type->label() : (is_string($lastOp->type) ? ucfirst($lastOp->type) : '-') }}
                                        </span>
                                        
                                        <span class="{{ $isCredit ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}">
                                            {{ $isCredit ? '+' : '-' }}{{ number_format($lastOp->value, 2, ',', '.') }}€
                                        </span>
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('dashboard.virtual-cards.show', $card->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">View</a>
                                <a href="{{ route('dashboard.virtual-cards.edit', $card->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs ml-1">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-gray-500">No virtual cards found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $virtualCards->links() }}
        </div>
    </div>
</div>
@endsection
