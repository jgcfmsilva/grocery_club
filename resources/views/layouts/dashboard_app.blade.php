<!DOCTYPE html>
<html lang="pt" class="h-full">
    <head>
        @include('layouts.partials.dashboard.head')
        @livewireStyles
    </head>
    <body class="bg-gray-100 text-gray-900 h-full overflow-hidden">
        <div class="flex h-full">
            <!-- Sidebar -->
            @include('layouts.partials.dashboard.sidebar')

            <!-- Main -->
            <div class="flex-1 flex flex-col overflow-auto">
                <!-- Top Bar -->
                @include('layouts.partials.dashboard.topbar')

                <!-- Content -->
                <main class="flex-1 px-6 pb-8">
                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="bg-gray-800 text-white text-center p-4">
                    &copy; {{ date('Y') }} Grocery Club. All rights reserved.
                </footer>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
