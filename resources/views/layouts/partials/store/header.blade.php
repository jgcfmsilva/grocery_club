<header class="relative z-20">
    <!-- Desktop Header (hidden on mobile) -->
    <div class="hidden lg:block pt-5 pb-13 bg-primary">
        <div class="custom-container">
            <div class="flex flex-wrap items-center">
                <div class="w-full xl:w-3/12 xxl:w-1/4">
                    <div class="topbar-info d-none d-xl-block">
                        <p class="text-white fs-sm fw-medium mb-0">{{ config('vars.welcome_message') }}</p>
                    </div>

                </div>
                <div class="w-full xl:w-9/12 xxl:w-3/4">
                    <ul class="flex items-baseline justify-center xl:justify-end topbar-info-right">
                        <li>
                            <a href="tel:{{ config('vars.contact_number') }}" class="text-white flex items-center">
                                <span class="me-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="-5 3 27 14" fill="none">
                                        <path fill="white"
                                            d="M21 15.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.11 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 15.92z">
                                        </path>
                                    </svg>
                                </span>
                                {{ config('vars.contact_number') }}
                            </a>
                        </li>

                        <li>
                            <a href="mailto:{{ config('vars.email') }}" class="text-white flex items-center">
                                <span class="me-2">
                                    <svg width="16" height="14" viewBox="0 0 20 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18.2422 0H1.75781C0.790547 0 0 0.783572 0 1.75V12.25C0 13.2168 0.791055 14 1.75781 14H18.2422C19.2095 14 20 13.2164 20 12.25V1.75C20 0.783339 19.2091 0 18.2422 0ZM17.9723 1.16667C17.4039 1.73433 10.7283 8.40194 10.4541 8.67588C10.225 8.90462 9.77512 8.90478 9.54594 8.67588L2.02773 1.16667H17.9723ZM1.17188 12.0355V1.96447L6.21348 7L1.17188 12.0355ZM2.02773 12.8333L7.04078 7.82631L8.71598 9.49951C9.40246 10.1852 10.5978 10.1849 11.2841 9.49951L12.9593 7.82635L17.9723 12.8333H2.02773ZM18.8281 12.0355L13.7865 7L18.8281 1.96447V12.0355Z"
                                            fill="white" />
                                    </svg>
                                </span>
                                {{ config('vars.email') }}
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"
                                class="inline-flex items-center text-white font-normal p-0 hover:underline tt-theme-toggle">
                                <div class="tt-theme-light" data-bs-toggle="tooltip" data-bs-placement="left"
                                    data-bs-title="Dark">Dark <i class="fas fa-moon fs-lg ms-1"></i>
                                </div>
                                <div class="tt-theme-dark" data-bs-toggle="tooltip" data-bs-placement="left"
                                    data-bs-title="Light">Light <i class="fas fa-sun fs-lg ms-1"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="main-menu-header hidden lg:block" id="theme-container">
        <div class="custom-container" id="theme-container">
            <div class="mt-0 lg:-mt-8 bg-white rounded-full relative lg:ps-5 lg:pe-10">
                <div class="flex flex-wrap items-center">
                    <div class="col-xxl-2 col-xl-3 col-md-3 col-5">
                        <a href="{{ route('home') }}" class="logo">
                            <img src="{{ asset('assets/img/logo-light.png') }}" alt="Logo Claro"
                                class="img-fluid logo-light">
                            <img src="{{ asset('assets/img/logo-dark.png') }}" alt="Logo Escuro"
                                class="img-fluid logo-dark d-none">
                        </a>
                    </div>
                    <div class="col-xxl-10 col-xl-9 col-md-9 col-7">
                        <div class="flex items-center justify-between relative w-full">
                            <div class="hidden md:flex items-center mx-auto">
                                <div class="category-dropdown position-relative d-none d-md-inline-block">
                                    <a href="javascript:void(0)"
                                        class="category-dropdown-btn fw-bold d-none d-sm-inline-block">
                                        Categories <span class="ms-1"><i class="fa-solid fa-angle-down"></i></span>
                                    </a>
                                    <div class="category-dropdown-box scrollbar">
                                        <ul class="category-dropdown-menu">
                                            @php
                                                $categories = [];
                                                $categories = \App\Models\Category::allCategories();
                                            @endphp
                                            @foreach ($categories as $category)
                                                <li>
                                                    <a href="{{ route('products.category', $category->id) }}"
                                                        class="d-flex align-items-center">
                                                        <div class="me-2 avatar-icon">
                                                            <img src="{{ $category->image
                                                                ? asset('storage/categories/' . $category->image)
                                                                : asset('storage/categories/category_no_image.png') }}"
                                                                alt="" class="rounded-circle h-100 w-100">
                                                        </div>
                                                        <span>{{ $category->name }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <nav class="gshop-navmenu ml-3 hidden xl:block">
                                    <ul class="flex items-center justify-center t">
                                        <li><a href="{{ route('products.index') }}">Products</a></li>
                                    </ul>
                                </nav>

                            </div>
                            <div class="gshop-header-icons hidden md:inline-flex items-center justify-end ms-3">
                                <div class="gshop-header-search relative">
                                    <!-- Botão de ativação do dropdown -->
                                    <button type="button" class="header-icon theme-icon text-gray-400 hover:text-white" data-bs-toggle="dropdown">
                                        <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M10 2a8 8 0 015.29 13.71l4 4a1 1 0 01-1.42 1.42l-4-4A8 8 0 1110 2zm0 2a6 6 0 100 12A6 6 0 0010 4z"/>
                                        </svg>
                                    </button>

                                    <!-- Dropdown com formulário de busca -->
                                    <div class="dropdown-menu dropdown-menu-end mt-2 p-3 rounded-xl shadow-lg bg-gray-800 border-0 w-72">
                                        <form action="{{ route('products.index') }}" class="flex items-center bg-gray-200 border-2 border-dark rounded-lg overflow-hidden">
                                            <input 
                                                type="text" 
                                                name="search"
                                                placeholder="Search products..." 
                                                class="flex-1 bg-transparent text-gray-600 placeholder-gray-500 px-4 py-2 focus:outline-none"
                                                @isset($searchKey) value="{{ $searchKey }}" @endisset
                                            >
                                            <button type="submit" class="px-4 py-2 text-gray-800 hover:text-secondary">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if (Auth::check() && !isEmployee())
                                    <livewire:header-wishlist />
                                @endif

                                @if (!isEmployee())
                                    <livewire:header-cart-menu />
                                @endif

                                <livewire:header-user-menu />

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Header (visible only on mobile) -->
    <div class="block lg:hidden bg-white shadow-md py-2 px-4">
        <div class="flex items-center justify-between">
            <!-- Logo left -->
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('assets/img/logo-light.png') }}" alt="Logo Claro"
                    class="img-fluid logo-light">
                <img src="{{ asset('assets/img/logo-dark.png') }}" alt="Logo Escuro"
                    class="img-fluid logo-dark d-none">
            </a>
            <!-- Wishlist and Cart left of Hamburger -->
            <div class="flex items-center gap-5">
                @if (Auth::check() && !isEmployee())
                    <livewire:header-wishlist />
                @endif
                @if (!isEmployee())
                    <livewire:header-cart-menu />
                @endif
                <!-- Hamburger menu right -->
                <button id="mobile-hamburger-btn" class="focus:outline-none" aria-label="Open menu">
                    <i class="fas fa-bars text-dark text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Hamburger Menu Drawer -->
    <div id="mobile-hamburger-menu" class="fixed inset-0 z-50 bg-opacity-20 backdrop-blur-sm hidden">
        <div class="fixed top-0 right-0 w-72 max-w-full h-full bg-white shadow-lg flex flex-col">
            <div class="flex items-center justify-between px-4 py-3 border-b">
                <span class="font-bold text-lg text-dark">Menu</span>
                <button id="mobile-hamburger-close" class="text-gray-700 text-2xl focus:outline-none" aria-label="Close menu">
                    <i class="text-dark fas fa-times"></i>
                </button>
            </div>
            <nav class="flex-1 flex flex-col overflow-hidden">
                <!-- Search (top) -->
                <div class="p-4 border-b">
                    <form action="{{ route('products.index') }}" class="flex items-center bg-gray-200 border-2 border-dark rounded-lg overflow-hidden">
                        <input 
                            type="text" 
                            name="search"
                            placeholder="Search products..." 
                            class="flex-1 bg-transparent text-gray-600 placeholder-gray-500 px-4 py-2 focus:outline-none"
                            @isset($searchKey) value="{{ $searchKey }}" @endisset
                        >
                        <button type="submit" class="px-4 py-2 text-gray-800 hover:text-secondary">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>
                <!-- Contact/Theme (new section) -->
                <div class="px-4 py-3 border-b">
                    <ul class="space-y-2">
                        <li>
                            <a href="tel:{{ config('vars.contact_number') }}" class="flex items-center text-gray-800">
                                <span class="me-2 text-dark">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="-5 3 27 14" fill="none">
                                        <path fill="currentColor"
                                            d="M21 15.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.11 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 15.92z">
                                        </path>
                                    </svg>
                                </span>
                                <span class="text-dark font-medium">
                                    {{ config('vars.contact_number') }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="mailto:{{ config('vars.email') }}" class="flex items-center">
                                <span class="me-2 text-dark">
                                    <svg width="16" height="14" viewBox="0 0 20 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18.2422 0H1.75781C0.790547 0 0 0.783572 0 1.75V12.25C0 13.2168 0.791055 14 1.75781 14H18.2422C19.2095 14 20 13.2164 20 12.25V1.75C20 0.783339 19.2091 0 18.2422 0ZM17.9723 1.16667C17.4039 1.73433 10.7283 8.40194 10.4541 8.67588C10.225 8.90462 9.77512 8.90478 9.54594 8.67588L2.02773 1.16667H17.9723ZM1.17188 12.0355V1.96447L6.21348 7L1.17188 12.0355ZM2.02773 12.8333L7.04078 7.82631L8.71598 9.49951C9.40246 10.1852 10.5978 10.1849 11.2841 9.49951L12.9593 7.82635L17.9723 12.8333H2.02773ZM18.8281 12.0355L13.7865 7L18.8281 1.96447V12.0355Z"
                                            fill="currentColor" />
                                    </svg>
                                </span>
                                <span class="text-dark font-medium">
                                    {{ config('vars.email') }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <button type="button" id="mobile-theme-toggle" class="inline-flex items-center text-dark font-normal p-0 hover:underline focus:outline-none w-full tt-theme-toggle">
                                <span id="mobile-theme-light" class="flex items-center gap-2">
                                    <i class="fas fa-moon fs-lg ms-1"></i> Dark
                                </span>
                                <span id="mobile-theme-dark" class="flex items-center gap-2 hidden">
                                    <i class="fas fa-sun fs-lg ms-1"></i> Light
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>
                <!-- Categories (scrollable) -->
                <div class="flex-1 overflow-y-auto px-4 py-4">
                    <div class="font-semibold mb-2 text-dark">Categories</div>
                    <ul class="space-y-2">
                        @php
                            $categories = \App\Models\Category::allCategories();
                        @endphp
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('products.category', $category->id) }}" class="flex items-center gap-2">
                                    <img src="{{ $category->image
                                        ? asset('storage/categories/' . $category->image)
                                        : asset('storage/categories/category_no_image.png') }}"
                                        alt="" class="w-6 h-6 rounded-full object-cover">
                                    <span class="text-dark font-medium">{{ $category->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- User Menu (bottom, always visible as options) -->
                <div class="border-t px-4 py-4">
                    <ul class="space-y-2">
                        @auth
                            @if (auth()->user()->type === \App\Enums\UserType::PendingMember || auth()->user()->type === \App\Enums\UserType::Member || auth()->user()->type === \App\Enums\UserType::Employee || auth()->user()->type === \App\Enums\UserType::Board)
                                <li>
                                    <a href="{{ route('my-account.index') }}" class="text-dark block px-3 py-2 rounded hover:bg-gray-100">
                                        <span class="me-2"><i class="fa-solid fa-user"></i></span>My Account
                                    </a>
                                </li>
                            @endif
                            @if(auth()->user()->type === \App\Enums\UserType::Board || auth()->user()->type === \App\Enums\UserType::Employee)
                                <li>
                                    <a href="{{ route('dashboard.index') }}" class="text-dark block px-3 py-2 rounded hover:bg-gray-100">
                                        <span class="me-2"><i class="fa-solid fa-bars"></i></span>Dashboard
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-dark block px-3 py-2 rounded hover:bg-gray-100">
                                    <span class="me-2"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </li>
                        @endauth
                        @guest
                            <li>
                                <a href="{{ route('login') }}" class="text-dark block px-3 py-2 rounded hover:bg-gray-100">
                                    <span class="me-2"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>Log In
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}" class="text-dark block px-3 py-2 rounded hover:bg-gray-100">
                                    <span class="me-2"><i class="fa-solid fa-user-plus"></i></span>Registration
                                </a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</header>
<style>
    @media (max-width: 1023.98px) {
        .lg\:block { display: none !important; }
        .lg\:hidden { display: block !important; }
    }
    @media (min-width: 1024px) {
        .lg\:block { display: block !important; }
        .lg\:hidden { display: none !important; }
    }
</style>
<script>
</script>
