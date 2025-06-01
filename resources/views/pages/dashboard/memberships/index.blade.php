@extends('layouts.dashboard_app')

@section('title', 'Memberships')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Memberships</h1>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 border-2 border-gray-300">
        <form method="GET" action="{{ route('dashboard.memberships.index') }}" class="flex flex-wrap gap-2 mb-6 items-end">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..." class="border rounded px-3 py-2 flex-1 min-w-0" />
            <select name="email_verified" class="border rounded px-3 py-2">
                <option value="">All</option>
                <option value="yes" {{ request('email_verified') === 'yes' ? 'selected' : '' }}>Email Verified</option>
                <option value="no" {{ request('email_verified') === 'no' ? 'selected' : '' }}>Email Not Verified</option>
            </select>
            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer">
                <i class="fas fa-search text-white"></i>
                Filter
            </button>
            <a href="{{ route('dashboard.memberships.index') }}" class="bg-orange-400 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer">
                <i class="fas fa-undo"></i>
                Reset
            </a>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-center text-gray-700">
                <thead class="bg-primary text-xs uppercase tracking-wider text-white border-b-2 border-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-gray-800">
                            <a href="{{ route('dashboard.memberships.index', array_merge(request()->all(), ['sort' => 'name', 'direction' => (request('sort') === 'name' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}">
                                Name
                                @if(request('sort') === 'name')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-gray-800">
                            <a href="{{ route('dashboard.memberships.index', array_merge(request()->all(), ['sort' => 'email', 'direction' => (request('sort') === 'email' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}">
                                Email
                                @if(request('sort') === 'email')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-gray-800">
                            <a href="{{ route('dashboard.memberships.index', array_merge(request()->all(), ['sort' => 'created_at', 'direction' => (request('sort') === 'created_at' && request('direction') === 'asc') ? 'desc' : 'asc'])) }}">
                                Joined At
                                @if(request('sort') === 'created_at')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-gray-800">Email Verified</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        <tr class="border-b">
                            <td class="px-4 py-3">
                                <a href="{{ route('dashboard.users.show', $member->id) }}" class="text-blue-700 hover:underline">
                                    {{ $member->name }}
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ $member->email }}</td>
                            <td class="px-4 py-3">{{ $member->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                @if($member->email_verified_at)
                                    <span class="text-green-600 font-bold">Yes</span>
                                @else
                                    <span class="text-red-600 font-bold">No</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-gray-500">No members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $members->links() }}
        </div>
    </div>
</div>
@endsection
