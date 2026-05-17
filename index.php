<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sto. Nino Barangay Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/homepage.css" />
    <style>
        /* ── Login Overlay ── */
        .login-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            padding: 20px;
            font-family: 'Inter', sans-serif;
        }
        .login-overlay.active {
            display: flex;
        }
        .login-overlay .card-container {
            display: flex;
            width: 1300px;
            max-width: 100%;
            height: 700px;
            max-height: 90vh;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            position: relative;
        }
        .login-overlay .left-panel {
            flex: 1;
            background: linear-gradient(190deg, #d3ab79 0%, #871115 100%);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
        }
        .login-overlay .admin-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 30px;
            font-weight: 600;
            opacity: 0.9;
        }
        .login-overlay .shield-icon {
            width: 30px;
            height: 30px;
            border: 1px solid rgba(255,255,255,0.5);
            padding: 4px;
            border-radius: 6px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(255,255,255,0.1);
        }
        .login-overlay .left-panel h1 {
            font-size: 52px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 10px;
            margin-left: 30px;
        }
        .login-overlay .right-panel {
            flex: 1.1;
            padding: 60px 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-overlay .right-panel h2 {
            font-size: 40px;
            color: darkred;
            margin-bottom: 8px;
            font-weight: 700;
        }
        .login-overlay .subtitle {
            color: gray;
            font-size: 15px;
            margin-bottom: 40px;
            line-height: 1.5;
        }
        .login-overlay .error-msg {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 500;
        }
        .login-overlay .input-group {
            margin-bottom: 24px;
        }
        .login-overlay .input-group label {
            display: block;
            color: #475569;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .login-overlay .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .login-overlay .input-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
            color: #334155;
            outline: none;
            transition: border-color 0.2s ease;
            font-family: 'Inter', sans-serif;
        }
        .login-overlay .input-group input::placeholder {
            color: #94a3b8;
        }
        .login-overlay .input-group input:focus {
            border-color: #871115;
            box-shadow: 0 0 0 3px rgba(135,17,21,0.1);
        }
        .login-overlay .submit-btn {
            width: 100%;
            background-color: #98171d;
            color: white;
            border: none;
            padding: 16px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
            transition: background-color 0.2s ease, transform 0.1s ease;
            font-family: 'Inter', sans-serif;
        }
        .login-overlay .submit-btn:hover { background-color: #7a1217; }
        .login-overlay .submit-btn:active { transform: scale(0.95); }
    </style>
</head>
<body>
    <div class="page-container">
        <header>
            <div class="header-branding">
               <img src="assets/barangay-logo.png" alt="Company Logo" width="50">
                <div class="header-text">
                    <div>PWD/CWD <span class="hub-text">Hub</span></div>
                    <span class="system-text">Sto. Nino System</span>
                </div>
            </div>

            <button class="header-admin-btn" onclick="openLogin()" title="Admin Login" aria-label="Admin Login">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                </svg>
            </button>
        </header>

        <main>
            <div class="main-container">
                <div class="welcome-message">
                    <h1>Welcome to the Official Sto. Nino Barangay Web App</h1>
                    <p>This Web App is designed to make barangay services more accessible, <br> organized, and efficient for everyone.</p>
                </div>
            </div>
        </main>
    </div>

    <!-- ── Login Panel Overlay ── -->
    <div class="login-overlay <?= isset($_SESSION['login_error']) ? 'active' : '' ?>" id="loginOverlay" onclick="handleOverlayClick(event)">
        <div class="card-container" id="loginCard">

            <div class="left-panel">
                <div class="admin-badge">
                    <div class="shield-icon"></div>
                </div>
                <h1>PWD & CWD<br>Database</h1>
            </div>

            <div class="right-panel">
                <h2>Mabuhay!</h2>
                <p class="subtitle">Enter your credentials to access the<br>dashboard.</p>

                <?php if (isset($_SESSION['login_error'])): ?>
                <div class="error-msg">
                    <?= htmlspecialchars($_SESSION['login_error']) ?>
                </div>
                <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>

                <form action="func/processLogin.php" method="POST">
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
    </div>

<script>
    function openLogin() {
        document.getElementById('loginOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeLogin() {
        document.getElementById('loginOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    function handleOverlayClick(e) {
        if (e.target === document.getElementById('loginOverlay')) {
            closeLogin();
        }
    }

    function togglePassword() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    function toSelfReg() {
        window.location.href = 'selfregistration.php';
    }
</script>

</body>
</html>
