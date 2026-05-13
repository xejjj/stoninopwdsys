<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION["role"] ?? "";

/* BLOCK ENCODERS */
if ($role !== "admin") {

    $_SESSION["error"] =
        "You cannot access this module.";

    header("Location: dashboard.php");
    exit();
}
?>
<?php session_start();?>
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
        <a class="nav-sub-item" href="review.php">Review Submissions</a>
      </div>
    </div>

    <div class="nav-group">
      <a class="nav-item" href="reports.php">
        <img src = "assets/reporticon.png" width="20" >
        Reports
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item active" href="#" onclick="toggleMenu(event,'system-sub')">
        <img src = "assets/settingicon.png" width="20" >
        System
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub open" id="system-sub">
        <a class="nav-sub-item active" href="system.php">System Tools</a>
        <a class="nav-sub-item" href="account.php">Accounts</a>
        <a class="nav-sub-item" href="archive.php">Archive</a>
        <a class="nav-sub-item" href="auditlogs.php">Audit Logs</a>
      </div>
    </div>
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
                
                <div class="card-item-actions">

                  <div class="dropdown">

            <button class="btn-print"
                    onclick="toggleExportDropdown(event)">
                <img src="assets/exporticon.png" width="16">
                Export
            </button>

            <div class="dropdown-menu"
                 id="exportDropdown">

                <a href="func/processExportExcel.php">
                    Export as Excel
                </a>

                <a href="func/processExportPDF.php">
                    Export as PDF
                </a>

            </div>

        </div>

    </div>
    </div>

                <div class = "card-item">
                    <div class = "card-item-text">
                        <h2> System Backup </h2> 
                        <p> Create a snapshot of the database</p>
                    </div>
                    <div class = "card-item-actions">
                        <a href="func/processBackupData.php" >
                            <button class="btn-print"> 
                                <img src = "assets/exporticon.png" width="16" > Export
                            </button>
                        </a>
                    </div>
                </div>

                <div class = "card-item">
                    <div class = "card-item-text">
                        <h2> Restore Data </h2> 
                        <p> Restore database from a backup file</p>
                    </div>
                    <div class="card-item-actions">

    <form action="func/processRestoreData.php"
      method="POST"
      enctype="multipart/form-data"
      id="restoreForm">

    <input type="file"
           name="backup_file"
           id="backup_file"
           accept=".sql"
           style="display:none;"
           required>

    <button type="button"
            class="btn-print"
            onclick="document.getElementById('backup_file').click();">
        <img src="assets/exporticon.png" width="16">
        Restore
    </button>

    <button type="submit"
            name="restore"
            id="realRestoreSubmit"
            style="display:none;">
    </button>

</form>

</div>
            </div>
        </div>
  </div>
  
  <div class="modal-overlay" id="restoreModal">

    <div class="modal-box">

        <h2>Confirm Restoration</h2>

        <p>
            Restoring a backup may overwrite current database data.
            Are you sure you want to continue?
        </p>

        <div class="modal-actions">

            <button class="btn-cancel"
                    onclick="closeRestoreModal()">

                Cancel

            </button>

            <button class="btn-confirm"
                    onclick="submitRestoreForm()">

                Confirm Restore

            </button>

        </div>

    </div>

</div>

<?php if (isset($_SESSION['success'])) : ?>

<div class="popup-success show-popup">

    <div class="popup-box">

        <h2>Success</h2>

        <p>
            <?php
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
        </p>

        <button onclick="closePopup()">
            OK
        </button>

    </div>

</div>

<?php endif; ?>


<?php if (isset($_SESSION['error'])) : ?>

<div class="popup-error show-popup">

    <div class="popup-box">

        <h2>Error</h2>

        <p>
            <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
        </p>

        <button onclick="closePopup()">
            OK
        </button>

    </div>

</div>

<?php endif; ?>

</main>


<script>
    function logout() {
  window.location.href = "func/logout.php";
}
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

const backupInput = document.getElementById("backup_file");

backupInput.addEventListener("change", function () {

    if (this.files.length > 0) {

        document.getElementById("restoreModal")
                .classList.add("show-modal");
    }
});

function closeRestoreModal() {

    document.getElementById("restoreModal")
            .classList.remove("show-modal");

    backupInput.value = "";
}

function submitRestoreForm() {

    document.getElementById("restoreForm").submit();
}

function closePopup() {

    document.querySelectorAll(
        ".popup-success, .popup-error"
    ).forEach(el => {

        el.classList.remove("show-popup");
    });
}

function toggleExportDropdown(event) {

    event.stopPropagation();

    document.getElementById("exportDropdown")
            .classList.toggle("show");
}

/* CLOSE DROPDOWN WHEN CLICKING OUTSIDE */

window.addEventListener("click", function() {

    const dropdown =
        document.getElementById("exportDropdown");

    if (dropdown.classList.contains("show")) {

        dropdown.classList.remove("show");
    }
});

function submitRestoreForm() {
    document.getElementById("realRestoreSubmit").click();
}


</script>
</body>
</html>