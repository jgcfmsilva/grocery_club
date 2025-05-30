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

    let productIndex = 1;
    const productsList = document.getElementById('manual-products-list');
    const addBtn = document.getElementById('add-product-row');

    function getSelectedProductIds() {
        return Array.from(productsList.querySelectorAll('.product-select'))
            .map(sel => sel.value)
            .filter(val => val);
    }

    function updateSelectOptions() {
        const selectedIds = getSelectedProductIds();
        productsList.querySelectorAll('.manual-product-row').forEach(function(row) {
            const select = row.querySelector('.product-select');
            const currentValue = select.value;
            Array.from(select.options).forEach(function(opt) {
                if (opt.value === "" || opt.value === currentValue) {
                    opt.disabled = false;
                } else {
                    opt.disabled = selectedIds.includes(opt.value);
                }
            });
        });
    }

    function updateBadges() {
        document.querySelectorAll('.manual-product-row').forEach(function(row) {
            const select = row.querySelector('.product-select');
            const badgeSpans = row.querySelectorAll('.product-badge');
            badgeSpans.forEach(function(span) {
                span.classList.add('hidden');
            });
            if (select.value) {
                const badge = row.querySelector('.product-badge[data-product="' + select.value + '"]');
                if (badge) {
                    badge.classList.remove('hidden');
                }
            }
        });
    }

    addBtn.addEventListener('click', function () {
        const firstRow = productsList.querySelector('.manual-product-row');
        const row = firstRow.cloneNode(true);

        // Update names and clear values
        row.querySelectorAll('select, input').forEach(function (el) {
            if (el.name && el.name.includes('[0]')) {
                el.name = el.name.replace('[0]', '[' + productIndex + ']');
            }
            if (el.classList.contains('product-select')) {
                el.selectedIndex = 0;
            }
            if (el.type === 'number') {
                el.value = '';
            }
        });

        row.querySelectorAll('.product-badge').forEach(function(span) {
            span.classList.add('hidden');
        });

        row.querySelector('.remove-product-row').classList.remove('hidden');

        productsList.appendChild(row);
        productIndex++;
        updateSelectOptions();
        updateBadges();
    });

    productsList.addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-product-row');
        if (btn) {
            const rows = productsList.querySelectorAll('.manual-product-row');
            if (rows.length > 1) {
                btn.closest('.manual-product-row').remove();
                updateSelectOptions();
                updateBadges();
            }
        }
    });

    productsList.addEventListener('change', function (e) {
        if (e.target.classList.contains('product-select')) {
            updateSelectOptions();
            updateBadges();
        }
    });

    updateSelectOptions();
    updateBadges();
});
