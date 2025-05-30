@extends('layouts.dashboard_app')

@section('title', 'Users')

@section('content')
<div class="w-full px-0">
    <h1 class="text-2xl font-bold mb-8">Users</h1>

    <!-- Filters -->
    <div class="bg-gray-800 p-5 rounded-xl shadow-lg mb-6">
        <form method="GET" action="{{ route('dashboard.users.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div>
                <label for="name" class="block font-semibold text-white">Name</label>
                <input type="text" name="name" id="name" value="{{ request('name') }}"
                    class="mt-2 block w-full h-12 text-white px-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="email" class="block font-semibold text-white">Email</label>
                <input type="text" name="email" id="email" value="{{ request('email') }}"
                    class="mt-2 block w-full h-12 text-white px-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="type" class="block font-semibold text-white">Role</label>
                <select name="type" id="type"
                        class="mt-2 block w-full h-12 text-white border-2 border-gray-300 px-3 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option class="text-black" value="">All</option>
                    <option class="text-black" value="pending_member" {{ request('type') == 'pending_member' ? 'selected' : '' }}>Pending Member</option>
                    <option class="text-black" value="member" {{ request('type') == 'member' ? 'selected' : '' }}>Member</option>
                    <option class="text-black" value="employee" {{ request('type') == 'employee' ? 'selected' : '' }}>Employee</option>
                    <option class="text-black" value="board" {{ request('type') == 'board' ? 'selected' : '' }}>Board</option>
                </select>
            </div>
            <div>
                <label for="status" class="block font-semibold text-white">Status</label>
                <select name="status" id="status"
                        class="mt-2 block w-full h-12 text-white border-2 border-gray-300 px-3 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option class="text-black" value="">All</option>
                    <option class="text-black" value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option class="text-black" value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                    <option class="text-black" value="deleted" {{ request('status') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>
            <div class="flex flex-col md:flex-row items-end gap-2">
                <button type="submit"
                        class="w-full md:w-auto bg-indigo-600 tracking-wider hover:bg-indigo-700 text-white px-8 py-3 rounded-xl shadow-md font-semibold cursor-pointer">
                    Filter
                </button>
                <a href="{{ route('dashboard.users.index') }}"
                   class="w-full md:w-auto bg-red-500 tracking-wider hover:bg-red-600 text-white px-8 py-3 rounded-xl shadow-md font-semibold text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto mb-8">
        <table class="min-w-full w-full border-collapse border border-gray-200 shadow-lg rounded-lg text-sm text-center">
            <thead class="bg-gray-100">
                <tr>
                    @php
                        $sort = request('sort', 'id');
                        $direction = request('direction', 'asc');
                        function sort_link_user($label, $column) {
                            $dir = request('direction', 'asc');
                            $isCurrent = request('sort') === $column;
                            $newDir = ($isCurrent && $dir === 'asc') ? 'desc' : 'asc';
                            $arrow = $isCurrent
                                ? ($dir === 'asc' ? '▲' : '▼')
                                : '▲▼';
                            $params = array_merge(request()->all(), ['sort' => $column, 'direction' => $newDir]);
                            $url = route('dashboard.users.index', $params);
                            return '<a href="'.$url.'" class="text-gray-800 hover:underline flex items-center justify-center">'.$label.'<span class="ml-1 text-xs">'.$arrow.'</span></a>';
                        }
                    @endphp
                    <th class="border border-gray-300 px-4 py-3 font-bold text-gray-700">{!! sort_link_user('ID', 'id') !!}</th>
                    <th class="border border-gray-300 px-4 py-3 font-bold text-gray-700">{!! sort_link_user('Name', 'name') !!}</th>
                    <th class="border border-gray-300 px-4 py-3 font-bold text-gray-700">{!! sort_link_user('Email', 'email') !!}</th>
                    <th class="border border-gray-300 px-4 py-3 font-bold text-gray-700">{!! sort_link_user('Role', 'type') !!}</th>
                    <th class="border border-gray-300 px-4 py-3 font-bold text-gray-700">{!! sort_link_user('Status', 'status') !!}</th>
                    <th class="border border-gray-300 px-4 py-3 font-bold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-4 py-2">{{ $user->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        @if($user->isBoard())
                            <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full text-xs font-semibold">Board</span>
                        @elseif($user->isEmployee())
                            <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">Employee</span>
                        @elseif($user->isPendingMember())
                            <span class="bg-gray-200 text-gray-800 px-2 py-1 rounded-full text-xs font-semibold">Pending Member</span>
                        @else
                            <span class="bg-green-200 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Member</span>
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        @if($user->deleted_at)
                            <span class="bg-red-200 text-red-800 px-2 py-1 rounded-full text-xs font-semibold">Deleted</span>
                        @elseif($user->isBlocked())
                            <span class="bg-gray-400 text-white px-2 py-1 rounded-full text-xs font-semibold">Blocked</span>
                        @else
                            <span class="bg-green-200 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">Active</span>
                        @endif
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        <div class="flex flex-wrap gap-2 justify-center">
                            @if(!$user->deleted_at)
                                @if($user->isEmployee())
                                    <a href="{{ route('dashboard.users.edit', $user->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs flex items-center gap-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this employee?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs flex items-center gap-1 cursor-pointer">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                                @if($user->isMember() && !$user->isBoard() && $user->id !== auth()->id())
                                    <form action="{{ route('dashboard.users.promote', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs flex items-center gap-1 cursor-pointer">
                                            <i class="fas fa-arrow-up"></i> Promote to Board
                                        </button>
                                    </form>
                                @endif
                                @if($user->isBoard() && $user->id !== auth()->id())
                                    <form action="{{ route('dashboard.users.demote', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white px-3 py-1 rounded text-xs flex items-center gap-1 cursor-pointer">
                                            <i class="fas fa-arrow-down"></i> Revoke Board
                                        </button>
                                    </form>
                                @endif
                                @if($user->isMember() && !$user->isBoard() && $user->id !== auth()->id())
                                    @if(!$user->isBlocked())
                                    <form action="{{ route('dashboard.users.block', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs flex items-center gap-1 cursor-pointer">
                                            <i class="fas fa-ban"></i> Block
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('dashboard.users.unblock', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs flex items-center gap-1 cursor-pointer">
                                            <i class="fas fa-unlock"></i> Unblock
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('dashboard.users.cancel-membership', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this membership?');">
                                        @csrf
                                        <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-3 py-1 rounded text-xs flex items-center gap-1 cursor-pointer">
                                            <i class="fas fa-user-slash"></i> Cancel Membership
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-gray-500 text-lg">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-8">
        {{ $users->links() }}
    </div>
</div>
@endsection
