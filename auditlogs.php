<?php
require_once("func/getAuditLogs.php"); 

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
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Audit Logs</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/auditlogs.css">
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
        <img src="assets/overviewicon.png" width="20">
        Overview
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item" href="#" onclick="toggleMenu(event,'mgmt-sub')">
        <img src="assets/users.png" width="20">
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
        <img src="assets/reporticon.png" width="20">
        Reports
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item active open" href="#" onclick="toggleMenu(event,'system-sub')">
        <img src="assets/settingicon.png" width="20">
        System
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub open" id="system-sub">
        <a class="nav-sub-item" href="system.php">System Tools</a>
        <a class="nav-sub-item" href="account.php">Accounts</a>
        <a class="nav-sub-item" href="archive.php">Archive</a>
        <a class="nav-sub-item active" href="auditlogs.php">Audit Logs</a>
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
  <div class="content-card">

    <div class="card-header">
      <div class="page-title">
        <h1>Audit Logs</h1>
      </div>

      <form method="GET" action="auditlogs.php" class="filter-form">
        <div class="search-bar">
          <input type="text" name="search" placeholder="Search logs..." value="<?= htmlspecialchars($search) ?>">
        </div>

        <select name="action" onchange="this.form.submit()">
          <option value="">All Actions</option>
          <?php foreach ([
              "LOGIN",
              "CREATE",
              "UPDATE",
              "APPROVE",
              "REJECT",
              "ARCHIVE",
              "RESTORE",
              "DELETE"
          ] as $a): ?>
            <option value="<?= $a ?>" <?= $action_filter === $a ? "selected" : "" ?>><?= $a ?></option>
          <?php endforeach; ?>
        </select>

        <button type="submit" class="btn-filter">Search</button>

        <?php if ($search !== "" || $action_filter !== ""): ?>
          <a href="auditlogs.php" class="btn-clear">Clear</a>
        <?php endif; ?>
      </form>
    </div>

    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>Date & Time</th>
            <th>Name</th>
            <th>Role</th>
            <th>Action</th>
            <th>Module</th>
            <th>Resident ID</th>
            <th>Description</th>
          </tr>
        </thead>

        <tbody>
          <?php if (mysqli_num_rows($audit_result) === 0): ?>
            <tr>
              <td colspan="7" class="empty">No audit logs found.</td>
            </tr>
          <?php else: ?>
            <?php while ($log = mysqli_fetch_assoc($audit_result)): ?>
              <tr>
                <td><?= htmlspecialchars(date("M d, Y h:i A", strtotime($log["created_at"]))) ?></td>
                <td class="fw-bold"><?= htmlspecialchars($log["admin_name"] ?? "Unknown") ?></td>
                <td><?= htmlspecialchars(ucfirst($log["role"] ?? "N/A")) ?></td>
                <td>
                  <span class="badge <?= actionBadge($log["action"]) ?>">
                    <?= htmlspecialchars($log["action"]) ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($log["module"]) ?></td>
                <td><?= htmlspecialchars($log["resident_id"] ?? "—") ?></td>
                <td><?= htmlspecialchars($log["description"]) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="pagination">
      <span class="pagination-info">
        Page <?= $current_page ?> of <?= $total_pages ?> · <?= $total_rows ?> log<?= $total_rows != 1 ? "s" : "" ?>
      </span>

      <div class="pagination-btns">
        <a href="<?= buildQuery($current_page - 1) ?>" class="page-btn <?= $current_page <= 1 ? 'disabled' : '' ?>">‹</a>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <a href="<?= buildQuery($i) ?>" class="page-btn <?= $i === $current_page ? 'active' : '' ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>

        <a href="<?= buildQuery($current_page + 1) ?>" class="page-btn <?= $current_page >= $total_pages ? 'disabled' : '' ?>">›</a>
      </div>
    </div>

  </div>
</main>

<script>
function toggleMenu(event, id) {
  event.preventDefault();
  event.currentTarget.classList.toggle("open");
  document.getElementById(id).classList.toggle("open");
}

function logout() {
  window.location.href = "func/logout.php";
}
</script>

</body>
</html>