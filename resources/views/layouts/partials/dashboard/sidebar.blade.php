<div class="w-64 bg-gray-800 text-white">
    <div class="p-4 mb-5">
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('assets/img/logo-dark.png') }}" alt="Logo Escuro"
                class="img-fluid logo-dark d-none">
        </a>
    </div>
    <ul id="sidebar-item" class="space-y-4 px-4">
        <li><a href="{{ route('dashboard.orders.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3"><i class="fas fa-box me-2"></i> Orders</a></li>
        <li><a href="{{ route('dashboard.memberships.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3"><i class="fas fa-users me-2"></i> Memberships</a></li>
        <li><a href="{{ route('dashboard.users.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3"><i class="fas fa-user me-2"></i> Users</a></li>
        <li><a href="{{ route('dashboard.virtual-cards.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3"><i class="fas fa-credit-card me-2"></i> Virtual Cards</a></li>
        <li><a href="{{ route('dashboard.inventory.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3"><i class="fa-solid fa-warehouse me-2"></i> Inventory</a></li>
        <li><a href="{{ route('dashboard.products.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3"><i class="fas fa-cube me-2"></i> Products</a></li>
        <li><a href="{{ route('dashboard.categories.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3"><i class="fas fa-tags me-2"></i> Categories</a></li>
        <li><a href="{{ route('dashboard.settings.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3"><i class="fas fa-cogs me-2"></i> Settings</a></li>
    </ul>
</div>