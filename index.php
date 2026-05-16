<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sto. Nino Barangay Portal Replica</title>
    <link rel="stylesheet" href="css/homepage.css" />
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
            
            <button class="header-admin-btn" onclick="toAdminLogin()" title="Admin Login" aria-label="Admin Login">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path>
                </svg>
            </button>
        </header>

        <main>
            <div class="main-container">
                <div class="welcome-message">
                    <h1>Welcome to the Official Sto. Nino Barangay Portal</h1>
                    <p>This portal is designed to make barangay services more accessible, <br> organized, and efficient for everyone. <br>Choose a portal below to get started.</p>
                </div>

            </div>
        </main>
    </div>
</body>
<script>
    function toSelfReg() {
        window.location.href = "selfregistration.php";
    }

    function toAdminLogin() {
        window.location.href = "login.php";
    }
</script>
</html>