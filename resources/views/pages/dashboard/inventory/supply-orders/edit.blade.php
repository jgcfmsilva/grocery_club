@extends('layouts.dashboard_app')

@section('title', 'Edit Supply Order')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Supply Order #{{ $supplyOrder->id }}</h1>
    <form action="{{ route('dashboard.inventory.supply-orders.update', $supplyOrder->id) }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="status" class="block font-semibold mb-2">Status</label>
            <select name="status" id="status" class="border rounded px-3 py-2 w-full">
                <option value="requested" {{ $supplyOrder->status == 'requested' ? 'selected' : '' }}>Requested</option>
                <option value="completed" {{ $supplyOrder->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="canceled" {{ $supplyOrder->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('dashboard.inventory.supply-orders.index') }}" class="btn btn-secondary ml-2">Cancel</a>
    </form>
</div>
@endsection