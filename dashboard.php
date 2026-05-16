<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Embedded Tracker: Updates session when user clicks the "View" eye icon in the dashboard
if (isset($_GET['track_view_id'])) {
    $id = intval($_GET['track_view_id']);
    if (!isset($_SESSION['recent_views'])) { $_SESSION['recent_views'] = []; }
    $pos = array_search($id, $_SESSION['recent_views']);
    if ($pos !== false) { unset($_SESSION['recent_views'][$pos]); }
    array_unshift($_SESSION['recent_views'], $id);
    $_SESSION['recent_views'] = array_slice($_SESSION['recent_views'], 0, 5);
    exit;
}

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
<style>
#viewModalOverlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center; padding:20px; font-family:'DM Sans', sans-serif; }
#viewModalContainer { background:#f5f4f2; border-radius:20px; width:100%; max-width:1100px; max-height:90vh; display:flex; flex-direction:column; position:relative; box-shadow:0 10px 40px rgba(0,0,0,0.3); }
#viewModalBody { padding:24px; overflow-y:auto; flex:1; }
.m-profile-header { background:#fff; border-radius:20px; position:relative; box-shadow:0 4px 15px rgba(0,0,0,0.02); margin-bottom:15px; }
.m-banner { height:48px; background:linear-gradient(90deg, #D6A886 0%, #D86B69 100%); border-radius:20px 20px 0 0; width:100%; }
.m-profile-content { padding:0 32px 32px 32px; display:flex; justify-content:space-between; }
.m-profile-left { display:flex; gap:20px; }
.m-avatar { width:100px; height:100px; border-radius:16px; border:4px solid #FFF; object-fit:cover; margin-top:-24px; background:#333; box-shadow:0 4px 10px rgba(0,0,0,0.08); }
.m-profile-info { margin-top:16px; }
.m-profile-name-row { display:flex; align-items:center; gap:12px; margin-bottom:4px; }
.m-profile-name { font-size:30px; font-weight:800; letter-spacing:-0.5px; color:#1A1A1A; margin:0; }
.m-profile-id { font-size:15px; color:#6B7280; font-weight:500; }
.m-classification-box { margin-top:-16px; background:#FFF; border-radius:30px; padding:20px 38px; box-shadow:0 4px 15px rgba(0,0,0,0.06); display:flex; flex-direction:column; align-items:flex-end; gap:8px; position:relative; z-index:2; }
.m-classification-title { font-size:22px; font-weight:700; color:#1A1A1A; }
.m-details-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:24px; }
.m-card { background:#fff; border-radius:20px; padding:32px; box-shadow:0 4px 15px rgba(0,0,0,0.02); display:flex; flex-direction:column; }
.m-card-contact { grid-column:1 / -1; }
.m-card-header { display:flex; align-items:center; gap:12px; margin-bottom:24px; }
.m-card-header h3 { font-size:18px; font-weight:700; color:#1A1A1A; margin:0; }
.m-icon-box { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; }
.m-icon-box svg { width:16px; height:16px; }
.m-bg-orange { background:#FFF7ED; color:#EA580C; }
.m-bg-blue { background:#EFF6FF; color:#3B82F6; }
.m-bg-red { background:#FEF2F2; color:#EF4444; }
.m-bg-purple { background:#F5F3FF; color:#9333EA; }
.m-info-list { display:flex; flex-direction:column; gap:20px; }
.m-contact-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:24px; }
.m-info-group { display:flex; flex-direction:column; gap:4px; }
.m-info-label { font-size:13px; font-weight:600; color:#6B7280; }
.m-info-value { font-size:14px; font-weight:600; color:#1A1A1A; }
.m-badge { display:inline-flex; align-items:center; justify-content:center; padding:5px 16px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; border:1.5px solid transparent; }
.m-badge-active { background:#DFF5E3; color:#32C24D; border-color:#32C24D; }
.m-badge-under-review { background:#FFF1E8; color:#E57A39; border-color:#F0BE9B; }
.m-badge-needs-correction { background:#FFF4E8; color:#D17A2B; border-color:#E7BC8D; }
.m-badge-expired { background:#FFDCDC; color:#E02424; border-color:#FF4D4D; }
.m-badge-rejected { background:#F9E2E2; color:#B42323; border-color:#D96B6B; }
.m-badge-visual { background:#E6EAFB; color:#155DA4; border:1px solid #C4D2F2; }
.m-badge-auditory { background:#FFF8D7; color:#C5AD11; border:1px solid #F0DF87; }
.m-badge-cognitive { background:#F7E6FB; color:#7E15A4; border:1px solid #E9D5FF; }
.m-badge-physical { background:#FFDCDC; color:#A41515; border:1px solid #F2C4C4; }
.m-badge-speech { background:#FFE9D7; color:#D13E0D; border:1px solid #F2D1C4; }
.m-badge-psycho { background:#E6FBE6; color:#15A44E; border:1px solid #C4F2D2; }
.m-badge-others { background:#F0F0F0; color:#666; border:1px solid #DDD; }

@media (max-width: 992px) {
  .m-profile-content { flex-direction: column; align-items: center; text-align: center; padding: 0 20px 20px 20px; }
  .m-profile-left { flex-direction: column; align-items: center; gap: 10px; }
  .m-profile-name-row { flex-direction: column; gap: 6px; }
  .m-classification-box { margin-top: 15px; align-items: center; padding: 15px; width: 100%; }
  #m-dis-badges { justify-content: center !important; }
  .m-details-grid { grid-template-columns: 1fr; gap: 15px; }
  .m-contact-grid { grid-template-columns: 1fr; gap: 15px; }
  .m-card { padding: 20px; }
}
</style>
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
        <option value="">All Genders</option>
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
            Gender
            <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            <div class="th-dropdown" id="drop-sex">
              <?php foreach ([''=>'All Genders','male'=>'Male','female'=>'Female'] as $val => $label): ?>
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
        $limit = 5; 

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
            $category   = htmlspecialchars($user['resident_type'] ?? '—');
            $sex        = htmlspecialchars(ucfirst($user['sex'] ?? '—'));

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
            $json_data = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
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
                  <span class="badge <?php echo badgeClass($t); ?>"><?= htmlspecialchars($t); ?></span>
                <?php endif; endforeach; ?>
              </td>
              <td><?php echo $category; ?></td>
              <td><?php echo $sex; ?></td>
              <td><span class="status <?php echo $status_cls; ?>"><?= $status ?></span></td>
              <td>
                <div class="actions">
                    <button class="action-btn" title="View" onclick="openViewModal(this)" data-info="<?= $json_data ?>">
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

<div id="viewModalOverlay">
  <div id="viewModalContainer">
    <div style="padding:15px 24px; background:#fff; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; border-radius: 20px 20px 0 0;">
      <h2 style="margin:0; font-size:18px; font-weight:700; color:#1A1A1A;">Resident Profile</h2>
      <div style="display:flex; gap:10px;">
        <?php if ($isAdmin): ?>
        <button id="m-archive-btn" data-id="" onclick="confirmArchive(this.dataset.id)" style="border:none; cursor:pointer; padding:8px 16px; background:#FEF2F2; color:#EF4444; border-radius:8px; font-weight:600; font-size:14px; display:flex; align-items:center; gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="12" x2="14" y2="12"/></svg> Archive
        </button>
        <?php endif; ?>
        <a id="m-edit-btn" href="#" style="text-decoration:none; padding:8px 16px; background:#EFF6FF; color:#3B82F6; border-radius:8px; font-weight:600; font-size:14px; display:flex; align-items:center; gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit
        </a>
        <button onclick="document.getElementById('viewModalOverlay').style.display='none'" style="background:none; border:none; cursor:pointer; padding:4px;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
    </div>

    <div id="viewModalBody">
      <div class="m-profile-header">
        <div class="m-banner"></div>
        <div class="m-profile-content">
          <div class="m-profile-left">
            <img id="m-avatar" src="" alt="Profile" class="m-avatar">
            <div class="m-profile-info">
              <div class="m-profile-name-row">
                <h1 class="m-profile-name" id="m-name"></h1>
                <span class="m-badge" id="m-status"></span>
              </div>
              <div class="m-profile-id">ID #: <span id="m-pwdid-top"></span></div>
            </div>
          </div>
          <div class="m-classification-box">
            <div class="m-classification-title">Disability Classification</div>
            <div id="m-dis-badges" style="display:flex; gap:6px; flex-wrap:wrap; justify-content:flex-end;"></div>
          </div>
        </div>
      </div>

      <div class="m-details-grid">
        <div class="m-card">
          <div class="m-card-header">
            <div class="m-icon-box m-bg-orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            <h3>Personal</h3>
          </div>
          <div class="m-info-list">
            <div class="m-info-group"><span class="m-info-label">Date of Birth</span><span class="m-info-value" id="m-dob"></span></div>
            <div class="m-info-group"><span class="m-info-label">Gender</span><span class="m-info-value" id="m-gender"></span></div>
            <div class="m-info-group"><span class="m-info-label">Civil Status</span><span class="m-info-value" id="m-civil"></span></div>
          </div>
        </div>

        <div class="m-card">
          <div class="m-card-header">
            <div class="m-icon-box m-bg-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg></div>
            <h3>Disability</h3>
          </div>
          <div class="m-info-list">
            <div class="m-info-group"><span class="m-info-label">Type of Disability/ies</span><span class="m-info-value" id="m-dis-type"></span></div>
            <div class="m-info-group"><span class="m-info-label">Cause of Disability</span><span class="m-info-value" id="m-dis-cause"></span></div>
            <div class="m-info-group"><span class="m-info-label">Medical Certificate</span><span class="m-info-value" id="m-medcert"></span></div>
          </div>
        </div>

        <div class="m-card">
          <div class="m-card-header">
            <div class="m-icon-box m-bg-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
            <h3>ID Information</h3>
          </div>
          <div class="m-info-list">
            <div class="m-info-group"><span class="m-info-label">PWD ID Number</span><span class="m-info-value" id="m-pwdid"></span></div>
            <div class="m-info-group"><span class="m-info-label">Control Number</span><span class="m-info-value" id="m-control"></span></div>
            <div class="m-info-group"><span class="m-info-label">Date Issued</span><span class="m-info-value" id="m-issued"></span></div>
            <div class="m-info-group"><span class="m-info-label">Expiration Date</span><span class="m-info-value" id="m-expired"></span></div>
          </div>
        </div>

        <div class="m-card m-card-contact">
          <div class="m-card-header">
            <div class="m-icon-box m-bg-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <h3>Contact and Address</h3>
          </div>
          <div class="m-contact-grid">
            <div class="m-info-list">
              <div class="m-info-group"><span class="m-info-label">Mobile Number</span><span class="m-info-value" id="m-mobile"></span></div>
              <div class="m-info-group"><span class="m-info-label">House No. / Street</span><span class="m-info-value" id="m-address"></span></div>
              <div class="m-info-group"><span class="m-info-label">Email/Facebook Account</span><span class="m-info-value" id="m-socials"></span></div>
            </div>
            <div class="m-info-list">
              <div class="m-info-group"><span class="m-info-label">Emergency Contact Name</span><span class="m-info-value" id="m-em-name"></span></div>
              <div class="m-info-group"><span class="m-info-label">Emergency Contact Number</span><span class="m-info-value" id="m-em-num"></span></div>
              <div class="m-info-group"><span class="m-info-label">Relationship</span><span class="m-info-value" id="m-em-rel"></span></div>
            </div>
            <div class="m-info-list">
              <div class="m-info-group"><span class="m-info-label">Father Name</span><span class="m-info-value" id="m-father"></span></div>
              <div class="m-info-group"><span class="m-info-label">Mother Name</span><span class="m-info-value" id="m-mother"></span></div>
              <div class="m-info-group"><span class="m-info-label">Spouse Name</span><span class="m-info-value" id="m-spouse"></span></div>
              <div class="m-info-group"><span class="m-info-label">Guardian</span><span class="m-info-value" id="m-guardian"></span></div>
              <div class="m-info-group"><span class="m-info-label">Guardian Relationship</span><span class="m-info-value" id="m-guardian-rel"></span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="archiveModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:10000; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
    <div style="width:48px; height:48px; background:#FFF3E8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F18831" stroke-width="2.5"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; margin-bottom:10px; color:#1c0202; text-align:center;">Archive this resident?</h2>
    <p style="font-size:13.5px; color:rgba(28,2,2,0.6); margin-bottom:24px; text-align:center;">This will move the resident to the archive. You can restore them from the Archive page.</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="document.getElementById('archiveModal').style.display='none'" style="padding:8px 18px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:none; font-family:inherit; font-weight:700; cursor:pointer;">Cancel</button>
      <form action="func/processArchive.php" method="POST" style="margin:0;">
        <input type="hidden" name="resident_id" id="archiveId">
        <button type="submit" style="padding:8px 18px; border-radius:8px; border:none; background:#A84040; color:#fff; font-family:inherit; font-weight:700; cursor:pointer;">Yes, Archive</button>
      </form>
    </div>
  </div>
</div>

<script>
function toggleMenu(event, id) {
  event.preventDefault();
  event.currentTarget.classList.toggle("open");
  document.getElementById(id).classList.toggle("open");
}
function toDashboard() { window.location.href = "dashboard.php"; }
function toggleSidebarMenu() { document.getElementById('appSidebar').classList.toggle('mobile-open'); }
function logout() { window.location.href = "func/logout.php"; }

function openViewModal(btnElement) {
    const data = JSON.parse(btnElement.getAttribute('data-info'));
    
    // PING CURRENT PAGE TO UPDATE SESSION TRACKER
    fetch(window.location.pathname + '?track_view_id=' + data.ID);

    document.getElementById('m-edit-btn').href = 'editResident.php?id=' + data.ID;
    
    const archiveBtn = document.getElementById('m-archive-btn');
    if (archiveBtn) archiveBtn.dataset.id = data.ID;

    const fullName = [data.first_name, data.middle_name, data.last_name].filter(Boolean).join(' ').trim();
    document.getElementById('m-name').textContent = fullName;
    document.getElementById('m-avatar').src = data.profile ? data.profile : `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=1c0202&color=fff&size=200`;
    document.getElementById('m-pwdid-top').textContent = data.pwdid_num || 'N/A';

    let status = 'Active';
    if (data.record_status === 'expired') status = 'Expired';
    else if (data.application_status === 'needs correction') status = 'Needs Correction';
    else if (data.application_status === 'rejected') status = 'Rejected';
    else if (data.application_status === 'under review') status = 'Under Review';
    const statusEl = document.getElementById('m-status');
    statusEl.textContent = status;
    statusEl.className = 'm-badge m-badge-' + status.toLowerCase().replace(/ /g, '-');

    const disContainer = document.getElementById('m-dis-badges');
    disContainer.innerHTML = '';
    if (data.disability_type) {
        const badgeMap = {
            "cognitive": "m-badge-cognitive", "visual": "m-badge-visual",
            "physical": "m-badge-physical", "auditory": "m-badge-auditory",
            "speech": "m-badge-speech", "psychosocial": "m-badge-psycho",
            "others": "m-badge-others"
        };
        data.disability_type.split(',').forEach(t => {
            t = t.trim();
            if(t) {
                const span = document.createElement('span');
                span.textContent = t;
                span.className = 'm-badge ' + (badgeMap[t.toLowerCase()] || 'm-badge-physical');
                disContainer.appendChild(span);
            }
        });
    }

    let ageText = 'N/A';
    if (data.birthdate && data.birthdate !== '0000-00-00') {
        const dob = new Date(data.birthdate);
        const age = Math.abs(new Date(Date.now() - dob.getTime()).getUTCFullYear() - 1970);
        ageText = dob.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) + ` (${age} years old)`;
    }
    document.getElementById('m-dob').textContent = ageText;
    document.getElementById('m-gender').textContent = data.sex ? (data.sex.charAt(0).toUpperCase() + data.sex.slice(1)) : 'N/A';
    document.getElementById('m-civil').textContent = data.civil_status || 'N/A';

    document.getElementById('m-dis-type').textContent = data.disability_type || 'N/A';
    document.getElementById('m-dis-cause').textContent = data.disability_remarks || 'N/A';
    
    const mc = document.getElementById('m-medcert');
    if (data.med_cert) mc.innerHTML = `<a href="${data.med_cert}" style="color:#3B82F6; text-decoration:underline;" target="_blank">View Medical Certificate</a>`;
    else mc.textContent = 'N/A';

    document.getElementById('m-pwdid').textContent = data.pwdid_num || 'N/A';
    document.getElementById('m-control').textContent = data.control_num || 'N/A';
    document.getElementById('m-issued').textContent = data.idissue_date || 'N/A';
    document.getElementById('m-expired').textContent = data.idexpiration_date || 'N/A';

    document.getElementById('m-mobile').textContent = data.contact_num || 'N/A';
    document.getElementById('m-address').textContent = data.address || 'N/A';
    document.getElementById('m-socials').textContent = data.socials || 'N/A';

    document.getElementById('m-em-name').textContent = data.emergency_name || 'N/A';
    document.getElementById('m-em-num').textContent = data.emergency_number || 'N/A';
    document.getElementById('m-em-rel').textContent = data.emergency_relation || 'N/A';

    document.getElementById('m-father').textContent = data.father_name || 'N/A';
    document.getElementById('m-mother').textContent = data.mother_name || 'N/A';
    document.getElementById('m-spouse').textContent = data.spouse_name || 'N/A';
    document.getElementById('m-guardian').textContent = data.guardian_name || 'N/A';
    document.getElementById('m-guardian-rel').textContent = data.guardian_rel || 'N/A';

    document.getElementById('viewModalOverlay').style.display = 'flex';
}

function confirmArchive(id) {
  document.getElementById("archiveId").value = id;
  document.getElementById('viewModalOverlay').style.display = 'none'; 
  document.getElementById("archiveModal").style.display = "flex";
}

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