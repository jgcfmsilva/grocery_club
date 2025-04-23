window.onload = function () {
    const infoAlert = document.getElementById("info-alert");

    if (infoAlert) {
        setTimeout(() => {
            infoAlert.style.display = "none";
        }, 5000);
    }

    const errorAlert = document.getElementById("error-alert");

    if (errorAlert) {
        setTimeout(() => {
            errorAlert.style.display = "none";
        }, 5000);
    }
};
