<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reports</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/reports.css" />
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
        <a class="nav-sub-item" href="review.php">Review Submissions</a>
      </div>
    </div>

    <div class="nav-group">
      <a class="nav-item active" href="reports.php">
        <img src = "assets/reporticon.png" width="20" >
        Reports
      </a>
    </div>

    <?php 
    $isAdmin = ($_SESSION["role"] ?? "") === "admin";
    $isEncoder = ($_SESSION["role"] ?? "") === "encoder"; 
    if ($isAdmin): ?>
<div class="nav-group">
  <a class="nav-item open" href="#" onclick="toggleMenu(event,'system-sub')">
    <img src="assets/settingicon.png" width="20">
    System
    <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
  </a>
  <div class="nav-sub" id="system-sub">
    <a class="nav-sub-item" href="system.php">System Tools</a>
    <a class="nav-sub-item" href="account.php">Accounts</a>
    <a class="nav-sub-item" href="archive.php">Archive</a>
    <a class="nav-sub-item" href="auditlogs.php">Audit Logs</a>
  </div>
</div>
<?php endif; ?>
  </nav>

  <div class="sidebar-footer">
    <button class="logout-btn" onclick="logout()">
      <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Logout
    </button>
  </div>
</aside>

<!--  MAIN  -->
<main class="main-content">
    <div class="page-title">
        <img src = "assets/leftchevron.png" width="12" onclick="toDashboard()">
        <h1>Reports</h1>
      </div>
  <div class="content-card">
    
    <div class="card-header">
        <div class = "page-title">
            <div class="report-icon">
                <img src = "assets/reporticon2.png" width="20" >
            </div>
            <h1> Generate Reports </h1>
        </div>
    </div>

    <div class = "card-content">
                <div class = "card-item">
                    <div class = "card-item-text">
                        <h2> Master List </h2> 
                        <p> Complete list of all registered PWDs and CWDs</p>
                    </div>
                    <div class = "card-item-actions">
                        <button class="btn-print" onclick="window.open('func/processPrintReports.php?type=master', '_blank')"> 
    <img src="assets/printicon.png" width="16"> Print
</button>
                    </div>
                </div>

                <div class = "card-item">
                    <div class = "card-item-text">
                        <h2> PWD/CWD Summary </h2> 
                        <p> Aggregated statistics by age</p>
                    </div>
                    <div class = "card-item-actions">
                    
                        <div class="dropdown">
    <button class="btn-print dropdown-toggle" onclick="toggleReportDropdown(event, 'pwdCwdMenu')">
        <img src="assets/printicon.png" width="16"> Print
    </button>

    <div class="dropdown-menu" id="pwdCwdMenu">
        <a href="func/processPrintReports.php?type=pwdcwd&category=PWD" target="_blank">Print PWD Summary</a>
        <a href="func/processPrintReports.php?type=pwdcwd&category=CWD" target="_blank">Print CWD Summary</a>
    </div>
</div>
                        
                    </div>
                </div>

                <div class = "card-item">
                    <div class = "card-item-text">
                        <h2> Disability Classification Summary </h2> 
                        <p> Distribution of PWDs and CWDs across different disability types</p>
                    </div>
                    <div class="card-item-actions">
                      
                    <div class="dropdown">
    <button class="btn-print dropdown-toggle" onclick="toggleReportDropdown(event, 'disabilityMenu')">
        <img src="assets/printicon.png" width="16"> Print
    </button>

    <div class="dropdown-menu" id="disabilityMenu">
        <a href="func/processPrintReports.php?type=disability&category=PWD" target="_blank">Print PWD Classification</a>
        <a href="func/processPrintReports.php?type=disability&category=CWD" target="_blank">Print CWD Classification</a>
        <a href="func/processPrintReports.php?type=disability&category=ALL" target="_blank">Print All Classification</a>
    </div>
</div>
                  </div>
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

    trigger.classList.toggle("open");
    submenu.classList.toggle("open");
}

function toDashboard() {
    window.location.href = "dashboard.php";
}

function toggleReportDropdown(event, menuId) {
    event.stopPropagation();

    document.querySelectorAll(".dropdown-menu").forEach(menu => {
        if (menu.id !== menuId) {
            menu.classList.remove("show");
        }
    });

    document.getElementById(menuId).classList.toggle("show");
}

window.addEventListener("click", function () {
    document.querySelectorAll(".dropdown-menu").forEach(menu => {
        menu.classList.remove("show");
    });
});

function logout() {
  window.location.href = "func/logout.php";
}
</script>
</body>
</html>