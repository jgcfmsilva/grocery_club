@extends('layouts.app')

@section('title', 'Registration')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="register-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
        class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md space-y-6">
        @csrf

        <h2 class="text-2xl font-semibold text-gray-700">Registration</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nome -->
            <div>
                <label for="name" class="block text-sm font-medium form-input-label">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium form-input-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium form-input-label">Password</label>
                <input type="password" name="password" id="password" value="{{ old('password') }}" required
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
            </div>

            <!-- Confirmar Password -->
            <div>
                <div class="flex items-center justify-between">
                    <label for="password_confirmation" class="block text-sm font-medium form-input-label">Confirm
                        Password</label>
                    <div>
                        <svg id="togglePassword" class="h-5 w-5 text-gray-400 cursor-pointer" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    value="{{ old('password_confirmation') }}" required
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
            </div>

            <!-- Género -->
            <div>
                <label for="gender" class="block text-sm font-medium form-input-label">Gender</label>
                <select name="gender" id="gender" required
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4">
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </select>
            </div>

            <!-- NIF -->
            <div>
                <label for="nif" class="block text-sm font-medium form-input-label">NIF Number</label>
                <input type="text" name="nif" id="nif" value="{{ old('nif') }}" maxlength="9"
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
            </div>

            <!-- Endereço -->
            <div class="md:col-span-2">
                <label for="default_delivery_address" class="block text-sm font-medium form-input-label">Delivery
                    Address</label>
                <input type="text" name="default_delivery_address" id="default_delivery_address"
                    value="{{ old('default_delivery_address') }}"
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
            </div>

            <!-- Tipo de pagamento -->
            <div>
                <label for="default_payment_type" class="block text-sm font-medium form-input-label">Payment Method</label>
                <select name="default_payment_type" id="default_payment_type"
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4">
                    <option value="">-- Select --</option>
                    <option value="Visa" {{ old('default_payment_type') == 'Visa' ? 'selected' : '' }}>Visa</option>
                    <option value="PayPal" {{ old('default_payment_type') == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                    <option value="MB WAY" {{ old('default_payment_type') == 'MB WAY' ? 'selected' : '' }}>MB WAY</option>
                </select>
            </div>

            <!-- Referência de pagamento -->
            <div id="paymentReferenceWrapper" class="hidden">
                <label for="default_payment_reference" id="paymentReferenceLabel"
                    class="block text-sm font-medium form-input-label">Payment Reference</label>
                <input type="text" name="default_payment_reference" id="default_payment_reference"
                    value="{{ old('default_payment_reference') }}"
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
            </div>

            <!-- Foto -->
            <div class="md:col-span-2">
                <label for="photo" class="block text-sm font-medium form-input-label mb-2">Profile Photo</label>

                <div class="flex items-center space-x-4">
                    <div
                        class="w-20 h-20 rounded-md overflow-hidden bg-gray-100 border-2 border-gray-300 flex items-center justify-center mr-4">
                        <img id="photoPreview" src="#" alt="Pré-visualização da foto"
                            class="hidden w-full h-full object-cover rounded-md" />

                        <svg id="photoPlaceholder" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M5.121 17.804A8.968 8.968 0 0112 15c2.221 0 4.243.815 5.879 2.153M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                    </div>

                    <label for="photo"
                        class="inline-flex items-center gap-2 cursor-pointer text-sm font-medium text-white bg-secondary px-4 py-2 rounded-lg shadow-sm transition">
                        <span>Select Image</span>
                        <input type="file" name="photo" id="photo" accept=".jpeg, .jpg, .png" class="hidden" />
                    </label>
                </div>

                <p class="mt-2 text-xs form-input-label">Choose an image up to 2MB.</p>
            </div>
        </div>

        <div class="pt-4">
            <button type="submit" id="submitButton" class="w-full rounded btn btn-primary">
                Create Account
            </button>
        </div>
    </form>
@endsection

@push('styles')
    @vite('resources/css/pages/auth/register.css')
@endpush

@push('scripts')
    @vite('resources/js/pages/auth/register.js')
    <script src="{{ asset('assets/js/vendors/image-preview.js') }}"></script>
@endpush
