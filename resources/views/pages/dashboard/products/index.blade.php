@extends('layouts.dashboard_app')

@section('title', 'Products')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Products</h1>
        <a href="{{ route('dashboard.products.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded shadow flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Add Product</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 p-5 rounded-xl shadow-lg mb-6">
        <form method="GET" action="{{ route('dashboard.products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="col-span-1">
                <label for="name" class="block font-semibold text-white">Name</label>
                <input type="text" name="name" id="name" value="{{ request('name') }}"
                    class="mt-2 block w-full h-12 text-white px-3 border-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="col-span-1">
                <label for="category" class="block font-semibold text-white">Category</label>
                <select name="category" id="category"
                        class="mt-2 block w-full h-12 text-white border-2 border-gray-300 px-3 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option class="text-black" value="">All</option>
                    @foreach($categories as $category)
                        <option class="text-black" value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-1">
                <label for="price_range" class="block font-semibold text-white">Price Range</label>
                <div class="flex space-x-4 mt-2">
                    <input type="number" name="price_min" id="price_min" placeholder="Min" value="{{ request('price_min') }}"
                        class="block w-full h-12 text-white border-2 border-gray-300 px-3 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <input type="number" name="price_max" id="price_max" placeholder="Max" value="{{ request('price_max') }}"
                        class="block w-full h-12 text-white border-2 border-gray-300 px-3 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div class="col-span-1 flex items-end space-x-2">
                <button type="submit"
                        class="w-full bg-indigo-600 tracking-wider hover:bg-indigo-700 text-white px-8 py-3 rounded-xl shadow-md font-semibold cursor-pointer">
                    Filter
                </button>
                <a href="{{ route('dashboard.products.index') }}"
                   class="w-full bg-red-500 tracking-wider hover:bg-red-600 text-white px-8 py-3 rounded-xl shadow-md font-semibold text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="overflow-x-auto mb-8">
        @if($products->isEmpty())
            <div class="text-center text-gray-500 text-lg font-semibold py-6">
                No products found.
            </div>
        @else
            <table class="table-auto w-full border-collapse border border-gray-200 shadow-lg rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Image</th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">
                            <a href="{{ route('dashboard.products.index', ['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center space-x-1">
                                <span class="me-2">Name</span>
                                @if(request('sort') === 'name')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">
                            <a href="{{ route('dashboard.products.index', ['sort' => 'category', 'direction' => request('sort') === 'category' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center space-x-1">
                                <span class="me-2">Category</span>
                                @if(request('sort') === 'category')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">
                            <a href="{{ route('dashboard.products.index', ['sort' => 'price', 'direction' => request('sort') === 'price' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center space-x-1">
                                <span class="me-2">Price</span>
                                @if(request('sort') === 'price')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">
                            <a href="{{ route('dashboard.products.index', ['sort' => 'stock', 'direction' => request('sort') === 'stock' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="flex items-center space-x-1">
                                <span class="me-2">Stock</span>
                                @if(request('sort') === 'stock')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </a>
                        </th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Discount</th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Discounted Price</th>
                        <th class="border border-gray-300 px-6 py-3 text-left text-lg font-bold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-6 py-4">
                            @if($product->photo)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-12 rounded">
                            @else
                                <span class="text-gray-500 text-sm">No Image</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $product->name }}</td>
                        <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $product->category->name }}</td>
                        <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">{{ $product->price }}€</td>
                        <td class="border border-gray-300 px-6 py-4 text-md">
                            @if($product->stock <= $product->stock_lower_limit)
                                <span class="text-red-600 font-bold">{{ $product->stock }} <span class="text-sm">(Low stock)</span></span>
                            @elseif($product->stock >= $product->stock_upper_limit)
                                <span class="text-green-600 font-bold">{{ $product->stock }} <span class="text-sm">(High stock)</span></span>
                            @else
                                <span class="text-gray-800">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">
                            @if($product->hasDiscount())
                                {{ $product->discount }} € (Min Qty: {{ $product->discount_min_qty }})
                            @else
                                <span class="text-gray-500 text-sm">No Discount</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 px-6 py-4 text-md text-gray-800">
                            @if($product->hasDiscount())
                                {{ $product->getPriceWithDiscount() }} €
                            @else
                                <span class="text-gray-500 text-sm">N/A</span>
                            @endif
                        </td>
                        <td class="border border-gray-300 px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('dashboard.products.show', $product->id) }}" class="bg-gray-700 hover:bg-gray-800 text-white px-3 py-1.5 rounded shadow text-sm cursor-pointer flex items-center space-x-1">
                                    <i class="fas fa-eye"></i>
                                    <span>View</span>
                                </a>
                                <a href="{{ route('dashboard.products.edit', $product->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded shadow text-sm cursor-pointer flex items-center space-x-1">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                                <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST" class="inline delete-item-form">
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
        @endif
    </div>
    {{ $products->appends(request()->except('page'))->links() }}

    @include('components.modal.index', [
        'title' => 'Delete Product',
        'subtitle' => 'Are you sure you want to delete this product? This action cannot be undone.',
        'confirmButtonText' => 'Delete'
    ])
</div>
@endsection
