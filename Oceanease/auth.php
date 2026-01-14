<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>OceanEase Auth</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="css/auth.css">
</head>
<body>

<div class="auth-box">
    <h2>OceanEase</h2>

    <!-- LOGIN FORM -->
    <form id="login-form" onsubmit="event.preventDefault(); submitForm(this,'login')">
        <input name="email" type="email" placeholder="Email" required>

        <div class="password-box">
            <input id="login_pass" name="password" type="password" placeholder="Password" required>
            <span class="eye" onclick="togglePassword(this)" data-tooltip="Show password"><i class="fa fa-eye"></i></span>
        </div>

        <select name="role" required>
            <option value="">Select Role</option>
            <option value="voyager">Voyager</option>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="headcook">HeadCook</option>
            <option value="supervisor">Supervisor</option>
        </select>

        <button>Login</button>

        <div class="bottom-links">
            <span class="toggle" onclick="showForm('register')">New User? Register</span> |
            <a href="forgot_password.php" class="toggle">Forgot Password?</a> <!-- Redirect -->
        </div>
    </form>

    <!-- REGISTER FORM -->
    <form id="register-form" style="display:none" onsubmit="event.preventDefault(); submitForm(this,'register')">
        <input name="name" placeholder="Full Name" required>
        <input name="email" type="email" placeholder="Email" required>

        <div class="password-box">
            <input id="reg_password" name="password" type="password" placeholder="Password" oninput="checkStrength(this.value); checkMatch()" required>
            <span class="eye" onclick="togglePassword(this)" data-tooltip="Show password"><i class="fa fa-eye"></i></span>
        </div>

        <div id="strengthBar"><div id="strengthFill"></div></div>
        <div id="strengthText"></div>

        <div class="password-box">
            <input id="reg_confirm" name="confirm" type="password" placeholder="Confirm Password" oninput="checkMatch()" required>
            <span class="eye" onclick="togglePassword(this)" data-tooltip="Show password"><i class="fa fa-eye"></i></span>
        </div>
        <div id="matchText" style="margin-bottom:10px;font-weight:bold;"></div>

        <button id="registerBtn" disabled>Register</button>
        <div class="toggle" onclick="showForm('login')">Already have an account? Login</div>
    </form>
</div>

<script src="js/auth.js"></script>
</body>
</html>
