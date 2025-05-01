document.addEventListener("DOMContentLoaded", function () {
    const paymentType = document.getElementById("default_payment_type");
    const cvcDiv = document.getElementById("cardVisaCvc");
    const referenceWrapper = document.getElementById("paymentReferenceWrapper");
    const referenceLabel = document.getElementById("paymentReferenceLabel");
    const referenceInput = document.getElementById("default_payment_reference");
    const cvcInput = document.getElementById("payment_cvc");

    const defaultPaymentType = paymentType.value;
    const defaultReferenceValue = referenceInput.value;

    function hideFields() {
        referenceWrapper.classList.add("hidden");
        cvcDiv.classList.add("hidden");
        cvcInput.required = false;
        referenceInput.required = false;
    }

    function showReferenceField(type) {
        hideFields();

        if (type === "Visa") {
            referenceLabel.textContent = "Card Number (Visa)";
            referenceWrapper.classList.remove("hidden");
            cvcDiv.classList.remove("hidden");

            referenceInput.required = true;
            cvcInput.required = true;
        } else if (type === "PayPal") {
            referenceLabel.textContent = "PayPal Email";
            referenceWrapper.classList.remove("hidden");
            cvcDiv.classList.add("hidden");

            referenceInput.required = true;
            cvcInput.required = false;
        } else if (type === "MB WAY") {
            referenceLabel.textContent = "Phone Number (MB WAY)";
            referenceWrapper.classList.remove("hidden");
            cvcDiv.classList.add("hidden");

            referenceInput.required = true;
            cvcInput.required = false;
        }

        if (type !== defaultPaymentType) {
            referenceInput.value = "";
        } else {
            referenceInput.value = defaultReferenceValue;
        }
    }

    if (paymentType.value) {
        showReferenceField(paymentType.value);
    }

    paymentType.addEventListener("change", function () {
        const type = paymentType.value;
        showReferenceField(type);
    });

    const form = document.getElementById("topup-card-form");
    const submitButton = document.getElementById("submitButton");

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        submitButton.disabled = true;

        form.submit();
    });
});
