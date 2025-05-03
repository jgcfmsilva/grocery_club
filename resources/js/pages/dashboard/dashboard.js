document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('user-avatar-btn').addEventListener('click', function() {
        const dropdown = document.getElementById('user-dropdown');
        dropdown.classList.toggle('hidden'); // Alterna a visibilidade
        dropdown.classList.toggle('opacity-0'); // Alterna a opacidade
        dropdown.classList.toggle('scale-95'); // Alterna a escala
        dropdown.classList.toggle('opacity-100'); // Alterna a opacidade
        dropdown.classList.toggle('scale-100'); // Alterna a escala
    });

    // Fechar o dropdown se o usuário clicar fora
    window.addEventListener('click', function(event) {
        const dropdown = document.getElementById('user-dropdown');
        const avatarButton = document.getElementById('user-avatar-btn');
        if (!avatarButton.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
            dropdown.classList.remove('opacity-100', 'scale-100');
            dropdown.classList.add('opacity-0', 'scale-95');
        }
    });
});