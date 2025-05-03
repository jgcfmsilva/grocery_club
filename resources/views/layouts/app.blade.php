<!DOCTYPE html>
<html lang="pt">
    <head>
        @include('layouts.partials.head.head')
        @livewireStyles
    </head>
    <body class="flex flex-col min-h-screen">
        <div class="bg-bs-body flex flex-col flex-1">
            @if (!isset($exception) || $exception->getStatusCode() != 503)
                @include('layouts.partials.header.header')
            @endif

            <main class="container mx-auto mt-6 flex-1 z-1">
                @yield('content')
            </main>

            <footer class="text-center p-4 text-sm text-white mt-10 bg-gray-800">
                &copy; {{ date('Y') }} Grocery Club. All rights reserved.
            </footer>
        </div>
        @livewireScripts
    </body>
</html>
