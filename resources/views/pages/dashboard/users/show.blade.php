@extends('layouts.dashboard_app')

@section('title', 'User Details')

@section('content')
<div class="w-full px-0">
    <div class="flex items-center mb-8">
        <a href="{{ url()->previous() }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded shadow text-sm flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <h1 class="text-2xl font-bold ml-4">User #{{ $user->id }} Details</h1>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-8 w-full">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
            <dt class="font-semibold">Name:</dt>
            <dd>{{ $user->name }}</dd>
            <dt class="font-semibold">Email:</dt>
            <dd>{{ $user->email }}</dd>
            <dt class="font-semibold">Email Verified:</dt>
            <dd>
                @if($user->email_verified_at)
                    <span class="text-green-600 font-bold">Yes</span>
                    <span class="text-xs text-gray-500">({{ $user->email_verified_at }})</span>
                @else
                    <span class="text-red-600 font-bold">No</span>
                @endif
            </dd>
            <dt class="font-semibold">Role:</dt>
            <dd>
                @if($user->isBoard())
                    <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">Board</span>
                @elseif($user->isEmployee())
                    <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">Employee</span>
                @elseif($user->isPendingMember())
                    <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full text-xs font-semibold">Pending Member</span>
                @else
                    <span class="bg-green-200 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Member</span>
                @endif
            </dd>
            <dt class="font-semibold">Status:</dt>
            <dd>
                @if($user->deleted_at)
                    <span class="bg-red-200 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Deleted</span>
                @elseif($user->isBlocked())
                    <span class="bg-gray-400 text-white px-2 py-1 rounded-full text-xs font-semibold">Blocked</span>
                @else
                    <span class="bg-green-200 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Active</span>
                @endif
            </dd>
            @if($user->deleted_at)
                <dt class="font-semibold">Deleted At:</dt>
                <dd>{{ $user->deleted_at }}</dd>
            @endif
            @if($user->nif)
                <dt class="font-semibold">NIF:</dt>
                <dd>{{ $user->nif }}</dd>
            @endif
            @if($user->gender)
                <dt class="font-semibold">Gender:</dt>
                <dd>
                    @if($user->gender === 'male' || $user->gender === 'M')
                        Male
                    @elseif($user->gender === 'female' || $user->gender === 'F')
                        Female
                    @else
                        {{ $user->gender }}
                    @endif
                </dd>
            @endif
            @if($user->photo)
                <dt class="font-semibold">Photo:</dt>
                <dd><img src="{{ asset('storage/users/' . $user->photo) }}" alt="User Photo" class="h-16 w-16 rounded-full"></dd>
            @endif
            @if(!$user->isEmployee())
                <dt class="font-semibold">Default Delivery Address:</dt>
                @if($user->default_delivery_address)
                    <dd>{{ $user->default_delivery_address }}</dd>
                @else
                    <dd>Not set</dd>
                @endif
                
                <dt class="font-semibold">Default Payment Type:</dt>
                @if($user->default_payment_type)
                    <dd>{{ $user->default_payment_type }}</dd>
                @else
                    <dd>Not set</dd>
                @endif
                
                <dt class="font-semibold">Default Payment Reference:</dt>
                @if($user->default_payment_reference)
                    <dd>{{ $user->default_payment_reference }}</dd>
                @else
                    <dd>Not set</dd>
                @endif
            @endif
            
            <dt class="font-semibold">Created At:</dt>
            <dd>{{ $user->created_at }}</dd>
            <dt class="font-semibold">Updated At:</dt>
            <dd>{{ $user->updated_at }}</dd>
        </dl>
    </div>
</div>
@endsection
