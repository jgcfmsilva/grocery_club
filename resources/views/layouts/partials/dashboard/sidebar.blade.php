<header class="lg:hidden flex items-center justify-between bg-gray-800 p-4 mb-8">
  <!-- Hamburger -->
  <button id="sidebar-toggle-btn" class="text-white focus:outline-none" aria-label="Open sidebar">
      <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
  </button>
  <!-- Título -->
  <span class="text-white font-bold text-lg">Dashboard</span>
  <!-- Avatar do usuário -->
  <div class="relative">
  <button id="user-avatar-btn-mobile" class="p-1 rounded-full focus:outline-none cursor-pointer">
    <img src="{{ asset('storage/users/' . auth()->user()->photo) }}" alt="User Image" class="w-10 h-10 rounded-full hover:ring-2 hover:ring-white">
  </button>

  <div id="user-dropdown-mobile" class="absolute right-0 mt-5 w-56 bg-gray-800 rounded-lg shadow-lg opacity-0 scale-95 transition-all duration-300 ease-in-out z-50 pointer-events-none">
      <ul class="py-2 px-4 text-white">
          <li>
              <a href="{{ route('home') }}" class="block px-4 py-2 hover:bg-gray-700 rounded-md">
                  <i class="fa-solid fa-shop me-2"></i> Mudar para a loja
              </a>
          </li>
          <li>
              <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" class="block px-4 py-2 hover:bg-gray-700 rounded-md">
                  <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
              </a>
              <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                  @csrf
              </form>
          </li>
      </ul>
  </div>
</div>
</header>

<div class="lg:flex">
    <!-- Sidebar -->
    <div id="dashboard-sidebar"
         class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-800 text-white transform -translate-x-full transition-transform duration-200 ease-in-out lg:relative lg:translate-x-0 lg:w-64 lg:block"
         style="will-change: transform;">
        <div class="p-4 mb-5">
            <a href="{{ route('dashboard.index') }}" class="logo">
                <img src="{{ asset('assets/img/logo-dark.png') }}" alt="Logo Escuro"
                    class="img-fluid logo-dark d-none">
            </a>
        </div>
        <ul id="sidebar-item" class="space-y-4 px-4">
            <li>
                <a href="{{ route('dashboard.orders.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3">
                    <i class="fas fa-box me-2"></i> Orders
                </a>
            </li>
            <li>
                <a href="{{ route('dashboard.inventory.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3">
                    <i class="fa-solid fa-warehouse me-2"></i> Inventory
                </a>
            </li>
            @if(isBoard())
                <li>
                    <a href="{{ route('dashboard.memberships.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3">
                        <i class="fas fa-users me-2"></i> Memberships
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.users.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3">
                        <i class="fas fa-user me-2"></i> Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.virtual-cards.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3">
                        <i class="fas fa-credit-card me-2"></i> Virtual Cards
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.products.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3">
                        <i class="fas fa-cube me-2"></i> Products
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.categories.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3">
                        <i class="fas fa-tags me-2"></i> Categories
                    </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.settings.index') }}" class="flex items-center space-x-2 py-2 hover:bg-gray-700 rounded-md px-3">
                        <i class="fas fa-cogs me-2"></i> Settings
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <!-- Overlay for mobile -->
    <div id="sidebar-overlay"
        class="fixed inset-0 z-30 hidden lg:hidden"
        style="background-color: rgba(255,255,255,0.1); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('dashboard-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebar-toggle-btn');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', openSidebar);
        }
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        // Close sidebar on resize to desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
    });
</script>
<style>
    @media (min-width: 1024px) {
        #dashboard-sidebar {
            position: relative !important;
            transform: none !important;
            left: 0 !important;
            top: 0 !important;
            width: 16rem !important;
            z-index: 10 !important;
        }
        #sidebar-overlay {
            display: none !important;
        }
    }
</style>