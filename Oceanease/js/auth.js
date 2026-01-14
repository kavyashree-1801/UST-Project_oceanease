/* ================= SHOW LOGIN / REGISTER FORMS ================= */
function showForm(type) {
    document.getElementById("login-form").style.display =
        type === "login" ? "block" : "none";
    document.getElementById("register-form").style.display =
        type === "register" ? "block" : "none";
}

/* ================= TOGGLE PASSWORD VISIBILITY ================= */
function togglePassword(el) {
    const input = el.previousElementSibling;
    const icon = el.querySelector("i");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
        el.dataset.tooltip = "Hide password";
    } else {
        input.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
        el.dataset.tooltip = "Show password";
    }
}

/* ================= SHOW ON HOVER ================= */
function hoverShow(el) {
    el.previousElementSibling.type = "text";
}

function hoverHide(el) {
    const input = el.previousElementSibling;
    if (!el.querySelector("i").classList.contains("fa-eye-slash")) {
        input.type = "password";
    }
}

/* ================= PASSWORD STRENGTH METER ================= */
function checkStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[\W]/.test(password)) strength++;

    const bar = document.getElementById("strengthFill");
    const text = document.getElementById("strengthText");

    const levels = ["", "Weak", "Weak", "Medium", "Strong", "Very Strong"];
    const colors = ["#ddd", "red", "orange", "yellow", "lightgreen", "green"];

    bar.style.width = `${strength * 20}%`;
    bar.style.background = colors[strength];
    text.textContent = levels[strength];
}

/* ================= CONFIRM PASSWORD MATCH ================= */
function checkMatch() {
    const pass = document.getElementById("reg_password").value;
    const confirm = document.getElementById("reg_confirm").value;
    const registerBtn = document.getElementById("registerBtn");

    registerBtn.disabled = pass !== confirm || pass.length < 8;

    const matchText = document.getElementById("matchText");
    if (pass && confirm) {
        matchText.textContent = pass === confirm ? "Passwords match ✅" : "Passwords do not match ❌";
        matchText.style.color = pass === confirm ? "green" : "red";
    } else {
        matchText.textContent = "";
    }
}

/* ================= FORM VALIDATION BEFORE SUBMIT ================= */
function validateForm(form) {
    const password = form.querySelector("input[type='password']").value;
    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return false;
    }
    if (!/[A-Z]/.test(password)) {
        alert("Password must contain at least one uppercase letter.");
        return false;
    }
    if (!/[a-z]/.test(password)) {
        alert("Password must contain at least one lowercase letter.");
        return false;
    }
    if (!/[0-9]/.test(password)) {
        alert("Password must contain at least one number.");
        return false;
    }
    return true;
}

/* ================= AJAX FORM SUBMIT ================= */
async function submitForm(form, action) {
    if (!validateForm(form)) return;

    const formData = new FormData(form);
    formData.append("action", action);

    try {
        const res = await fetch("api/auth_api.php", {
            method: "POST",
            body: formData
        });

        const data = await res.json();
        alert((data.messages && data.messages.join("\n")) || "Request processed");

        if (data.status === "success") {
            if (action === "login") {
                const routes = {
                    voyager: "homepage.php",
                    admin: "admin_homepage.php",
                    manager: "manager_dashboard.php",
                    headcook: "headcook_dashboard.php",
                    supervisor: "supervisor_dashboard.php"
                };
                window.location.href = routes[data.role];
            } else {
                window.location.href = "auth.php";
            }
        }
    } catch (err) {
        console.error(err);
        alert("An error occurred while processing your request.");
    }
}

/* ================= INIT EVENT LISTENERS ================= */
document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById("reg_password");
    const confirmInput = document.getElementById("reg_confirm");

    if (passwordInput) {
        passwordInput.addEventListener("input", () => {
            checkStrength(passwordInput.value);
            checkMatch();
        });
    }

    if (confirmInput) {
        confirmInput.addEventListener("input", checkMatch);
    }
});
