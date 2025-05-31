@extends('layouts.dashboard_app')

@section('title', 'Edit Virtual Card')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Virtual Card #{{ $card->id }}</h1>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-4">
            <a href="{{ route('dashboard.virtual-cards.index') }}" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded shadow text-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        <form method="POST" action="#">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">Card Number</label>
                <input type="text" name="card_number" value="{{ $card->card_number }}" class="w-full border rounded px-3 py-2" disabled>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Owner</label>
                <input type="text" value="{{ optional($card->user)->name ?? '-' }}" class="w-full border rounded px-3 py-2" disabled>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Balance</label>
                <input type="text" name="balance" value="{{ number_format($card->balance, 2, ',', '.') }}" class="w-full border rounded px-3 py-2" disabled>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
