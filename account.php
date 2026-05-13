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
<?php
session_start();
require_once("func/db.php");

$users_query = mysqli_query($conn, "SELECT * FROM admincreds ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Accounts</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/account.css" />
</head>
<body>

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
        <img src="assets/overviewicon.png" width="20"> Overview
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item open" href="#" onclick="toggleMenu(event,'mgmt-sub')">
        <img src="assets/users.png" width="20"> Management
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
        <img src="assets/reporticon.png" width="20"> Reports
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item active" href="#" onclick="toggleMenu(event,'system-sub')">
        <img src="assets/settingicon.png" width="20"> System
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub open" id="system-sub">
        <a class="nav-sub-item" href="system.php">System Tools</a>
        <a class="nav-sub-item active" href="account.php">Accounts</a>
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

<main class="main-content">
  <header class="page-header">
    <div class="header-left">
      <div class="title-row">
        <img class="back-chevron" src="assets/leftchevron.png" width="12" onclick="toDashboard()">
        <h1>Accounts</h1>
      </div>
      <p class="subtitle">Manage administrators and encoders accessing the system.</p>
    </div>

    <div class="header-right">
      <button class="add-user-btn" onclick="openAddModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
          <circle cx="8.5" cy="7" r="4"></circle>
          <line x1="20" y1="8" x2="20" y2="14"></line>
          <line x1="23" y1="11" x2="17" y2="11"></line>
        </svg>
        Add New User
      </button>
    </div>
  </header>

  <div class="table-container">
    <table class="data-table">
      <thead class="table-head-light">
        <tr>
          <th>USER</th>
          <th class="text-center">DATE ADDED</th>
          <th class="text-right">ACTIONS</th>
        </tr>
      </thead>

      <tbody>
        <?php if (mysqli_num_rows($users_query) > 0): ?>
          <?php while ($u = mysqli_fetch_assoc($users_query)): ?>
            <?php
              $id = $u["id"] ?? $u["ID"] ?? 0;
              $full_name = $u["full_name"] ?? "";
              $username = $u["username"] ?? "";
              $role = $u["role"] ?? "encoder";
              $date_added = !empty($u["created_at"]) ? date("m/d/y", strtotime($u["created_at"])) : date("m/d/y");
            ?>

            <tr>
              <td class="fw-bold">
                <?= htmlspecialchars($full_name) ?><br>
                <span class="user-meta">
                  @<?= htmlspecialchars($username) ?> • <?= ucfirst(htmlspecialchars($role)) ?>
                </span>
              </td>

              <td class="text-center fw-bold"><?= $date_added ?></td>

              <td class="text-right">
                <div class="action-buttons">
                  <button
                    class="icon-btn edit-btn"
                    title="Edit"
                    onclick="openEditModal(
                      <?= (int)$id ?>,
                      '<?= htmlspecialchars($full_name, ENT_QUOTES) ?>',
                      '<?= htmlspecialchars($username, ENT_QUOTES) ?>',
                      '<?= htmlspecialchars($role, ENT_QUOTES) ?>'
                    )"
                  >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                      <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                  </button>

                  <button class="icon-btn delete-btn" title="Delete" onclick="openDeleteModal(<?= (int)$id ?>)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6"></polyline>
                      <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center user-meta">No accounts found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<div id="addModal" class="modal-overlay">
  <div class="modal-box">
    <div class="modal-icon add">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
        <circle cx="8.5" cy="7" r="4"></circle>
        <line x1="20" y1="8" x2="20" y2="14"></line>
        <line x1="23" y1="11" x2="17" y2="11"></line>
      </svg>
    </div>

    <h2 class="modal-title">Add New User</h2>

    <form action="func/processAddUser.php" method="POST" onsubmit="return validateAddPassword()">
      <div class="modal-group">
        <label>Full Name</label>
        <input type="text" name="full_name" class="modal-input" required>
      </div>

      <div class="modal-group">
        <label>Username</label>
        <input type="text" name="username" class="modal-input" required>
      </div>

      <div class="modal-group">
        <label>Role</label>
        <select name="role" class="modal-input" required>
          <option value="admin">Admin</option>
          <option value="encoder" selected>Encoder</option>
        </select>
      </div>

      <div class="modal-group">
        <label>Password</label>
        <input type="password" name="password" id="addPassword" class="modal-input" required>
      </div>

      <div class="modal-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" id="addConfirmPassword" class="modal-input" required>
      </div>

      <div class="modal-actions">
        <button type="button" class="modal-btn cancel" onclick="closeModals()">Cancel</button>
        <button type="submit" class="modal-btn add">Add User</button>
      </div>
    </form>
  </div>
</div>

<div id="editModal" class="modal-overlay">
  <div class="modal-box">
    <div class="modal-icon edit">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
      </svg>
    </div>

    <h2 class="modal-title">Edit User</h2>

    <form action="func/processEditUser.php" method="POST" onsubmit="return validateEditPassword()">
      <input type="hidden" name="user_id" id="editUserId">

      <div class="modal-group">
        <label>Full Name</label>
        <input type="text" name="full_name" id="editFullName" class="modal-input" required>
      </div>

      <div class="modal-group">
        <label>Username</label>
        <input type="text" name="username" id="editUsername" class="modal-input" required>
      </div>

      <div class="modal-group">
        <label>Role</label>
        <select name="role" id="editRole" class="modal-input" required>
          <option value="admin">Admin</option>
          <option value="encoder">Encoder</option>
        </select>
      </div>

      <div class="modal-group">
        <label>
          New Password
          <span class="modal-note">(leave blank to keep current)</span>
        </label>
        <input type="password" name="password" id="editPassword" class="modal-input">
      </div>

      <div class="modal-group">
        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" id="editConfirmPassword" class="modal-input">
      </div>

      <div class="modal-actions">
        <button type="button" class="modal-btn cancel" onclick="closeModals()">Cancel</button>
        <button type="submit" class="modal-btn edit">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<div id="deleteModal" class="modal-overlay">
  <div class="modal-box">
    <div class="modal-icon delete">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="3 6 5 6 21 6"></polyline>
        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
      </svg>
    </div>

    <h2 class="modal-title">Delete Account?</h2>

    <p class="modal-description">
      This action cannot be undone. Are you sure you want to remove this user?
    </p>

    <div class="modal-actions">
      <button type="button" class="modal-btn cancel" onclick="closeModals()">Cancel</button>

      <form action="func/processDeleteUser.php" method="POST">
        <input type="hidden" name="user_id" id="deleteUserId">
        <button type="submit" class="modal-btn delete">Yes, Delete</button>
      </form>
    </div>
  </div>
</div>

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

function logout() {
  window.location.href = "func/logout.php";
}

function openAddModal() {
  document.getElementById("addModal").style.display = "flex";
}

function openEditModal(id, fullName, username, role) {
  document.getElementById("editUserId").value = id;
  document.getElementById("editFullName").value = fullName;
  document.getElementById("editUsername").value = username;
  document.getElementById("editRole").value = role;
  document.getElementById("editPassword").value = "";
  document.getElementById("editConfirmPassword").value = "";
  document.getElementById("editModal").style.display = "flex";
}

function openDeleteModal(id) {
  document.getElementById("deleteUserId").value = id;
  document.getElementById("deleteModal").style.display = "flex";
}

function closeModals() {
  document.getElementById("addModal").style.display = "none";
  document.getElementById("editModal").style.display = "none";
  document.getElementById("deleteModal").style.display = "none";
}

function validateAddPassword() {
  const pass = document.getElementById("addPassword").value;
  const confirm = document.getElementById("addConfirmPassword").value;

  if (pass !== confirm) {
    alert("Passwords do not match. Please try again.");
    return false;
  }

  return true;
}

function validateEditPassword() {
  const pass = document.getElementById("editPassword").value;
  const confirm = document.getElementById("editConfirmPassword").value;

  if (pass !== confirm) {
    alert("Passwords do not match. Please try again.");
    return false;
  }

  return true;
}
</script>

</body>
</html>