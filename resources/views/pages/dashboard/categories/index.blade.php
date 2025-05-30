@extends('layouts.dashboard_app')

@section('title', 'Categories')

@section('content')
<div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">Categories</h1>

    <!-- Filters -->
    <div class="bg-gray-800 p-5 rounded-xl shadow-lg mb-6">
        <form method="GET" action="{{ route('dashboard.categories.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="col-span-2">
                <label for="name" class="block font-semibold text-white">Name</label>
                <input type="text" name="name" id="name" value="{{ request('name') }}"
                    class="mt-2 block w-full h-12 text-white px-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex flex-col md:flex-row items-end space-y-2 md:space-y-0 md:space-x-2 col-span-2">
                <button type="submit"
                        class="w-full bg-indigo-600 tracking-wider hover:bg-indigo-700 text-white px-8 py-3 rounded-xl shadow-md font-semibold cursor-pointer">
                    Filter
                </button>
                <a href="{{ route('dashboard.categories.index') }}"
                   class="w-full bg-red-500 tracking-wider hover:bg-red-600 text-white px-8 py-3 rounded-xl shadow-md font-semibold text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="overflow-x-auto mb-8">
        <table class="table-auto w-full border-collapse border border-gray-200 shadow-lg rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Image</th>
                    <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">
                        <div class="flex items-center">
                            Name
                            @php
                                $newDir = ($sort === 'name' && $direction === 'asc') ? 'desc' : 'asc';
                                $arrow = $sort === 'name'
                                    ? ($direction === 'asc' ? '▲' : '▼')
                                    : '▲▼';
                                $params = array_merge(request()->all(), ['sort' => 'name', 'direction' => $newDir]);
                                $url = route('dashboard.categories.index', $params);
                            @endphp
                            <a href="{{ $url }}" class="ml-2 text-gray-700 hover:underline text-base">{{ $arrow }}</a>
                        </div>
                    </th>
                    <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 px-6 py-4">
                        @if($category->image)
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="h-12 rounded">
                        @else
                            <span class="text-gray-500 text-sm">No Image</span>
                        @endif
                    </td>
                    <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $category->name }}</td>
                    <td class="border border-gray-300 px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('dashboard.categories.edit', $category->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded shadow text-sm cursor-pointer flex items-center space-x-1">
                                <i class="fas fa-edit"></i>
                                <span>Edit</span>
                            </a>
                            <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="POST" class="inline delete-item-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="bg-red-700 hover:bg-red-800 text-white px-3 py-1.5 rounded shadow text-sm cursor-pointer flex items-center space-x-1 delete-item-btn">
                                    <i class="fas fa-trash text-sm"></i>
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $categories->links() }}

    @include('components.modal.index', [
        'title' => 'Delete Category',
        'subtitle' => 'Are you sure you want to delete this category? This action cannot be undone.',
        'confirmButtonText' => 'Delete'
    ])
</div>
@endsection
