<!DOCTYPE html>
<html lang="pt">
    <head>
        @include('layouts.partials.store.head')
        @livewireStyles
    </head>
    <body class="flex flex-col min-h-screen">
        <div class="bg-bs-body flex flex-col flex-1">
            @if (!isset($exception) || $exception->getStatusCode() != 503)
                @include('layouts.partials.store.header')
            @endif

            @auth
                @if (!auth()->user()->hasVerifiedEmail())
                    <div class="mt-3 mx-[15%]">
                        <div class="bg-yellow-100 py-3 rounded-lg border-2 border-yellow-200 text-center mb-0" role="alert">
                            Your email has not been confirmed yet.
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="text-dark font-bold underline p-0 align-baseline">Resend confirmation email</button>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth

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
