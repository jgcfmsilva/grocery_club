@extends('layouts.dashboard_app')

@section('title', 'Inventory')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Inventory</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-4 flex justify-between items-center">
            <form method="GET" action="{{ route('dashboard.inventory.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product..." class="border rounded px-3 py-2" />
                <select name="stock_filter" class="border rounded px-3 py-2">
                    <option value="">All Stock</option>
                    <option value="out" {{ request('stock_filter') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                    <option value="below_min" {{ request('stock_filter') == 'below_min' ? 'selected' : '' }}>Below Minimum</option>
                    <option value="high" {{ request('stock_filter') == 'high' ? 'selected' : '' }}>High Stock</option>
                </select>
                <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-search text-white"></i>
                    Search
                </button>
            </form>
            <div class="flex gap-2">
                @if(!isEmployee())
                <a href="{{ route('dashboard.products.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm">
                    Add Product
                </a>
                @endif
                @if(isEmployee() || isBoard())
                <a href="{{ route('dashboard.inventory.supply-orders.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm">
                    Supply Orders
                </a>
                @endif
            </div>
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
                        @php
                            $show = true;
                            if(request('stock_filter') == 'out' && $product->stock > 0) $show = false;
                            if(request('stock_filter') == 'below_min' && $product->stock > $product->stock_lower_limit) $show = false;
                            if(request('stock_filter') == 'high' && $product->stock < $product->stock_upper_limit) $show = false;
                        @endphp
                        @if($show)
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
                                @if(!isEmployee())
                                    <a href="{{ route('dashboard.products.edit', $product->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">Edit</a>
                                @endif
                                <a href="{{ route('dashboard.products.show', $product->id) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs ml-1">View</a>
                                @if(isEmployee() || isBoard())
                                <button type="button"
                                    class="bg-orange-500 hover:bg-orange-600 text-white px-2 py-1 rounded text-xs ml-1 cursor-pointer"
                                    onclick="openAdjustStockModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->stock }})">
                                    Adjust Stock
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endif
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

<!-- Modal -->
<div id="adjustStockModal" class="fixed inset-0 bg-opacity-20 backdrop-blur-sm flex items-center justify-center hidden">
    <div class="bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-md relative">
        <button type="button" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl" onclick="closeAdjustStockModal()">&times;</button>
        <h2 class="text-xl text-gray-100 font-bold mb-6 tracking-wider">Adjust Stock</h2>
        <div class="mb-4 bg-gray-700 p-4 rounded-lg">
            <div class="flex items-center gap-2">
                <span class="text-white font-semibold">Product:</span>
                <span id="modal_product_name" class="text-gray-200"></span>
            </div>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-white font-semibold">Current Stock:</span>
                <span id="modal_current_stock" class="text-gray-200"></span>
            </div>
        </div>
        <form id="adjustStockForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="modal_new_stock" class="block text-white font-semibold mb-2 tracking-wider">New Stock</label>
                <input type="number" name="new_stock" id="modal_new_stock" min="0" class="border-1 border-white text-gray-300 rounded px-3 py-2 w-full" required>
            </div>
            <div class="mb-4">
                <label for="modal_reason" class="block text-white font-semibold mb-2 tracking-wider">Reason (optional)</label>
                <input type="text" name="reason" id="modal_reason" class="border-1 border-gray-300 text-gray-300 rounded px-3 py-2 w-full">
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeAdjustStockModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2 cursor-pointer tracking-wider">Cancel</button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold cursor-pointer tracking-wider">Save</button>
            </div>
        </form>
    </div>
</div>
<script>
    function openAdjustStockModal(productId, productName, currentStock) {
        document.getElementById('adjustStockModal').classList.remove('hidden');
        document.getElementById('modal_product_name').textContent = productName;
        document.getElementById('modal_current_stock').textContent = currentStock;
        document.getElementById('modal_new_stock').value = currentStock;
        document.getElementById('modal_reason').value = '';
        var form = document.getElementById('adjustStockForm');
        form.action = "{{ route('dashboard.inventory.index') }}".replace('/inventory', '/inventory/' + productId + '/adjust-stock');
    }
    function closeAdjustStockModal() {
        document.getElementById('adjustStockModal').classList.add('hidden');
    }
</script>
@endsection
