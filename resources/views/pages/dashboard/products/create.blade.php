@extends('layouts.dashboard_app')

@section('title', 'Add Product')

@section('content')
<div class="container mx-auto">
    <div class="flex items-center mb-6">
        <a href="{{ url()->previous() }}" class="bg-green-600 hover:bg-green-600 text-white font-normal px-4 py-2 rounded shadow flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
        <h1 class="text-2xl font-bold ml-4">Add Product</h1>
    </div>

    <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data" class="mt-6">
        @csrf

        <!-- General Information -->
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div>
                <label for="name" class="block text-sm font-medium">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="border border-gray-300 rounded px-4 py-2 w-full" required>
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium">Category</label>
                <select name="category_id" id="category_id" class="border border-gray-300 rounded px-4 py-2 w-full cursor-pointer" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium">Price (€)</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" step="0.01" class="border border-gray-300 rounded px-4 py-2 w-full" required>
            </div>
        </div>

        <!-- Stock Information -->
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div>
                <label for="stock" class="block text-sm font-medium">Stock</label>
                <input type="number" name="stock" id="stock" min="0"value="{{ old('stock') }}" class="border border-gray-300 rounded px-4 py-2 w-full" required>
            </div>
            <div>
                <label for="stock_lower_limit" class="block text-sm font-medium">Stock Lower Limit</label>
                <input type="number" name="stock_lower_limit" id="stock_lower_limit" min="0" value="{{ old('stock_lower_limit') }}" class="border border-gray-300 rounded px-4 py-2 w-full" required>
            </div>
            <div>
                <label for="stock_upper_limit" class="block text-sm font-medium">Stock Upper Limit</label>
                <input type="number" name="stock_upper_limit" id="stock_upper_limit" min="0" value="{{ old('stock_upper_limit') }}" class="border border-gray-300 rounded px-4 py-2 w-full" required>
            </div>
        </div>

        <!-- Discount Information -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="discount" class="block text-sm font-medium">Discount (€)</label>
                <input type="number" name="discount" id="discount" value="{{ old('discount') }}" min="0" step="0.01" class="border border-gray-300 rounded px-4 py-2 w-full">
            </div>
            <div>
                <label for="discount_min_qty" class="block text-sm font-medium">Discount Minimum Quantity</label>
                <input type="number" name="discount_min_qty" id="discount_min_qty" min="0" value="{{ old('discount_min_qty') }}" class="border border-gray-300 rounded px-4 py-2 w-full">
            </div>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium">Description</label>
            <textarea name="description" id="description" rows="4" class="border border-gray-300 rounded px-4 py-2 w-full" required>{{ old('description') }}</textarea>
        </div>

        <!-- Image Upload -->
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
                    <input type="file" name="photo" id="photo" accept=".jpeg, .jpg, .png" class="hidden" onchange="previewImage(event)" />
                </label>
            </div>

            <p class="mt-2 text-xs form-input-label">Choose an image up to 2MB.</p>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow cursor-pointer">Save</button>
    </form>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/vendors/image-preview.js') }}"></script>
@endpush
