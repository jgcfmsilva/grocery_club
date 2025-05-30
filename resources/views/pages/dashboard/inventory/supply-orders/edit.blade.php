@extends('layouts.dashboard_app')

@section('title', 'Edit Supply Order')

@section('content')
<div class="w-full max-w-lg mx-auto">
    <div class="flex items-center mb-8">
        <a href="{{ route('dashboard.inventory.supply-orders.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded shadow text-sm flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <h1 class="text-2xl font-bold ml-4">Edit Supply Order #{{ $supplyOrder->id }}</h1>
    </div>
    <form action="{{ route('dashboard.inventory.supply-orders.update', $supplyOrder->id) }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
        @csrf
        @method('PUT')
        <div class="mb-6">
            <label for="status" class="block font-semibold mb-2">Status</label>
            <select name="status" id="status" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition">
                <option value="requested" {{ $supplyOrder->status == 'requested' ? 'selected' : '' }}>Requested</option>
                <option value="completed" {{ $supplyOrder->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="canceled" {{ $supplyOrder->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </div>
        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow transition duration-200 flex items-center gap-2 text-base">
                <i class="fas fa-save"></i>
                Update
            </button>
            <a href="{{ route('dashboard.inventory.supply-orders.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg shadow transition duration-200 flex items-center gap-2 text-base">
                <i class="fas fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection