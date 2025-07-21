@extends('layouts.dashboard_app')

@section('title', 'Add Category')

@section('content')
<div class="container mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ url()->previous() }}" class="bg-green-600 hover:bg-green-600 text-white font-normal px-4 py-2 rounded shadow flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
        <h1 class="text-2xl font-bold ml-4">Add Category</h1>
    </div>

    <form action="{{ route('dashboard.categories.store') }}" method="POST" enctype="multipart/form-data" class="mt-6">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium">Name</label>
            <input type="text" name="name" id="name" class="border border-gray-300 rounded px-4 py-2 w-full" required>
        </div>
        <div class="mb-8">
            <label for="photo" class="block text-sm font-medium form-input-label mb-2">Image</label>

            <div class="flex items-center space-x-4">
                <div class="w-20 h-20 rounded-md overflow-hidden bg-gray-100 border-2 border-gray-300 flex items-center justify-center mr-4">
                    <img id="photoPreview" src="#" alt="Image Preview" class="w-full h-full object-cover rounded-md hidden" />
                    <div id="photoPlaceholder" class="w-full h-full flex items-center justify-center bg-gray-200">
                        <span class="text-gray-400 text-sm">Image</span>
                    </div>
                </div>

                <label for="photo" class="bg-gray-800 inline-flex items-center gap-2 cursor-pointer text-sm font-medium text-white px-4 py-2 rounded-lg shadow-sm transition">
                    <span>Select Image</span>
                    <input type="file" name="image" id="photo" accept=".jpeg, .jpg, .png" class="hidden" onchange="previewImage(event)" />
                </label>
            </div>

            <p class="mt-2 text-xs form-input-label">Choose an image up to 5MB.</p>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow cursor-pointer">Save</button>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/vendors/image-preview.js') }}"></script>
@endpush
