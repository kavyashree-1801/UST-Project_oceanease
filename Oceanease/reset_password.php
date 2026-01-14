<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<link rel="stylesheet" href="css/reset_password.css">
</head>
<body>

<div class="auth-box">
    <h2>Reset Password</h2>

    <form id="resetForm">
        <input type="hidden" id="reset_id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">

        <!-- New Password -->
        <div class="password-wrapper">
            <input type="password" id="new_password" placeholder="Enter new password" required>
            <svg class="toggle-eye" onclick="togglePassword('new_password', this)"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
            </svg>
        </div>

        <!-- Confirm Password -->
        <div class="password-wrapper">
            <input type="password" id="confirm_password" placeholder="Confirm Password" required>
            <svg class="toggle-eye" onclick="togglePassword('confirm_password', this)"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 5C7 5 2.73 8.11 1 12c1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
            </svg>
        </div>

        <button type="submit">Reset Password</button>
    </form>

    <div id="result"></div>

    <a href="auth.php" class="back-btn">Back to Login</a>
</div>

<script src="js/reset_password.js"></script>
</body>
</html>
