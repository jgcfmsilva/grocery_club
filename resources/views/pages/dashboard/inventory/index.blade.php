@extends('layouts.dashboard_app')

@section('title', 'Inventory')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-6">Inventory</h1>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-4 flex justify-between items-center">
            <form method="GET" action="{{ route('dashboard.inventory.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product..." class="border rounded px-3 py-2" />
                <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-search text-white"></i>
                    Search
                </button>
            </form>
            <a href="{{ route('dashboard.products.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm">
                Add Product
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-center text-gray-700">
                <thead class="bg-primary text-xs uppercase tracking-wider text-white">
                    <tr>
                        @php
                            $currentSort = request('sort', 'id');
                            $currentDir = request('direction', 'desc');
                            function sort_link($label, $column) {
                                $dir = request('direction', 'desc');
                                $isCurrent = request('sort') === $column;
                                $newDir = ($isCurrent && $dir === 'asc') ? 'desc' : 'asc';
                                $arrow = $isCurrent
                                    ? ($dir === 'asc' ? '<span class="ml-1 text-xs font-bold">▲</span>' : '<span class="ml-1 text-xs font-bold">▼</span>')
                                    : '<span class="ml-1 text-xs text-gray-700">▲▼</span>';
                                $params = array_merge(request()->all(), ['sort' => $column, 'direction' => $newDir]);
                                $url = route('dashboard.inventory.index', $params);
                                return '<a href="'.$url.'" class="text-gray-800 hover:underline flex items-center justify-center">'.$label.$arrow.'</a>';
                            }
                        @endphp
                        <th class="px-4 py-3">{!! sort_link('ID', 'id') !!}</th>
                        <th class="px-4 py-3">{!! sort_link('Product', 'name') !!}</th>
                        <th class="px-4 py-3">{!! sort_link('Category', 'category') !!}</th>
                        <th class="px-4 py-3">{!! sort_link('Stock', 'stock') !!}</th>
                        <th class="px-4 py-3">{!! sort_link('Lower Limit', 'stock_lower_limit') !!}</th>
                        <th class="px-4 py-3">{!! sort_link('Upper Limit', 'stock_upper_limit') !!}</th>
                        <th class="px-4 py-3">{!! sort_link('Stock Level', 'stock_level') !!}</th>
                        <th class="px-4 py-3 text-gray-800">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ $product->id }}</td>
                            <td class="px-4 py-3 text-left">
                                <div class="flex items-center gap-2">
                                    @if($product->photo)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-8 h-8 object-cover rounded" />
                                    @endif
                                    <span>{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $product->category->name }}</td>
                            <td class="px-4 py-3 font-bold">{{ $product->stock }}</td>
                            <td class="px-4 py-3">{{ $product->stock_lower_limit }}</td>
                            <td class="px-4 py-3">{{ $product->stock_upper_limit }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $stockLevel = \App\Enums\ProductStockLevel::fromStock($product->stock, $product->stock_lower_limit, $product->stock_upper_limit);
                                @endphp
                                <span class="{{ $stockLevel->badgeClass() }} px-2 py-1 rounded text-xs font-semibold">
                                    {{ $stockLevel->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('dashboard.products.edit', $product->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">Edit</a>
                                <a href="{{ route('dashboard.products.show', $product->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs ml-1">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-6 text-gray-500">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
