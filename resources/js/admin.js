import './bootstrap';

document.addEventListener("DOMContentLoaded", (event) => {
    const emailField =
        document.getElementById("exampleInputEmail1");
    const passwordField = document.getElementById(
        "exampleInputPassword1"
    );
    const rememberCheckbox = document.getElementById("remember");
    const passwordToggle = document.getElementById("password-toggle");

    if (passwordToggle) {
        passwordToggle.addEventListener('click', function() {
            const passwordInput = document.getElementById('exampleInputPassword1');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }

    if (getCookie("email") && getCookie("password")) {
        emailField.value = getCookie("email");
        passwordField.value = getCookie("password");
        rememberCheckbox.checked = true;
    }

    document
        .getElementById("loginForm")
        .addEventListener("submit", function (event) {
            if (rememberCheckbox.checked) {
                setCookie("email", emailField.value, 30);
                setCookie("password", passwordField.value, 30);
            } else {
                setCookie("email", "", 0);
                setCookie("password", "", 0);
            }
        });

    function setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
        const expires = "expires=" + date.toUTCString();
        document.cookie =
            name + "=" + value + ";" + expires + ";path=/";
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(";");
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == " ") c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0)
                return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
});
