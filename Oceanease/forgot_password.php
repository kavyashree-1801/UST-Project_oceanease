<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<link rel="stylesheet" href="css/forgot_password.css">
</head>
<body>

<div class="box">
    <h2>Forgot Password</h2>

    <form id="forgotForm">
        <input type="email" id="email" name="email" placeholder="Enter registered email" required>
        <button type="submit">Send Reset Link</button>
    </form>

    <!-- LINK WILL SHOW HERE -->
    <div id="result"></div>

    <a href="auth.php" class="back">Back to Login</a>
</div>

<script src="js/forgot_password.js"></script>
</body>
</html>
