@extends('layouts.dashboard_app')

@section('title', 'Add Virtual Card')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Add Virtual Card</h1>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-4">
            <a href="{{ route('dashboard.virtual-cards.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded shadow text-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <form method="POST" action="#">
            @csrf
            {{-- Adapte o action para a rota de store se necessário --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Card Number</label>
                <input type="text" name="card_number" value="{{ old('card_number') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Balance</label>
                <input type="number" name="balance" value="{{ old('balance', 0) }}" step="0.01" class="w-full border rounded px-3 py-2" required>
            </div>
            {{-- Adicione outros campos necessários --}}
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded shadow">Create Card</button>
            </div>
        </form>
    </div>
</div>
@endsection
