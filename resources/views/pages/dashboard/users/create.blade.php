@extends('layouts.dashboard_app')

@section('title', 'Create User')

@section('content')
<div class="w-full px-0">
    <div class="flex items-center mb-8">
        <a href="{{ url()->previous() }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded shadow text-sm flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <h1 class="text-2xl font-bold ml-4">Create User</h1>
    </div>
    <form action="{{ route('dashboard.users.store') }}" method="POST" class="bg-white rounded-xl shadow-lg p-8 w-full" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <div class="mb-6">
                    <label for="name" class="block font-semibold mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="border rounded px-3 py-2 w-full" required>
                </div>
                <div class="mb-6">
                    <label for="email" class="block font-semibold mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="border rounded px-3 py-2 w-full" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block font-semibold mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" value="{{ old('password') }}"
                            class="border rounded px-3 py-2 w-full pr-10" required>
                        <button type="button" tabindex="-1" onclick="togglePassword()" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 focus:outline-none">
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-6">
                    <label for="type" class="block font-semibold mb-2">Role</label>
                    <select name="type" id="type" class="border rounded px-3 py-2 w-full" required onchange="toggleEmployeeFields()">
                        <option value="pending_member" {{ old('type') == 'pending_member' ? 'selected' : '' }}>Pending Member</option>
                        <option value="member" {{ old('type') == 'member' ? 'selected' : '' }}>Member</option>
                        <option value="employee" {{ old('type') == 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="board" {{ old('type') == 'board' ? 'selected' : '' }}>Board</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label for="blocked" class="block font-semibold mb-2">Blocked</label>
                    <select name="blocked" id="blocked" class="border rounded px-3 py-2 w-full">
                        <option value="0" {{ old('blocked') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('blocked') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label for="gender" class="block font-semibold mb-2">Gender</label>
                    <select name="gender" id="gender" class="border rounded px-3 py-2 w-full" required>
                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div id="nonEmployeeFields">
                    <div class="mb-6">
                        <label for="nif" class="block font-semibold mb-2">NIF</label>
                        <input type="text" name="nif" id="nif" value="{{ old('nif') }}"
                            class="border rounded px-3 py-2 w-full">
                    </div>
                    <div class="mb-6">
                        <label for="default_delivery_address" class="block font-semibold mb-2">Default Delivery Address</label>
                        <input type="text" name="default_delivery_address" id="default_delivery_address" value="{{ old('default_delivery_address') }}"
                            class="border rounded px-3 py-2 w-full">
                    </div>
                    <div class="mb-6">
                        <label for="default_payment_type" class="block font-semibold mb-2">Default Payment Type</label>
                        <select name="default_payment_type" id="default_payment_type" class="border rounded px-3 py-2 w-full">
                            <option value="">-- Select --</option>
                            @foreach(\App\Enums\PaymentMethod::cases() as $method)
                                <option value="{{ $method->value }}" {{ old('default_payment_type') == $method->value ? 'selected' : '' }}>
                                    {{ $method->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="default_payment_reference" class="block font-semibold mb-2">Default Payment Reference</label>
                        <input type="text" name="default_payment_reference" id="default_payment_reference" value="{{ old('default_payment_reference') }}"
                            class="border rounded px-3 py-2 w-full">
                    </div>
                </div>
            </div>
            <div>
                <div class="mb-6">
                    <label for="photo" class="block font-semibold mb-2">Photo</label>
                    <div class="mb-2 flex items-center gap-5">
                        <span id="photoPreviewWrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A8.968 8.968 0 0112 15c2.221 0 4.243.815 5.879 2.153M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </span>
                        <label class="block">
                        <input type="file" name="photo" id="photo" accept="image/*"
                            class="block w-full text-sm text-gray-700
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                                px-3 py-2
                                cursor-pointer
                                hidden
                            "
                            onchange="previewPhoto(event)">
                        <label for="photo" class="bg-orange-500 p-2 rounded-lg text-white hover:bg-orange-600 cursor-pointer">Select file</label>
                    </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-2 mt-8">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg shadow transition duration-200 flex items-center gap-2 text-base cursor-pointer">
                <i class="fas fa-save"></i>
                Create
            </button>
            <a href="{{ route('dashboard.users.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg shadow transition duration-200 flex items-center gap-2 text-base">
                <i class="fas fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>
<script>
    function previewPhoto(event) {
        const file = event.target.files[0];
        const wrapper = document.getElementById('photoPreviewWrapper');
        wrapper.innerHTML = ''; // Clear existing content

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview';
                img.className = 'w-12 h-12 rounded-full object-cover';
                wrapper.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            // Restore SVG if no file selected
            wrapper.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A8.968 8.968 0 0112 15c2.221 0 4.243.815 5.879 2.153M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2a10 10 0 100 20 10 10 0 000-20z" />
                </svg>`;
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                this.blur();
            });
        });
        toggleEmployeeFields();
    });

    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function toggleEmployeeFields() {
        const type = document.getElementById('type').value;
        const nonEmployeeFields = document.getElementById('nonEmployeeFields');
        if (type === 'employee') {
            nonEmployeeFields.style.display = 'none';
        } else {
            nonEmployeeFields.style.display = '';
        }
    }
</script>
@endsection
