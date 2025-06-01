@extends('layouts.dashboard_app')

@section('title', 'Settings')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Settings</h1>
    </div>

    <div class="bg-white rounded-xl shadow p-6 mb-10 w-full">
        <h2 class="text-lg font-semibold mb-4">Membership Fee (€)</h2>
        <form action="{{ route('dashboard.settings.update') }}" method="POST" class="flex flex-col md:flex-row gap-6 items-end">
            @csrf
            <div class="flex-1">
                <input type="number" min="0" step="1" name="membership_fee" value="{{ old('membership_fee', $settings['membership_fee'] ?? '') }}" class="border rounded px-3 py-2 w-full" required>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow font-semibold cursor-pointer">
                Save
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow p-6 w-full">
        <h2 class="text-lg font-semibold mb-4">Shipping Costs</h2>
        <form action="{{ route('dashboard.settings.shipping-cost.add') }}" method="POST" class="flex flex-wrap gap-4 mb-6 items-end">
            @csrf
            <input type="number" min="0" step="1" name="min_value_threshold" placeholder="Min Value (€)" class="border rounded px-3 py-2" required>
            <input type="number" min="0" step="1" name="max_value_threshold" placeholder="Max Value (€)" class="border rounded px-3 py-2" required>
            <input type="number" min="0" step="1" name="shipping_cost" placeholder="Shipping Cost (€)" class="border rounded px-3 py-2" required>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded shadow font-semibold cursor-pointer">
                Add
            </button>
        </form>
        <table class="min-w-full text-sm text-center bg-white border border-gray-200 rounded mb-6">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">Min Value (€)</th>
                    <th class="px-4 py-2">Max Value (€)</th>
                    <th class="px-4 py-2">Shipping Cost (€)</th>
                    <th class="px-4 py-2">Last Updated</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shippingCosts as $cost)
                <tr>
                    <td class="px-4 py-2">{{ number_format($cost->min_value_threshold, 2, ',', '') }}</td>
                    <td class="px-4 py-2">{{ number_format($cost->max_value_threshold, 2, ',', '') }}</td>
                    <td class="px-4 py-2">{{ number_format($cost->shipping_cost, 2, ',', '') }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($cost->updated_at)->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2 flex gap-2 justify-center">
                        <button type="button"
                            onclick="openEditShippingCost({{ $cost->id }}, '{{ $cost->min_value_threshold }}', '{{ $cost->max_value_threshold }}', '{{ $cost->shipping_cost }}')"
                            class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-semibold cursor-pointer">
                            Edit
                        </button>
                        <form action="{{ route('dashboard.settings.shipping-cost.delete', $cost->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this shipping cost?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-semibold cursor-pointer">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div id="editShippingCostModal" class="fixed inset-0 bg-opacity-20 backdrop-blur-sm flex items-center justify-center z-50 hidden">
            <div class="bg-gray-800 rounded-lg shadow-lg p-8 w-full max-w-md relative">
                <button type="button" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl" onclick="closeEditShippingCost()">&times;</button>
                <h3 class="text-lg font-bold mb-4 text-gray-300">Edit Shipping Cost</h3>
                <form id="editShippingCostForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="modal_shipping_id">
                    <div class="mb-4">
                        <label class="block font-semibold mb-2 text-gray-300">Min Value (€)</label>
                        <input type="number" min="0" step="1" name="min_value_threshold" id="modal_min_value" class="border-2 border-gray-300 text-gray-300 rounded-lg px-3 py-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold mb-2 text-gray-300">Max Value (€)</label>
                        <input type="number" min="0" step="1" name="max_value_threshold" id="modal_max_value" class="border-2 border-gray-300 text-gray-300 rounded-lg px-3 py-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-semibold mb-2 text-gray-300">Shipping Cost (€)</label>
                        <input type="number" min="0" step="1" name="shipping_cost" id="modal_shipping_cost" class="border-2 border-gray-300 text-gray-300 rounded-lg px-3 py-2 w-full" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeEditShippingCost()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded mr-2 cursor-pointer">Cancel</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow font-semibold cursor-pointer">Save</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            function openEditShippingCost(id, min, max, cost) {
                document.getElementById('editShippingCostModal').classList.remove('hidden');
                document.getElementById('modal_shipping_id').value = id;
                document.getElementById('modal_min_value').value = min;
                document.getElementById('modal_max_value').value = max;
                document.getElementById('modal_shipping_cost').value = cost;
                document.getElementById('editShippingCostForm').action = "{{ route('dashboard.settings.update') }}/shipping-cost/" + id;
            }
            function closeEditShippingCost() {
                document.getElementById('editShippingCostModal').classList.add('hidden');
            }
        </script>
    </div>
</div>
@endsection
