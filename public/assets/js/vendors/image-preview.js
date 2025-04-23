function previewImage() {
    const file = document.getElementById("photo").files[0];
    const preview = document.getElementById("photoPreview");
    const placeholder = document.getElementById("photoPlaceholder");

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            console.log(
                "Leitura do arquivo concluída. Resultado:",
                e.target.result
            );

            preview.src = e.target.result;
            preview.classList.remove("hidden");
            placeholder.classList.add("hidden");

            console.log("Imagem pré-visualizada com sucesso!");
        };

        reader.readAsDataURL(file);
    } else {
        preview.classList.add("hidden");
        placeholder.classList.remove("hidden");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("photo")?.addEventListener("change", previewImage);
});
