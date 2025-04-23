<!DOCTYPE html>
<html lang="pt">
    <head>
        @include('layouts.partials.head')
    </head>
    <body>
        <div class="main-wrapper bg-bs-body">
            @if (!isset($exception) || $exception->getStatusCode() != 503)
                @include('layouts.partials.header')
            @endif

            <main class="container mx-auto mt-6">
                @yield('content')
            </main>

            <footer class="text-center p-4 text-sm text-red-200 mt-10 bg-red-800">
                &copy; {{ date('Y') }} Grocery Club. All rights reserved.
            </footer>
        </div>
    </body>
</html>
