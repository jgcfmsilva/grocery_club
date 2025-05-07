<div id="deleteModal" class="fixed inset-0 bg-opacity-20 backdrop-blur-sm flex items-center justify-center hidden">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 w-96">
        <h2 class="text-lg font-medium mb-4 text-white">{{ $title ?? 'Confirm Deletion' }}</h2>
        <p class="text-sm text-gray-300 mb-6">{{ $subtitle ?? 'Are you sure you want to delete? This action cannot be undone.' }}</p>
        <div class="flex justify-end space-x-4">
            <button id="cancelDelete" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded cursor-pointer">Cancel</button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded cursor-pointer">
                    {{ $confirmButtonText ?? 'Delete' }}
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/components/modal.js')
@endpush
