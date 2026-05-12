<?php require_once("func/getDashboardData.php");
require_once("func/processDailyBackup.php");
require_once("func/db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PWD/CWD Hub – Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/dashboard.css" />
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
      <a class="nav-item active" href="dashboard.php">
        <img src="assets/overviewicon.png" width="20">
        Overview
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item open" href="#" onclick="toggleMenu(event,'mgmt-sub')">
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
      <a class="nav-item open" href="#" onclick="toggleMenu(event,'system-sub')">
        <img src="assets/settingicon.png" width="20">
        System
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub" id="system-sub">
        <a class="nav-sub-item" href="system.php">System Tools</a>
        <a class="nav-sub-item" href="account.php">Accounts</a>
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

<!-- ── MAIN ── -->
<div class="main">
  <h1 class="page-title">Dashboard Overview</h1>
  <p class="page-sub">Welcome to the Brgy. Sto. Niño PWD/CWD database!</p>

  <div class="chart-row">
    <div class="chart-comb">

      <!-- Stat card -->
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon">
            <img src="assets/users2icon.png" width="30">
          </div>
          <div>
            <div class="stat-label">Total Citizens Registered</div>
            <div>
              <span class="stat-number"><?php echo $total; ?></span>
              <span class="stat-unit">citizens</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Age Demographics Bar Chart -->
      <div class="chart-card">
        <div class="chart-title">Age Demographics</div>
        <div class="bar-chart">
          <div class="bar-row">
            <div class="bar-track">
              <div class="bar-fill" style="width:<?php echo $minors_pct; ?>%; background:#C97B78;"></div>
            </div>
          </div>
          <div class="bar-row">
            <div class="bar-track">
              <div class="bar-fill" style="width:<?php echo $adults_pct; ?>%; background:#A84040;"></div>
            </div>
          </div>
          <div class="bar-row">
            <div class="bar-track">
              <div class="bar-fill" style="width:<?php echo $seniors_pct; ?>%; background:#4A1010;"></div>
            </div>
          </div>
        </div>
        <div class="bar-legend">
          <div class="legend-item"><div class="legend-dot" style="background:#C97B78;"></div> Minors (0–17)</div>
          <div class="legend-item"><div class="legend-dot" style="background:#A84040;"></div> Adults (18–59)</div>
          <div class="legend-item"><div class="legend-dot" style="background:#4A1010;"></div> Seniors (60+)</div>
        </div>
      </div>

    </div>

    <!-- Registration Status Pie Chart -->
    <div class="chart-card">
      <div class="chart-title">Registration Status</div>
      <div class="pie-wrap">
        <svg width="320" height="320" viewBox="0 0 180 180">
          <?php if ($total > 0): ?>
            <?php if ($active_count == $total): ?>
              <circle cx="90" cy="90" r="80" fill="#D4736F"/>
            <?php elseif ($pending_count == $total): ?>
              <circle cx="90" cy="90" r="80" fill="#A84040"/>
            <?php elseif ($expired_count == $total): ?>
              <circle cx="90" cy="90" r="80" fill="#5C1010"/>
            <?php else: ?>
              <?php if ($active_count  > 0): ?><path class="pie-slice" d="<?php echo $active_path;  ?>" fill="#D4736F"/><?php endif; ?>
              <?php if ($pending_count > 0): ?><path class="pie-slice" d="<?php echo $pending_path; ?>" fill="#A84040"/><?php endif; ?>
              <?php if ($expired_count > 0): ?><path class="pie-slice" d="<?php echo $expired_path; ?>" fill="#5C1010"/><?php endif; ?>
            <?php endif; ?>
          <?php else: ?>
            <circle cx="90" cy="90" r="80" fill="#eee"/>
          <?php endif; ?>
        </svg>
        <div class="pie-legend">
          <div class="pie-legend-item"><div class="pie-dot" style="background:#D4736F;"></div> Active</div>
          <div class="pie-legend-item"><div class="pie-dot" style="background:#A84040;"></div> Pending</div>
          <div class="pie-legend-item"><div class="pie-dot" style="background:#5C1010;"></div> Expired</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Directory Table -->
  <div class="dir-card">
    <div class="dir-header">
      <div class="dir-title">Directory</div>
      <div class="search-wrap">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="searchInput" placeholder="Search name" oninput="filterTable()">
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Disability Type</th>
          <th>Category</th>
          <th>Sex</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="residentTable">
        <?php 
        $display_count = 0;
        while ($user = mysqli_fetch_assoc($residents_result)): 
          if ($display_count == 5) break;
          $display_count++;
          
          $full_name = htmlspecialchars(
            $user['last_name'] . ", " . $user['first_name'] . " " . $user['middle_name']
          );
          $disability = htmlspecialchars($user['disablity_type'] ?? '—');
          $badge      = badgeClass($user['disablity_type'] ?? '');
          $category   = htmlspecialchars($user['resident_type'] ?? '—');
          $sex        = htmlspecialchars(strtoupper($user['sex'] ?? '—'));
          $status     = htmlspecialchars($user['status'] ?? 'Pending');
          $status_cls = "status-" . strtolower($status);
        ?>
          <tr>
            <td class="td-name"><?php echo $full_name; ?></td>
            <td>
              <?php
                $types = explode(",", $disability);
                foreach ($types as $t):
                  $t = trim($t);
                  if ($t):
              ?>
                <span class="badge <?php echo badgeClass($t); ?>"><?php echo htmlspecialchars($t); ?></span>
              <?php endif; endforeach; ?>
            </td>
            <td><?php echo $category; ?></td>
            <td><?php echo $sex; ?></td>
            <td><span class="status <?php echo $status_cls; ?>"><?php echo $status; ?></span></td>
            <td>
              <div class="actions">
                <button class="action-btn" title="Edit" onclick="window.location.href='editResident.php?id=<?= $user['ID'] ?>'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button class="action-btn" title="View" onclick="window.location.href='viewprofile.php?id=<?= $user['ID'] ?>'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function toggleMenu(event, id) {
  event.preventDefault();
  event.currentTarget.classList.toggle("open");
  document.getElementById(id).classList.toggle("open");
}
function logout() {
  window.location.href = "login.php";
}
function filterTable() {
  const search = document.getElementById("searchInput").value.toLowerCase();
  document.querySelectorAll("#residentTable tr").forEach(row => {
    const name = row.querySelector(".td-name")?.textContent.toLowerCase() ?? "";
    row.style.display = name.includes(search) ? "" : "none";
  });
}
</script>
</body>
</html>