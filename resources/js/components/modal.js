document.addEventListener('DOMContentLoaded', () => {
    const deleteButtons = document.querySelectorAll('.delete-item-btn');
    const deleteModal = document.getElementById('deleteModal');
    const cancelDelete = document.getElementById('cancelDelete');
    const deleteForm = document.getElementById('deleteForm');

    deleteButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const form = button.closest('.delete-item-form');
            deleteForm.action = form.action;
            deleteModal.classList.remove('hidden');
        });
    });

    cancelDelete.addEventListener('click', () => {
        deleteModal.classList.add('hidden');
    });
});
