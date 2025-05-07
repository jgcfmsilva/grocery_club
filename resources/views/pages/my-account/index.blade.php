@extends('layouts.pages.my-account.layout')

@section('title', 'My Account - Personal Data')

@section('account-content')
    <h3 class="text-2xl font-semibold text-gray-700 mb-6">Personal Data</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="personal-data-form" action="{{ route('my-account.personal-data.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nome -->
            <div>
                <label for="name" class="block text-sm font-medium form-input-label">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium form-input-label">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Género -->
            <div>
                <label for="gender" class="block text-sm font-medium form-input-label">Gender</label>
                <select name="gender" id="gender" required
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4">
                    <option value="M" {{ auth()->user()->gender == 'M' ? 'selected' : '' }}>Male</option>
                    <option value="F" {{ auth()->user()->gender == 'F' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <!-- NIF -->
            <div>
                <label for="nif" class="block text-sm font-medium form-input-label">NIF Number</label>
                <input type="text" id="nif" name="nif" value="{{ old('nif', auth()->user()->nif) }}"
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
            </div>
        </div>

        <!-- Endereço -->
        <div>
            <label for="default_delivery_address" class="block text-sm font-medium form-input-label">Delivery Address</label>
            <input type="text" name="default_delivery_address" id="default_delivery_address" value="{{ old('default_delivery_address', auth()->user()->default_delivery_address) }}"
                class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tipo de pagamento -->
            <div>
                <label for="default_payment_type" class="block text-sm font-medium form-input-label">Payment Method</label>
                <select name="default_payment_type" id="default_payment_type"
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4">
                    <option value="">-- Select --</option>
                    <option value="Visa" {{ auth()->user()->default_payment_type == 'Visa' ? 'selected' : '' }}>Visa</option>
                    <option value="PayPal" {{ auth()->user()->default_payment_type == 'PayPal' ? 'selected' : '' }}>PayPal</option>
                    <option value="MB WAY" {{ auth()->user()->default_payment_type == 'MB WAY' ? 'selected' : '' }}>MB WAY</option>
                </select>
            </div>

             <!-- Referência de pagamento -->
            <div id="paymentReferenceWrapper" class="hidden">
                <label for="default_payment_reference" id="paymentReferenceLabel" class="block text-sm font-medium form-input-label">Payment Reference</label>
                <input type="text" name="default_payment_reference" id="default_payment_reference"
                    value="{{ old('default_payment_reference', auth()->user()->default_payment_reference) }}"
                    class="mt-1 block w-full rounded-md border-2 border-gray-300 bg-white focus:ring-indigo-500 focus:border-indigo-500 form-input-field py-2 px-4" />
            </div>
        </div>

        <!-- Foto -->
        <div>
            <label for="photo" class="block text-sm font-medium form-input-label mb-2">Profile Photo</label>

            <div class="flex items-center space-x-4">
                <div class="w-20 h-20 rounded-md overflow-hidden bg-gray-100 border-2 border-gray-300 flex items-center justify-center mr-4">
                    @if(auth()->user()->photo)
                        <img id="photoPreview" src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Current Photo" class="w-full h-full object-cover rounded-md" />
                    @else
                        <svg id="photoPlaceholder" xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A8.968 8.968 0 0112 15c2.221 0 4.243.815 5.879 2.153M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                    @endif
                </div>

                <label for="photo" class="inline-flex items-center gap-2 cursor-pointer text-sm font-medium text-white bg-secondary px-4 py-2 rounded-lg shadow-sm transition">
                    <span>Select Image</span>
                    <input type="file" name="photo" id="photo" accept="image/*" class="hidden" />
                </label>
            </div>

            <p class="mt-2 text-xs form-input-label">Choose an image up to 2MB.</p>
        </div>

        <div class="pt-4">
            <button type="submit" id="submitButton" class="w-full rounded btn btn-primary">
                Save Changes
            </button>
        </div>
    </form>
@endsection

@push('styles')
    @vite('resources/css/pages/my-account/personal-data.css')
@endpush

@push('scripts')
    @vite('resources/js/pages/my-account/personal-data.js')
    <script src="{{ asset('assets/js/vendors/image-preview.js') }}"></script>
@endpush
