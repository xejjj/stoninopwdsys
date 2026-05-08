<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>System</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/system.css" />
</head>
<body>

<!-- ── SIDEBAR ── -->
<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon">
      <img src="assets/barangay-logo.png" width="50">
    </div>
    <div class="brand-text">
      <span class="brand-name">PWD/CWD Hub</span>
      <span class="brand-sub">Sto. Niño System</span>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Main Menu</div>

    <div class="nav-group">
      <a class="nav-item" href="dashboard.php">
        <img src = "assets/overviewicon.png" width="20">
        Overview
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item open" href="#" onclick="toggleMenu(event,'mgmt-sub')">
        <img src = "assets/users.png" width="20" >
        Management
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub" id="mgmt-sub">
        <a class="nav-sub-item" href="resident.php">View Residents</a>
        <a class="nav-sub-item" href="registration.php">New Registration</a>
        <a class="nav-sub-item" href="#">Review Submissions</a>
      </div>
    </div>

    <div class="nav-group">
      <a class="nav-item" href="reports.php">
        <img src = "assets/reporticon.png" width="20" >
        Reports
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item open active" href="#" onclick="toggleMenu(event,'system-sub')">
        <img src = "assets/settingicon.png" width="20" >
        System
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub" id="system-sub">
        <a class="nav-sub-item active" href="system.php">System Tools</a>
        <a class="nav-sub-item" href="account.php">Accounts</a>
        <a class="nav-sub-item" href="archive.php">Archive</a>
      </div>
    </div>
  </nav>

  <div class="sidebar-footer">
    <button class="logout-btn" href="login.php">
      <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Logout
    </button>
  </div>
</aside>

<!--  MAIN  -->
<main class="main-content">
    <div class="page-title">
        <img src = "assets/leftchevron.png" width="12" onclick="toDashboard()">
        <h1>System Tools</h1>
      </div>
  <div class="content-card">
    
    <div class="card-header">
        <div class = "page-title">
            <div class="report-icon">
                <img src = "assets/systoolsicon.png" width="20" >
            </div>
            <h1> Data and System </h1>
        </div>
    </div>

    <div class = "card-content">
                <div class = "card-item">
                    <div class = "card-item-text">
                        <h2> Export Data </h2> 
                        <p> Download database as excel file</p>
                    </div>
                    <div class = "card-item-actions">
                        <button class="btn-print"> 
                            <img src = "assets/exporticon.png" width="16" > Export
                        </button>
                    </div>
                </div>

                <div class = "card-item">
                    <div class = "card-item-text">
                        <h2> System Backup </h2> 
                        <p> Create a snapshot of the database</p>
                    </div>
                    <div class = "card-item-actions">
                        <button class="btn-print"> 
                            <img src = "assets/exporticon.png" width="16" > Export
                        </button>
                        
                    </div>
                </div>

                <div class = "card-item">
                    <div class = "card-item-text">
                        <h2> Restore Data </h2> 
                        <p> Restore database from a backup file</p>
                    </div>
                    <div class="card-item-actions">
                    <button class="btn-print"> 
                        <img src="assets/exporticon.png" width="16"> 
                        Export
                        
                </div>
            </div>
        </div>
  </div>
</main>


<script>
function toggleMenu(event, id) {
  event.preventDefault();

  const trigger = event.currentTarget;
  const submenu = document.getElementById(id);

  // Toggle open class on parent
  trigger.classList.toggle("open");

  // Toggle submenu
  submenu.classList.toggle("open");
}
function toDashboard() {
  window.location.href = "dashboard.php";
}

// Toggles the visibility of the dropdown
function toggleDropdown(event) {
    // Prevent the click from immediately bubbling up to the window object
    event.stopPropagation(); 
    document.getElementById("optionsMenu").classList.toggle("show");
}
</script>
</body>
</html>