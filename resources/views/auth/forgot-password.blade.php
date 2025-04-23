@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <form method="POST" action="{{ route('forgot-password.send-email') }}"
        class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md space-y-6">
        @csrf

        <h2 class="text-2xl font-semibold text-gray-700">Reset Password</h2>
        @if (session('status'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        <div class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-800">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email', $email ?? '') }}" required autofocus
                    class="mt-1 block w-full rounded-md border-2 @error('email') border-red-500 @else border-gray-300 @enderror bg-white focus:ring-indigo-500 focus:border-indigo-500 text-gray-800 py-2 px-4" />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mt-5">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                    Back to login
                </a>
            </div>
        </div>
        <button type="submit" class="w-full rounded btn btn-primary">Send verification email</button>
    </form>
@endsection
