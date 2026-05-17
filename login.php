<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css" />
</head>

<body>
    <button class="return-btn" onclick="toHome()">
        <img src="assets/leftarrow1.png" width="12">
        Return to Home
    </button>

    <div class="card-container">
        
        <div class="left-panel">
            <div class="admin-badge">
                <div class="shield-icon"><img src="assets/barangay-logo.png" width="50"></div>
                Admin Portal
            </div>
            <h1>PWD & CWD<br>Database</h1>
        </div>

        <div class="right-panel">
            <h2>Mabuhay!</h2>
            <p class="subtitle">Enter your credentials to access the<br>dashboard.</p>

            <?php if (isset($_SESSION["login_error"])): ?>
            <div class="error-msg">
                <?= htmlspecialchars($_SESSION["login_error"]) ?>
            </div>
            <?php unset($_SESSION["login_error"]); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION["forgot_msg"])): ?>
            <div class="success-msg">
                <?= htmlspecialchars($_SESSION["forgot_msg"]) ?>
            </div>
            <?php unset($_SESSION["forgot_msg"]); ?>
            <?php endif; ?>

            <form action="func/processLogin.php" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" placeholder="Enter Username" value="<?= isset($_COOKIE['remembered_username']) ? htmlspecialchars($_COOKIE['remembered_username']) : '' ?>">
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter Password">
                        <span class="eye-icon" onclick="togglePassword()"></span>
                    </div>
                </div>

                <div class="login-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember_me" <?= isset($_COOKIE['remembered_username']) ? 'checked' : '' ?>>
                        <span class="checkmark"></span>
                        Remember Me
                    </label>
                    <a href="#" class="forgot-pwd" onclick="openForgotModal(event)">Forgot Password?</a>
                </div>

                <button type="submit" class="submit-btn">
                    Sign In to dashboard 
                </button>
            </form>
        </div>

    </div>

    <div id="forgotModalOverlay" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Reset Password</h3>
                <button class="close-btn" onclick="closeForgotModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Enter the email address associated with your account and we will send you a link to reset your password.</p>
                <form action="func/processForgotPass.php" method="POST">
                    <div class="input-group">
                        <label for="forgot_email">Email Address</label>
                        <input type="email" id="forgot_email" name="email" placeholder="e.g. admin@stonino.gov.ph" required>
                    </div>
                    <button type="submit" class="submit-btn">Send Recovery Email</button>
                </form>
            </div>
        </div>
    </div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
    }

    function toHome() {
        window.location.href = "index.php";
    }

    function openForgotModal(e) {
        e.preventDefault();
        document.getElementById('forgotModalOverlay').style.display = 'flex';
    }

    function closeForgotModal() {
        document.getElementById('forgotModalOverlay').style.display = 'none';
    }
</script>

</body>
</html>