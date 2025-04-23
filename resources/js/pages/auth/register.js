document.addEventListener("DOMContentLoaded", function () {
    const paymentType = document.getElementById("default_payment_type");
    const referenceWrapper = document.getElementById("paymentReferenceWrapper");
    const referenceLabel = document.getElementById("paymentReferenceLabel");

    function hideFields() {
        referenceWrapper.classList.add("hidden");
    }

    function showReferenceField(type) {
        hideFields();

        if (type === "Visa") {
            referenceLabel.textContent = "Card Number (Visa)";
            referenceWrapper.classList.remove("hidden");
        } else if (type === "PayPal") {
            referenceLabel.textContent = "PayPal Email";
            referenceWrapper.classList.remove("hidden");
        } else if (type === "MB WAY") {
            referenceLabel.textContent = "Phone Number (MB WAY)";
            referenceWrapper.classList.remove("hidden");
        }
    }

    paymentType.addEventListener("change", function () {
        const type = paymentType.value;
        showReferenceField(type);
    });

    const form = document.getElementById("register-form");
    const submitButton = document.getElementById("submitButton");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        submitButton.disabled = true;

        form.submit();
    });
});
