<?php
session_start();
require_once("func/db.php");

// Fetch users from the database
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
        <img src = "assets/overviewicon.png" width="20"> Overview
      </a>
    </div>
    <div class="nav-group">
      <a class="nav-item open" href="#" onclick="toggleMenu(event,'mgmt-sub')">
        <img src = "assets/users.png" width="20" > Management
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
        <img src = "assets/reporticon.png" width="20" > Reports
      </a>
    </div>
    <div class="nav-group">
      <a class="nav-item active" href="#" onclick="toggleMenu(event,'system-sub')">
        <img src = "assets/settingicon.png" width="20" > System
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub open" id="system-sub">
        <a class="nav-sub-item" href="system.php">System Tools</a>
        <a class="nav-sub-item active" href="account.php">Accounts</a>
        <a class="nav-sub-item" href="archive.php">Archive</a>
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
        <img style="cursor:pointer;" src="assets/leftchevron.png" width="12" onclick="toDashboard()">
        <h1>Accounts</h1>
      </div>
      <p class="subtitle">Manage administrators and encoders accessing the system.</p>
    </div>
    
    <div class="header-right">
      <button class="add-user-btn" onclick="openAddModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
        Add New User
      </button>
    </div>
  </header>

  <div class="table-container">
    <table class="data-table">
      <thead style="background-color: #f9f9f9;">
        <tr>
          <th>USER</th>
          <th class="text-center">DATE ADDED</th>
          <th class="text-right">ACTIONS</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if(mysqli_num_rows($users_query) > 0):
            while($u = mysqli_fetch_assoc($users_query)): 
                // Format date safely, fallback to today if column doesn't exist
                $date_added = !empty($u['created_at']) ? date('m/d/y', strtotime($u['created_at'])) : date('m/d/y');
        ?>
        <tr>
          <td class="fw-bold"><?= htmlspecialchars($u['username']) ?></td>
          <td class="text-center fw-bold"><?= $date_added ?></td>
          <td class="text-right">
            <div class="action-buttons">
              <button class="icon-btn edit-btn" title="Edit" onclick="openEditModal(<?= $u['ID'] ?>, '<?= htmlspecialchars($u['username']) ?>')">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
              </button>
              <button class="icon-btn delete-btn" title="Delete" onclick="openDeleteModal(<?= $u['ID'] ?>)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              </button>
            </div>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr>
            <td colspan="3" class="text-center" style="color: var(--text-gray);">No accounts found.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</main>

<div id="addModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
    <div style="width:48px; height:48px; background:#EAF9EE; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#38C966" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; margin-bottom:20px; color:#1c0202; text-align:center;">Add New User</h2>
    
    <form action="func/processAddUser.php" method="POST" style="margin:0;" onsubmit="return validateAddPassword()">
      <div style="text-align: left; margin-bottom: 16px;">
        <label style="display:block; font-size:13px; font-weight:700; color:rgba(28,2,2,0.6); margin-bottom:6px;">Username</label>
        <input type="text" name="username" required style="width:100%; padding:10px 14px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:#f9f9f9; font-family:inherit; font-size:14px; outline:none; box-sizing:border-box;">
      </div>
      <div style="text-align: left; margin-bottom: 16px;">
        <label style="display:block; font-size:13px; font-weight:700; color:rgba(28,2,2,0.6); margin-bottom:6px;">Password</label>
        <input type="password" name="password" id="addPassword" required style="width:100%; padding:10px 14px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:#f9f9f9; font-family:inherit; font-size:14px; outline:none; box-sizing:border-box;">
      </div>
      <div style="text-align: left; margin-bottom: 24px;">
        <label style="display:block; font-size:13px; font-weight:700; color:rgba(28,2,2,0.6); margin-bottom:6px;">Confirm Password</label>
        <input type="password" name="confirm_password" id="addConfirmPassword" required style="width:100%; padding:10px 14px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:#f9f9f9; font-family:inherit; font-size:14px; outline:none; box-sizing:border-box;">
      </div>
      <div style="display:flex; gap:10px; justify-content:center;">
        <button type="button" onclick="closeModals()" style="padding:8px 18px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:none; font-family:inherit; font-weight:700; cursor:pointer; color:rgba(28,2,2,0.6);">Cancel</button>
        <button type="submit" style="padding:8px 18px; border-radius:8px; border:none; background:#38C966; color:#fff; font-family:inherit; font-weight:700; cursor:pointer;">Add User</button>
      </div>
    </form>
  </div>
</div>

<div id="editModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
    <div style="width:48px; height:48px; background:#E6F0FA; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2D72D2" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; margin-bottom:20px; color:#1c0202; text-align:center;">Edit User</h2>
    
    <form action="func/processEditUser.php" method="POST" style="margin:0;" onsubmit="return validateEditPassword()">
      <input type="hidden" name="user_id" id="editUserId">
      <div style="text-align: left; margin-bottom: 16px;">
        <label style="display:block; font-size:13px; font-weight:700; color:rgba(28,2,2,0.6); margin-bottom:6px;">Username</label>
        <input type="text" name="username" id="editUsername" required style="width:100%; padding:10px 14px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:#f9f9f9; font-family:inherit; font-size:14px; outline:none; box-sizing:border-box;">
      </div>
      <div style="text-align: left; margin-bottom: 16px;">
        <label style="display:block; font-size:13px; font-weight:700; color:rgba(28,2,2,0.6); margin-bottom:6px;">New Password <span style="font-weight:400; font-size:11px;">(leave blank to keep current)</span></label>
        <input type="password" name="password" id="editPassword" style="width:100%; padding:10px 14px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:#f9f9f9; font-family:inherit; font-size:14px; outline:none; box-sizing:border-box;">
      </div>
      <div style="text-align: left; margin-bottom: 24px;">
        <label style="display:block; font-size:13px; font-weight:700; color:rgba(28,2,2,0.6); margin-bottom:6px;">Confirm New Password</label>
        <input type="password" name="confirm_password" id="editConfirmPassword" style="width:100%; padding:10px 14px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:#f9f9f9; font-family:inherit; font-size:14px; outline:none; box-sizing:border-box;">
      </div>
      <div style="display:flex; gap:10px; justify-content:center;">
        <button type="button" onclick="closeModals()" style="padding:8px 18px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:none; font-family:inherit; font-weight:700; cursor:pointer; color:rgba(28,2,2,0.6);">Cancel</button>
        <button type="submit" style="padding:8px 18px; border-radius:8px; border:none; background:#2D72D2; color:#fff; font-family:inherit; font-weight:700; cursor:pointer;">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
    <div style="width:48px; height:48px; background:#FEE2E2; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; margin-bottom:10px; color:#1c0202; text-align:center;">Delete Account?</h2>
    <p style="font-size:13.5px; color:rgba(28,2,2,0.6); margin-bottom:24px; text-align:center;">This action cannot be undone. Are you sure you want to remove this user?</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="closeModals()" style="padding:8px 18px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:none; font-family:inherit; font-weight:700; cursor:pointer; color:rgba(28,2,2,0.6);">Cancel</button>
      <form action="func/processDeleteUser.php" method="POST" style="margin:0;">
        <input type="hidden" name="user_id" id="deleteUserId">
        <button type="submit" style="padding:8px 18px; border-radius:8px; border:none; background:#DC2626; color:#fff; font-family:inherit; font-weight:700; cursor:pointer;">Yes, Delete</button>
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

function toDashboard() { window.location.href = "dashboard.php"; }
function logout(){ window.location.href = "login.php"; }

// Modal Logic
function openAddModal() {
  document.getElementById("addModal").style.display = "flex";
}

function openEditModal(id, username) {
  document.getElementById("editUserId").value = id;
  document.getElementById("editUsername").value = username;
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

// Add these to your existing script block in account.php
function validateAddPassword() {
  const pass = document.getElementById("addPassword").value;
  const confirm = document.getElementById("addConfirmPassword").value;
  if (pass !== confirm) {
    alert("Passwords do not match. Please try again.");
    return false; // Stops the form from submitting
  }
  return true;
}

function validateEditPassword() {
  const pass = document.getElementById("editPassword").value;
  const confirm = document.getElementById("editConfirmPassword").value;
  if (pass !== confirm) {
    alert("Passwords do not match. Please try again.");
    return false; // Stops the form from submitting
  }
  return true;
}
</script>
</body>
</html>