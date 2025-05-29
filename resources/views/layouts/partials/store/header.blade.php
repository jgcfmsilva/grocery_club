<header class="relative z-20">
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
                                <ul class="flex items-center justify-center">
                                    <li><a href="{{ route('products.index') }}">Products</a></li>
                                </ul>
                            </nav>

                        </div>
                        <div class="gshop-header-icons hidden md:inline-flex items-center justify-end ms-3">
                            <div class="gshop-header-search dropdown">
                                <button type="button" class="header-icon theme-icon" data-bs-toggle="dropdown">
                                    <svg width="20" height="23" viewBox="0 0 22 23" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.68859 0.5C4.34645 0.5 0 4.84646 0 10.1886C0 15.5311 4.34645 19.8772 9.68859 19.8772C15.031 19.8772 19.3772 15.5311 19.3772 10.1886C19.3772 4.84646 15.031 0.5 9.68859 0.5ZM9.68859 18.0886C5.33261 18.0886 1.78866 14.5447 1.78866 10.1887C1.78866 5.83266 5.33261 2.28867 9.68859 2.28867C14.0446 2.28867 17.5885 5.83262 17.5885 10.1886C17.5885 14.5446 14.0446 18.0886 9.68859 18.0886Z"
                                            fill="#5D6374" />
                                        <path
                                            d="M21.7406 20.9824L16.6436 15.8853C16.2962 15.538 15.7338 15.538 15.3865 15.8853C15.0391 16.2323 15.0391 16.7954 15.3865 17.1424L20.4835 22.2395C20.6571 22.4131 20.8845 22.5 21.1121 22.5C21.3393 22.5 21.5669 22.4131 21.7406 22.2395C22.0879 21.8925 22.0879 21.3294 21.7406 20.9824Z"
                                            fill="#5D6374" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end border-0">
                                    <form class="search-form flex items-center" action="{{ route('products.index') }}">
                                        <input type="text" placeholder="Search products" class="w-100"
                                            name="search"
                                            @isset($searchKey) value="{{ $searchKey }}" @endisset>
                                        <button type="submit" class="submit-icon-btn-secondary"><i
                                                class="fa-solid fa-magnifying-glass"></i></button>
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
</header>
