@extends('layouts.dashboard_app')

@section('title', 'Virtual Cards')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Virtual Cards</h1>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 border-2 border-gray-300">
        <div class="flex justify-between items-center mb-6">
            <form method="GET" action="{{ route('dashboard.virtual-cards.index') }}" class="flex gap-2 w-full max-w-xl">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by card number or user name..." class="border rounded px-3 py-2 flex-1 min-w-0" />
                <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-search text-white"></i>
                    Search
                </button>
                <a href="{{ route('dashboard.virtual-cards.index') }}" class="bg-orange-400 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-undo"></i>
                    Reset
                </a>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-center text-gray-700">
                <thead class="bg-primary text-xs uppercase tracking-wider text-white border-b-2 border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-gray-800">#</th>
                        <th class="px-4 py-3 text-gray-800">
                            <a href="{{ route('dashboard.virtual-cards.index', array_merge(request()->all(), ['sort' => 'card_number', 'direction' => (request('sort') === 'card_number' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}">
                                Card Number
                                @if(request('sort') === 'card_number')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-gray-800">
                            <a href="{{ route('dashboard.virtual-cards.index', array_merge(request()->all(), ['sort' => 'owner', 'direction' => (request('sort') === 'owner' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}">
                                Owner
                                @if(request('sort') === 'owner')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-gray-800">
                            <a href="{{ route('dashboard.virtual-cards.index', array_merge(request()->all(), ['sort' => 'balance', 'direction' => (request('sort') === 'balance' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}">
                                Balance
                                @if(request('sort') === 'balance')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-gray-800">
                            <a href="{{ route('dashboard.virtual-cards.index', array_merge(request()->all(), ['sort' => 'last_transaction', 'direction' => (request('sort') === 'last_transaction' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}">
                                Last Transaction
                                @if(request('sort') === 'last_transaction')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
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
                                    $lastOp = $card->operations->sortByDesc('created_at')->first();
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
