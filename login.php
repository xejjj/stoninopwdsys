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
                <div class="shield-icon"></div>
                Admin Portal
            </div>
            <h1>PWD & CWD<br>Database</h1>
        </div>

        <div class="right-panel">
            <h2>Mabuhay!</h2>
            <p class="subtitle">Enter your credentials to access the<br>dashboard.</p>

          

            <!-- success and error FOR NOW, will replace with popups later -->
            <?php if (isset($_SESSION["login_error"])): ?>
            <div class="error-msg">
                <?= htmlspecialchars($_SESSION["login_error"]) ?>
            </div>
            <?php unset($_SESSION["login_error"]); ?>
            <?php endif; ?>

            <form action="sqlLogin.php" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" placeholder="Enter Username">
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter Password">
                        <span class="eye-icon" onclick="togglePassword()"></span>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    Sign In to dashboard 
                </button>
            </form>
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
</script>

</body>
</html>