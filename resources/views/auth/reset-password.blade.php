@extends('layouts.app')

@section('title', 'Redefinir Senha')

@section('content')


    <form method="POST" action="{{ route('password.update') }}"
        class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md space-y-6">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="gap-6">
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-800">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $email) }}" required
                    class="mt-1 block w-full rounded-md border-2 @error('email') border-red-500 @else border-gray-300 @enderror bg-white focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 py-2 px-4" />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mt-3">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-gray-800">Password</label>
                </div>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full rounded-md border-2 @error('password') border-red-500 @else border-gray-300 @enderror bg-white focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 py-2 px-4" />
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>

            <!-- Confirm Password -->
            <div class="mt-3">
                <div class="flex items-center justify-between">
                    <label for="password-confirm" class="block text-sm font-medium text-gray-700">Confirm password</label>

                    <div class="cursor-pointer">
                        <svg id="togglePassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>

                <input id="password-confirm" type="password" name="password_confirmation" required
                class="mt-1 block w-full rounded-md border-2 @error('password') border-red-500 @else border-gray-300 @enderror bg-white focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 py-2 px-4" />
            </div>

            <!-- submit -->
            <div class="mt-3">
                <button type="submit" class="w-full rounded btn btn-primary">Set new password</button>
            </div>

        </div>
    </form>
@endsection

@push('scripts')
    @vite('resources/js/pages/auth/reset-password.js')
@endpush
