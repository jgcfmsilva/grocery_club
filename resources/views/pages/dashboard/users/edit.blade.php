@extends('layouts.dashboard_app')

@section('title', 'Edit User')

@section('content')
<div class="w-full max-w-lg mx-auto">
    <div class="flex items-center mb-8">
        <a href="{{ route('dashboard.users.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded shadow text-sm flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <h1 class="text-2xl font-bold ml-4">Edit User #{{ $user->id }}</h1>
    </div>
    <form action="{{ route('dashboard.users.update', $user->id) }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
        @csrf
        @method('PUT')
        <div class="mb-6">
            <label for="name" class="block font-semibold mb-2">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                   class="border rounded px-3 py-2 w-full" required>
        </div>
        <div class="mb-6">
            <label for="email" class="block font-semibold mb-2">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                   class="border rounded px-3 py-2 w-full" required>
        </div>
        <div class="mb-6">
            <label for="type" class="block font-semibold mb-2">Role</label>
            <select name="type" id="type" class="border rounded px-3 py-2 w-full" required>
                <option value="pending_member" {{ $user->isPendingMember() ? 'selected' : '' }}>Pending Member</option>
                <option value="member" {{ $user->isMember() ? 'selected' : '' }}>Member</option>
                <option value="employee" {{ $user->isEmployee() ? 'selected' : '' }}>Employee</option>
                <option value="board" {{ $user->isBoard() ? 'selected' : '' }}>Board</option>
            </select>
        </div>
        <div class="mb-6">
            <label for="blocked" class="block font-semibold mb-2">Blocked</label>
            <select name="blocked" id="blocked" class="border rounded px-3 py-2 w-full">
                <option value="0" {{ !$user->isBlocked() ? 'selected' : '' }}>No</option>
                <option value="1" {{ $user->isBlocked() ? 'selected' : '' }}>Yes</option>
            </select>
        </div>
        <div class="flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow transition duration-200 flex items-center gap-2 text-base">
                <i class="fas fa-save"></i>
                Update
            </button>
            <a href="{{ route('dashboard.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg shadow transition duration-200 flex items-center gap-2 text-base">
                <i class="fas fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
