document.addEventListener("DOMContentLoaded", function () {
    // Inicializa Select2
    $('.searchable-select').select2({
        placeholder: "-- Select a product --",
        allowClear: true,
        width: '100%' // garante que o Select2 use 100% da largura
    }).on('select2:open', function () {
        // Aplica classes Tailwind ao dropdown quando aberto
        $('.select2-results__option').addClass('text-sm px-2 py-1 hover:bg-gray-100');
    });

    $('.searchable-select').on('select2:open', function () {
        $('.select2-container--default .select2-selection--single')
            .addClass('border rounded px-3 py-2 w-full border-gray-800 focus:ring focus:ring-blue-200');
    });

    // Dropdown de avatar
    const avatarBtn = document.getElementById('user-avatar-btn');
    const dropdown = document.getElementById('user-dropdown');

    avatarBtn.addEventListener('mouseenter', () => {
        dropdown.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
        dropdown.classList.add('opacity-100', 'scale-100', 'pointer-events-auto');
    });

    avatarBtn.addEventListener('mouseleave', () => {
        setTimeout(() => {
            if (!dropdown.matches(':hover')) {
                dropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                dropdown.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
            }
        }, 100);
    });

    dropdown.addEventListener('mouseleave', () => {
        dropdown.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
        dropdown.classList.remove('opacity-100', 'scale-100', 'pointer-events-auto');
    });

    // Modais de cancelamento
    const cancelButtons = document.querySelectorAll('.cancel-item-btn');
    const closeModalButtons = document.querySelectorAll('.close-modal');

    cancelButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const orderId = button.getAttribute('data-order-id');
            const modal = document.getElementById('cancelModal-' + orderId);
            if (modal) {
                modal.classList.remove('hidden');
            }
        });
    });

    closeModalButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const orderId = button.getAttribute('data-order-id');
            const modal = document.getElementById('cancelModal-' + orderId);
            if (modal) {
                modal.classList.add('hidden');
            }
        });
    });
});
