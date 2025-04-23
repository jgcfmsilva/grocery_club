

// code to pass the email from the login view to the forgotpassword view
document.getElementById('forgotPasswordLink').addEventListener('click', function (e) {
    // puts the email in the hidden form
    document.getElementById('hiddenEmailField').value = document.getElementById('email').value;

    // submits the form
   document.getElementById('forgotPassword-form').submit();
});
