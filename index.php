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
                        <!-- papalitan nalang yung laman ng icon sa taas nito (placeholder icon lng muna) -->
                 
                <div class="header-text">
                    <div>PWD/CWD <span class="hub-text">Hub</span></div>
                    <span class="system-text">Sto. Nino System</span>
                </div>
            </div>
        </header>

        <main>
            <div class="main-container">
                <div class="welcome-message">
                    <h1>Welcome to the Official Sto. Nino Barangay Portal</h1>
                    <p>This portal is designed to make barangay services more accessible, <br> organized, and efficient for everyone. <br>Choose a portal below to get started.</p>
                </div>

                <div class="portal-cards-grid">
                    <div class="portal-card">
                           <img src="assets/users.png" alt="Company Logo" width="40">
                        <!-- papalitan nalang yung laman ng icon sa taas nito (placeholder icon lng muna) -->
                        <h2>Resident Registration</h2>
                        <p>Apply for your PWD ID or update <br> your existing records online.<br> Quick, easy, and accessible to <br>everyone.</p>
                        <button class="card-button" onclick = "toSelfReg()">
                            Start Registration
                        </button>
                    </div>

                    <div class="portal-card">
                        <img src="assets/users.png" alt="Company Logo" width="40">
                        <!-- papalitan nalang yung laman ng icon sa taas nito (placeholder icon lng muna) -->
                        <h2>Administrator Portal</h2>
                        <p>Secure access for barangay<br> officials to review submissions,<br> manage records, and view<br> analytical reports.</p>
                        <button class="card-button" onclick = "toLogin()"> 
                         
                                Admin Login
                          
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
<script>

    function toSelfReg() {
        window.location.href = "selfregistration.php";
    }
    function toLogin() {
        window.location.href = "login.php";
    }
</script>
</html>