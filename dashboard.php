<?php
require_once("func/auth.php");
require_once("func/db.php");
require_once("func/getDashboardData.php");
require_once("func/processDailyBackup.php");
require_once("func/getNotifications.php");

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

<!-- ── MAIN ── -->
<div class="main">
  <div class="top-actions">
  <div class="notification-wrap">
    <button class="notification-btn" onclick="toggleNotifications(event)">
      🔔

      <?php if ($notification_count > 0): ?>
        <span class="notification-badge">
          <?= $notification_count ?>
        </span>
      <?php endif; ?>
    </button>

    <div class="notification-panel" id="notificationPanel">

      <div class="notification-header">
        <strong>Notifications</strong>
        <span><?= $notification_count ?> alert(s)</span>
      </div>

      <?php if ($expired_ids_count > 0): ?>
        <div class="notification-item danger">
          <strong><?= $expired_ids_count ?> expired ID(s)</strong>
          <p>Some resident IDs are already expired.</p>
          <a href="resident.php">View Residents</a>
        </div>
      <?php endif; ?>

      <?php if ($review_count > 0): ?>
        <div class="notification-item info">
          <strong><?= $review_count ?> new registration(s)</strong>
          <p>Pending submissions need review.</p>
          <a href="review.php">Open Review Page</a>
        </div>
      <?php endif; ?>

      <?php if ($missing_medcert_count > 0): ?>
        <div class="notification-item warning">
          <strong><?= $missing_medcert_count ?> missing medical certificate(s)</strong>
          <p>Some residents have no uploaded medical certificate.</p>
          <a href="resident.php">Check Residents</a>
        </div>
      <?php endif; ?>

      <?php if ($expiring_soon_count > 0): ?>
        <div class="notification-item warning">
          <strong><?= $expiring_soon_count ?> ID(s) expiring soon</strong>
          <p>Some resident IDs will expire within 1 month.</p>
          <a href="resident.php">View Residents</a>
        </div>
      <?php endif; ?>

      <?php if ($backupReminder): ?>
        <div class="notification-item backup">
          <strong>Backup Reminder</strong>
          <p>No recent backup detected in the last 24 hours.</p>
          <a href="system.php">Open System Tools</a>
        </div>
      <?php endif; ?>

      <?php if ($notification_count == 0): ?>
        <div class="notification-empty">
          No notifications available.
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

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
          <div class="legend-item">
            <div class="legend-dot" style="background:#C97B78;"></div>
            Minors (0–17)
            <span class="legend-stat">(<?php echo $minors_count; ?> | <?php echo $total > 0 ? round(($minors_count / $total) * 100) : 0; ?>%)</span>
          </div>
          <div class="legend-item">
            <div class="legend-dot" style="background:#A84040;"></div>
            Adults (18–59)
            <span class="legend-stat">(<?php echo $adults_count; ?> | <?php echo $total > 0 ? round(($adults_count / $total) * 100) : 0; ?>%)</span>
          </div>
          <div class="legend-item">
            <div class="legend-dot" style="background:#4A1010;"></div>
            Seniors (60+)
            <span class="legend-stat">(<?php echo $seniors_count; ?> | <?php echo $total > 0 ? round(($seniors_count / $total) * 100) : 0; ?>%)</span>
          </div>
        </div>
      </div>

    </div>

    <!-- Registration Status Pie Chart -->
<div class="chart-card">
  <div class="chart-title">Registration Status</div>

  <div class="pie-wrap">

    <svg width="320" height="320" viewBox="0 0 180 180">

      <?php if ($status_total > 0): ?>

  <?php if ($active_count == $status_total): ?>
    <circle cx="90" cy="90" r="80" fill="#A84040"/>

  <?php elseif ($under_review_count == $status_total): ?>
    <circle cx="90" cy="90" r="80" fill="#D4736F"/>

  <?php elseif ($needs_correction_count == $status_total): ?>
    <circle cx="90" cy="90" r="80" fill="#F2B8A0"/>

  <?php elseif ($expired_count == $status_total): ?>
    <circle cx="90" cy="90" r="80" fill="#5C1010"/>

  <?php else: ?>

    <?php if ($active_count > 0): ?>
      <path class="pie-slice" d="<?= $active_path ?>" fill="#A84040"/>
    <?php endif; ?>

    <?php if ($under_review_count > 0): ?>
      <path class="pie-slice" d="<?= $under_review_path ?>" fill="#D4736F"/>
    <?php endif; ?>

    <?php if ($needs_correction_count > 0): ?>
      <path class="pie-slice" d="<?= $needs_correction_path ?>" fill="#F2B8A0"/>
    <?php endif; ?>

    <?php if ($expired_count > 0): ?>
      <path class="pie-slice" d="<?= $expired_path ?>" fill="#5C1010"/>
    <?php endif; ?>

  <?php endif; ?>

<?php else: ?>
  <circle cx="90" cy="90" r="80" fill="#eee"/>
<?php endif; ?>

    </svg>

    <div class="pie-legend">

      <div class="pie-legend-item">
        <div class="pie-dot" style="background:#A84040;"></div>
        Active
        <span class="legend-stat">
          (<?= $active_count ?> | <?= $status_total > 0 ? round(($active_count / $status_total) * 100) : 0 ?>%)
        </span>
      </div>

      <div class="pie-legend-item">
        <div class="pie-dot" style="background:#D4736F;"></div>
        Under Review
        <span class="legend-stat">
          (<?= $under_review_count ?> | <?= $status_total > 0 ? round(($under_review_count / $status_total) * 100) : 0 ?>%)
        </span>
      </div>

      <div class="pie-legend-item">
        <div class="pie-dot" style="background:#F2B8A0;"></div>
        Needs Correction
        <span class="legend-stat">
          (<?= $needs_correction_count ?> | <?= $status_total > 0 ? round(($needs_correction_count / $status_total) * 100) : 0 ?>%)
        </span>
      </div>

      <div class="pie-legend-item">
        <div class="pie-dot" style="background:#5C1010;"></div>
        Expired
        <span class="legend-stat">
          (<?= $expired_count ?> | <?= $status_total > 0 ? round(($expired_count / $status_total) * 100) : 0 ?>%)
        </span>
      </div>

    </div>
  </div>
</div>
  </div>

  <!-- Directory Table -->
  <div class="dir-card">
    <div class="dir-header">
      <div class="dir-title">
        Directory
        <?php if (!empty($dash_filter_disab) || !empty($dash_filter_cat) || !empty($dash_filter_sex) || !empty($dash_filter_status)): ?>
          <a href="dashboard.php" style="font-size:11px;font-weight:700;color:var(--primary);text-decoration:none;margin-left:10px;">✕ Clear filters</a>
        <?php endif; ?>
      </div>
      <form id="searchForm" onsubmit="event.preventDefault();" style="margin: 0;">
        <div class="search-wrap">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" id="liveSearchInput" name="search" placeholder="Search entire directory" value="<?= htmlspecialchars($search ?? '') ?>" autocomplete="off">
        </div>
      </form>
    </div>

    <!-- Hidden filter form (driven by th clicks) -->
    <form method="GET" action="dashboard.php" id="dashFilterForm">
      <select name="disability" id="dash-sel-disability" style="display:none" onchange="document.getElementById('dashFilterForm').submit()">
        <option value="">All Disability Types</option>
        <option value="Cognitive"    <?= $dash_filter_disab === "Cognitive"    ? "selected" : "" ?>>Cognitive</option>
        <option value="Visual"       <?= $dash_filter_disab === "Visual"       ? "selected" : "" ?>>Visual</option>
        <option value="Physical"     <?= $dash_filter_disab === "Physical"     ? "selected" : "" ?>>Physical</option>
        <option value="Auditory"     <?= $dash_filter_disab === "Auditory"     ? "selected" : "" ?>>Auditory</option>
        <option value="Speech"       <?= $dash_filter_disab === "Speech"       ? "selected" : "" ?>>Speech</option>
        <option value="Psychosocial" <?= $dash_filter_disab === "Psychosocial" ? "selected" : "" ?>>Psychosocial</option>
        <option value="Others"       <?= $dash_filter_disab === "Others"       ? "selected" : "" ?>>Others</option>
      </select>
      <select name="category" id="dash-sel-category" style="display:none" onchange="document.getElementById('dashFilterForm').submit()">
        <option value="">All Categories</option>
        <option value="PWD" <?= $dash_filter_cat === "PWD" ? "selected" : "" ?>>PWD</option>
        <option value="CWD" <?= $dash_filter_cat === "CWD" ? "selected" : "" ?>>CWD</option>
      </select>
      <select name="sex" id="dash-sel-sex" style="display:none" onchange="document.getElementById('dashFilterForm').submit()">
        <option value="">All Sexes</option>
        <option value="male"   <?= $dash_filter_sex === "male"   ? "selected" : "" ?>>Male</option>
        <option value="female" <?= $dash_filter_sex === "female" ? "selected" : "" ?>>Female</option>
      </select>
      <select name="status" id="dash-sel-status" style="display:none" onchange="document.getElementById('dashFilterForm').submit()">
        <option value="">All Statuses</option>
        <option value="Active"           <?= $dash_filter_status === "Active"           ? "selected" : "" ?>>Active</option>
        <option value="Under Review"     <?= $dash_filter_status === "Under Review"     ? "selected" : "" ?>>Under Review</option>
        <option value="Needs Correction" <?= $dash_filter_status === "Needs Correction" ? "selected" : "" ?>>Needs Correction</option>
        <option value="Expired"          <?= $dash_filter_status === "Expired"          ? "selected" : "" ?>>Expired</option>
      </select>
    </form>

    <table>
      <colgroup>
        <col style="width:30%">
        <col style="width:20%">
        <col style="width:12%">
        <col style="width:10%">
        <col style="width:18%">
        <col style="width:10%">
      </colgroup>
      <thead>
        <tr>
          <th>Full Name</th>
          <th class="th-filter <?= !empty($dash_filter_disab) ? 'th-active' : '' ?>" onclick="dashToggleFilter('drop-disability', event)">
            Disability Type
            <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            <div class="th-dropdown" id="drop-disability">
              <?php foreach ([''=>'All Disability Types','Cognitive'=>'Cognitive','Visual'=>'Visual','Physical'=>'Physical','Auditory'=>'Auditory','Speech'=>'Speech','Psychosocial'=>'Psychosocial','Others'=>'Others'] as $val => $label): ?>
                <div class="th-drop-item <?= $dash_filter_disab === $val ? 'selected' : '' ?>" onclick="dashSetFilter('disability','<?= $val ?>', event)"><?= $label ?></div>
              <?php endforeach; ?>
            </div>
          </th>
          <th class="th-filter <?= !empty($dash_filter_cat) ? 'th-active' : '' ?>" onclick="dashToggleFilter('drop-category', event)">
            Category
            <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            <div class="th-dropdown" id="drop-category">
              <?php foreach ([''=>'All Categories','PWD'=>'PWD','CWD'=>'CWD'] as $val => $label): ?>
                <div class="th-drop-item <?= $dash_filter_cat === $val ? 'selected' : '' ?>" onclick="dashSetFilter('category','<?= $val ?>', event)"><?= $label ?></div>
              <?php endforeach; ?>
            </div>
          </th>
          <th class="th-filter <?= !empty($dash_filter_sex) ? 'th-active' : '' ?>" onclick="dashToggleFilter('drop-sex', event)">
            Sex
            <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            <div class="th-dropdown" id="drop-sex">
              <?php foreach ([''=>'All Sexes','male'=>'Male','female'=>'Female'] as $val => $label): ?>
                <div class="th-drop-item <?= $dash_filter_sex === $val ? 'selected' : '' ?>" onclick="dashSetFilter('sex','<?= $val ?>', event)"><?= $label ?></div>
              <?php endforeach; ?>
            </div>
          </th>
          <th class="th-filter <?= !empty($dash_filter_status) ? 'th-active' : '' ?>" onclick="dashToggleFilter('drop-status', event)">
            Status
            <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            <div class="th-dropdown" id="drop-status">
              <?php foreach ([''=>'All Statuses','Active'=>'Active','Under Review'=>'Under Review','Needs Correction'=>'Needs Correction','Expired'=>'Expired'] as $val => $label): ?>
                <div class="th-drop-item <?= $dash_filter_status === $val ? 'selected' : '' ?>" onclick="dashSetFilter('status','<?= addslashes($val) ?>', event)"><?= $label ?></div>
              <?php endforeach; ?>
            </div>
          </th>
          <th></th>
        </tr>
      </thead>
      <tbody id="residentTable">
        <?php 
        $display_count = 0;
        // Show up to 20 results if searching, otherwise show 5 recent
        $limit = !empty($search) ? 5 : 5; 

        if (mysqli_num_rows($residents_result) === 0): ?>
          <tr>
            <td colspan="6" style="text-align:center; padding:30px; color:#888;">No residents found matching your search.</td>
          </tr>
        <?php else:
          while ($user = mysqli_fetch_assoc($residents_result)): 
            if ($display_count == $limit) break;
            $display_count++;
            
            $full_name = htmlspecialchars(
              $user['last_name'] . ", " . $user['first_name'] . " " . $user['middle_name']
            );
            $disability = htmlspecialchars($user['disability_type'] ?? '—');
            $badge      = badgeClass($user['disability_type'] ?? '');
            $category   = htmlspecialchars($user['resident_type'] ?? '—');
            $sex        = htmlspecialchars(strtoupper($user['sex'] ?? '—'));

if (($user['record_status'] ?? '') === 'expired') {
    $status = 'Expired';
} elseif (($user['application_status'] ?? '') === 'under review') {
    $status = 'Under Review';
} elseif (($user['application_status'] ?? '') === 'needs correction') {
    $status = 'Needs Correction';
} elseif (($user['application_status'] ?? '') === 'rejected') {
    $status = 'Rejected';
} else {
    $status = 'Active';
}

$status = htmlspecialchars($status);
$status_cls = "status-" . str_replace(' ', '-', strtolower($status));
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
        <?php endif; ?>
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
  window.location.href = "func/logout.php";
}

// ── LIVE SEARCH LOGIC ──
const searchInput = document.getElementById("liveSearchInput");
let searchTimer;

searchInput.addEventListener("input", function() {
  clearTimeout(searchTimer);
  
  searchTimer = setTimeout(() => {
    const url = "dashboard.php?search=" + encodeURIComponent(searchInput.value);

    fetch(url)
      .then(response => response.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, "text/html");
        document.getElementById("residentTable").innerHTML = doc.getElementById("residentTable").innerHTML;
      })
      .catch(err => console.error("Search fetch error:", err));
  }, 300);
});

// ── COLUMN FILTER LOGIC ──
let dashActiveDropdown = null;

function dashCloseAll() {
  document.querySelectorAll('.th-dropdown.open').forEach(d => d.classList.remove('open'));
  dashActiveDropdown = null;
}

function dashToggleFilter(dropId, event) {
  event.stopPropagation();
  const drop = document.getElementById(dropId);
  const isOpen = drop.classList.contains('open');
  dashCloseAll();
  if (!isOpen) {
    drop.classList.add('open');
    dashActiveDropdown = dropId;
  }
}

function dashSetFilter(param, value, event) {
  event.stopPropagation();
  document.getElementById('dash-sel-' + param).value = value;
  dashCloseAll();
  document.getElementById('dashFilterForm').submit();
}

document.addEventListener('click', () => dashCloseAll());

// ── NOTIFICATIONS LOGIC ──
function toggleNotifications(event) {
  event.stopPropagation();
  document.getElementById("notificationPanel").classList.toggle("show");
}

window.addEventListener("click", function () {
  const panel = document.getElementById("notificationPanel");
  if (panel && panel.classList.contains("show")) {
    panel.classList.remove("show");
  }
});
</script>
</body>
</html>