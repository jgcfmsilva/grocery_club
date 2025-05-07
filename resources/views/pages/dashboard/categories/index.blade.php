@extends('layouts.dashboard_app')

@section('title', 'Categories')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Categories</h1>
        <a href="{{ route('dashboard.categories.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded shadow flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add Category</span>
        </a>
    </div>

    <!-- Categories Table -->
    <div class="overflow-x-auto mb-8">
        <table class="table-auto w-full border-collapse border border-gray-200 shadow-lg rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Image</th>
                    <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Name</th>
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
