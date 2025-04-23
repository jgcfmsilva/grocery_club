document.addEventListener("DOMContentLoaded", function () {
    const paymentType = document.getElementById("default_payment_type");
    const referenceInput = document.getElementById("default_payment_reference");
    const referenceWrapper = document.getElementById("paymentReferenceWrapper");
    const referenceLabel = document.getElementById("paymentReferenceLabel");
    const form = document.getElementById("personal-data-form");
    const submitButton = document.getElementById("submitButton");

    const referenceValues = {
        Visa: "",
        PayPal: "",
        "MB WAY": "",
    };

    function hideFields() {
        referenceWrapper.classList.add("hidden");
    }

    function showReferenceField(type) {
        hideFields();

        if (!type || !referenceValues.hasOwnProperty(type)) {
            return;
        }

        if (type === "Visa") {
            referenceLabel.textContent = "Card Number (Visa)";
        } else if (type === "PayPal") {
            referenceLabel.textContent = "PayPal Email";
        } else if (type === "MB WAY") {
            referenceLabel.textContent = "Phone Number (MB WAY)";
        }

        referenceWrapper.classList.remove("hidden");
        referenceInput.value = referenceValues[type] || "";
    }

    paymentType.addEventListener("change", function () {
        const previousType = paymentType.dataset.previousType;
        const currentType = paymentType.value;

        if (previousType && referenceInput.value.trim() !== "") {
            referenceValues[previousType] = referenceInput.value.trim();
        }

        referenceInput.value = "";
        showReferenceField(currentType);
        paymentType.dataset.previousType = currentType;
    });

    form.addEventListener("submit", function (event) {
        event.preventDefault();
        submitButton.disabled = true;
        form.submit();
    });

    const selectedType = paymentType.value;
    const hasReferenceValue = referenceInput.value.trim() !== "";

    if (selectedType) {
        if (hasReferenceValue) {
            referenceValues[selectedType] = referenceInput.value.trim();
        }
        paymentType.dataset.previousType = selectedType;
        showReferenceField(selectedType);
    }
});
