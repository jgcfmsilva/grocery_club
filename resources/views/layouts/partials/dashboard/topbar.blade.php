<header class="bg-gray-800 shadow-lg p-2 flex items-center justify-between space-x-6 border-b border-gray-200 m-5 rounded-2xl">
    <!-- Left Side: Dashboard Title -->
    <div class="text-xl font-semibold text-white px-5">Dashboard</div>

    <!-- Right Side: User Avatar and Dropdown -->
    <div class="relative flex items-center space-x-4">
        <!-- User Avatar and Dropdown Button -->
        <div class="relative">
            <button id="user-avatar-btn" class="p-1 rounded-full hover:bg-gray-300 transition duration-200 ease-in-out focus:outline-none cursor-pointer">
                <!-- User Avatar -->
                <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="User Image" class="w-11 h-11 rounded-full ring-2 ring-blue-500">
            </button>

            <!-- Dropdown Menu -->
            <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 scale-95 transition-all duration-300 ease-in-out hidden z-50">
                <ul class="pt-2">
                    <li><a href="{{ route('home') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-300 transition duration-200">Mudar para a loja</a></li>
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-gray-800 hover:bg-gray-300 transition duration-200">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>