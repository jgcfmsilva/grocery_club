@extends('layouts.app')

@section('title', 'Login')

@section('content')

    <form method="POST" action="{{ route('login.submit') }}" enctype="multipart/form-data"
        class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md space-y-6">
        @csrf

        <h2 class="text-2xl font-semibold text-gray-700">Log in</h2>

        <div class="gap-6">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-800">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="mt-1 block w-full rounded-md border-2 @error('email') border-red-500 @else border-gray-300 @enderror bg-white focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 py-2 px-4" />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mt-3">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-gray-800">Password</label>
                    <div class="cursor-pointer">
                        <svg  id="togglePassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full rounded-md border-2 @error('password') border-red-500 @else border-gray-300 @enderror bg-white focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 py-2 px-4" />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Forgot Password Link -->
                <div class="text-right mt-2">
                    <a id="forgotPasswordLink" class="text-sm text-indigo-600 hover:text-indigo-500 cursor-pointer">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div class="form-check mb-3 mt-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
        </div>
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <button type="submit" class="w-full rounded btn btn-primary">Log in</button>
    </form>

    <!-- forgot password form -->
    <form id="forgotPassword-form" action="{{ route('forgot-password.post') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="email" id="hiddenEmailField">
    </form>
@endsection

@push('scripts')
    @vite('resources/js/pages/auth/login.js')
@endpush
