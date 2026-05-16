<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Embedded Tracker: Updates session when user clicks the "View" eye icon
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
require_once("func/getResidents.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resident</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/resident.css" />
<style>
.header-left { display: flex; flex-direction: column; gap: 4px; flex-grow: 1; }
.breadcrumb { display: flex; align-items: center; gap: 6px; list-style: none; padding: 0; margin: 0 0 0 24px; font-size: 12.5px; font-weight: 500; color: #6B7280; }
.breadcrumb a { color: #A84040; text-decoration: none; transition: opacity 0.2s; }
.breadcrumb a:hover { text-decoration: underline; opacity: 0.8; }
.breadcrumb .active { color: #1A1A1A; font-weight: 600; }
.breadcrumb-separator { width: 12px; height: 12px; opacity: 0.5; }

.menu-toggle-btn { display: none; background: #fff; border: 1px solid #e0e0e0; border-radius: 8px; padding: 8px; cursor: pointer; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
.menu-toggle-btn svg { stroke: #333; width: 20px; height: 20px; }

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
  .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; box-shadow: 2px 0 15px rgba(0,0,0,0.1); }
  .sidebar.mobile-open { transform: translateX(0); }
  .main-content { margin-left: 0 !important; padding: 16px !important; }
  .menu-toggle-btn { display: flex; }
  .breadcrumb { margin-left: 10px; }
  .card-header { flex-direction: column !important; align-items: stretch !important; gap: 15px !important; }
  #searchForm { width: 100% !important; margin-bottom: 0; }
  .search-bar { width: 100% !important; }
  #viewModalContainer { max-height: 95vh; }
  .m-profile-content { flex-direction: column; align-items: center; text-align: center; padding: 0 20px 20px 20px; }
  .m-profile-left { flex-direction: column; align-items: center; gap: 10px; }
  .m-profile-name-row { flex-direction: column; gap: 6px; }
  .m-classification-box { margin-top: 15px; align-items: center; padding: 15px; width: 100%; }
  #m-dis-badges { justify-content: center !important; }
  .m-details-grid { grid-template-columns: 1fr; gap: 15px; }
  .m-contact-grid { grid-template-columns: 1fr; gap: 15px; }
  .m-card { padding: 20px; }
}

@media (max-width: 576px) {
  .pagination { flex-direction: column !important; align-items: center !important; text-align: center; gap: 15px; }
  .pagination > div { width: 100%; justify-content: center !important; }
  .pagination-btns { width: 100%; justify-content: center; }
}
</style>
</head>
<body>

<aside class="sidebar" id="appSidebar">
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
      <a class="nav-item active" href="#" onclick="toggleMenu(event,'mgmt-sub')">
        <img src="assets/users.png" width="20">
        Management
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub open" id="mgmt-sub">
        <a class="nav-sub-item active" href="resident.php">View Residents</a>
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

<main class="main-content">
  <div class="content-card">
    <div class="card-header" style="align-items: flex-end;">
      <div class="header-left">
        <ul class="breadcrumb">
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><svg class="breadcrumb-separator" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></li>
          <li>Management</li>
          <li><svg class="breadcrumb-separator" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></li>
          <li class="active">Residents List</li>
        </ul>
        <div class="page-title" style="display: flex; gap: 10px; align-items: center;">
          <button class="menu-toggle-btn" onclick="toggleSidebarMenu()" title="Toggle Menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
          </button>
          <img style="cursor:pointer;" src="assets/leftchevron.png" width="12" onclick="toDashboard()">
          <h1>Residents List</h1>
        </div>
      </div>
      <form method="GET" action="resident.php" id="searchForm" style="display:flex;gap:10px;align-items:center; margin-bottom: 2px;">
        <?php if (!empty($filter_cat)):    ?><input type="hidden" name="category"   value="<?= htmlspecialchars($filter_cat) ?>"><?php endif; ?>
        <?php if (!empty($filter_sex)):    ?><input type="hidden" name="sex"        value="<?= htmlspecialchars($filter_sex) ?>"><?php endif; ?>
        <?php if (!empty($filter_status)): ?><input type="hidden" name="status"     value="<?= htmlspecialchars($filter_status) ?>"><?php endif; ?>
        <?php if (!empty($filter_disab)):  ?><input type="hidden" name="disability" value="<?= htmlspecialchars($filter_disab) ?>"><?php endif; ?>
        <div class="search-bar">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          <input type="text" name="search" id="liveSearchInput" placeholder="Search name" value="<?= htmlspecialchars($search) ?>" autocomplete="off">
        </div>
      </form>
    </div>

    <form method="GET" action="resident.php" id="filterForm">
      <?php if (!empty($search)): ?>
        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
      <?php endif; ?>
      <select name="disability" id="sel-disability" style="display:none" onchange="document.getElementById('filterForm').submit()">
        <option value="">All Disability Types</option>
        <option value="Cognitive"    <?= $filter_disab === "Cognitive"    ? "selected" : "" ?>>Cognitive</option>
        <option value="Visual"       <?= $filter_disab === "Visual"       ? "selected" : "" ?>>Visual</option>
        <option value="Physical"     <?= $filter_disab === "Physical"     ? "selected" : "" ?>>Physical</option>
        <option value="Auditory"     <?= $filter_disab === "Auditory"     ? "selected" : "" ?>>Auditory</option>
        <option value="Speech"       <?= $filter_disab === "Speech"       ? "selected" : "" ?>>Speech</option>
        <option value="Psychosocial" <?= $filter_disab === "Psychosocial" ? "selected" : "" ?>>Psychosocial</option>
        <option value="Others"       <?= $filter_disab === "Others"       ? "selected" : "" ?>>Others</option>
      </select>
      <select name="category" id="sel-category" style="display:none" onchange="document.getElementById('filterForm').submit()">
        <option value="">All Categories</option>
        <option value="PWD" <?= $filter_cat === "PWD" ? "selected" : "" ?>>PWD</option>
        <option value="CWD" <?= $filter_cat === "CWD" ? "selected" : "" ?>>CWD</option>
      </select>
      <select name="sex" id="sel-sex" style="display:none" onchange="document.getElementById('filterForm').submit()">
        <option value="">All Genders</option>
        <option value="male"   <?= $filter_sex === "male"   ? "selected" : "" ?>>Male</option>
        <option value="female" <?= $filter_sex === "female" ? "selected" : "" ?>>Female</option>
      </select>
      <select name="status" id="sel-status" style="display:none" onchange="document.getElementById('filterForm').submit()">
        <option value="">All Statuses</option>
        <option value="Active"  <?= $filter_status === "Active"  ? "selected" : "" ?>>Active</option>
        <option value="Under Review" <?= $filter_status === "Under Review" ? "selected" : "" ?>>Under Review</option>
        <option value="Needs Correction" <?= $filter_status === "Needs Correction" ? "selected" : "" ?>>Needs Correction</option>
        <option value="Rejected" <?= $filter_status === "Rejected" ? "selected" : "" ?>>Rejected</option>
        <option value="Expired" <?= $filter_status === "Expired" ? "selected" : "" ?>>Expired</option>
      </select>
    </form>

    <div class="table-wrap" style="-webkit-overflow-scrolling: touch;">
      <table class="data-table">
        <thead>
          <tr>
            <th style="width: 40px; text-align: center;">#</th>
            <th>Full Name</th>
            <th class="th-filter <?= !empty($filter_disab) ? 'th-active' : '' ?>" onclick="openFilter('sel-disability', this)">
              Disability Type
              <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </th>
            <th class="th-filter <?= !empty($filter_cat) ? 'th-active' : '' ?>" onclick="openFilter('sel-category', this)">
              Category
              <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </th>
            <th class="th-filter <?= !empty($filter_sex) ? 'th-active' : '' ?>" onclick="openFilter('sel-sex', this)">
              Gender
              <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </th>
            <th class="th-filter <?= !empty($filter_status) ? 'th-active' : '' ?>" onclick="openFilter('sel-status', this)">
              Status
              <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </th>
            <th></th>
          </tr>
        </thead>
        <tbody id="residentTable">
          <?php if (mysqli_num_rows($residents_result) === 0): ?>
            <tr>
              <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted); font-weight:600;">
                No residents found.
              </td>
            </tr>
          <?php else: ?>
            <?php 
              $row_num = $offset + 1; 
            ?>
            <?php while ($user = mysqli_fetch_assoc($residents_result)): ?>
              <?php
                $full_name  = htmlspecialchars($user['last_name'] . ", " . $user['first_name'] . " " . $user['middle_name']);
                $disability = $user['disability_type'] ?? '';
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
                $status_cls = "status-" . strtolower(str_replace(" ", "-", $status));
                $is_expiring_soon = false;

                if (!empty($user['idexpiration_date']) && $status !== 'Expired') {
                    $today = date('Y-m-d');
                    $one_month = date('Y-m-d', strtotime('+1 month'));
                    $exp_date = date('Y-m-d', strtotime($user['idexpiration_date']));

                    if ($exp_date >= $today && $exp_date <= $one_month) {
                        $is_expiring_soon = true;
                    }
                }
                $types_arr  = array_filter(array_map('trim', explode(",", $disability)));
                $json_data = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
              ?>
              <tr>
                <td style="text-align: center; color: #888; font-weight: 600; font-size: 13px;">
                  <?= $row_num++ ?>
                </td>
                <td class="fw-bold">
                  <?php if ($is_expiring_soon): ?>
                    <span class="expiry-warning" title="ID Expiring Soon (<?= htmlspecialchars($user['idexpiration_date']) ?>)">!</span>
                  <?php endif; ?>
                  <?= $full_name ?>
                </td>
                <td>
                  <div class="badge-group">
                    <?php foreach ($types_arr as $t): ?>
                      <span class="badge <?= badgeClass($t) ?>"><?= htmlspecialchars($t) ?></span>
                    <?php endforeach; ?>
                  </div>
                </td>
                <td class="fw-bold"><?= $category ?></td>
                <td class="fw-bold"><?= $sex ?></td>
                <td><span class="status <?= $status_cls ?>"><?= $status ?></span></td>
                <td class="actions">
                  <button title="View" onclick="openViewModal(this)" data-info="<?= $json_data ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="pagination" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-top: 15px;">
      <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap; flex: 1;">
        <span class="pagination-info">
          Page <?= $current_page ?> of <?= $total_pages ?> &nbsp;·&nbsp; <?= $total_rows ?> resident<?= $total_rows !== 1 ? "s" : "" ?>
        </span>

        <div style="display: flex; gap: 8px;">
            <?php if (!empty($search) || !empty($filter_cat) || !empty($filter_sex) || !empty($filter_status) || !empty($filter_disab)): ?>
            <button onclick="clearFilters()" style="background: #ffffff; border: 1px solid #e0e0e0; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 13px; color: #A84040; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: background 0.2s; white-space: nowrap;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                Clear Filters
            </button>
            <?php endif; ?>
            
            <button onclick="printCurrentFilter()" style="background: #ffffff; border: 1px solid #e0e0e0; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 13px; color: #333; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: background 0.2s; white-space: nowrap;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Print Current Filters
            </button>
        </div>
      </div>

      <div style="min-width: 170px; display: flex; justify-content: flex-end;">
        <div class="pagination-btns" style="display: flex; gap: 4px;">
          <a href="<?= buildQuery($current_page - 1) ?>" class="page-btn <?= $current_page <= 1 ? 'disabled' : '' ?>">‹</a>
          <?php
            $start = max(1, $current_page - 2);
            $end   = min($total_pages, $current_page + 2);
            if ($start > 1): ?>
              <a href="<?= buildQuery(1) ?>" class="page-btn">1</a>
              <?php if ($start > 2): ?><span class="page-btn" style="pointer-events:none;">…</span><?php endif; ?>
            <?php endif;
            for ($i = $start; $i <= $end; $i++): ?>
              <a href="<?= buildQuery($i) ?>" class="page-btn <?= $i === $current_page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor;
            if ($end < $total_pages): ?>
              <?php if ($end < $total_pages - 1): ?><span class="page-btn" style="pointer-events:none;">…</span><?php endif; ?>
              <a href="<?= buildQuery($total_pages) ?>" class="page-btn"><?= $total_pages ?></a>
            <?php endif; ?>
          <a href="<?= buildQuery($current_page + 1) ?>" class="page-btn <?= $current_page >= $total_pages ? 'disabled' : '' ?>">›</a>
        </div>
      </div>
    </div>
  </div>
</main>

<div id="filterDropdown" class="filter-dropdown" style="display:none;">
  <div id="filterDropdownInner"></div>
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

function getFilterParams() {
  const form = document.getElementById('filterForm');
  let hiddenSearch = form.querySelector('input[name="search"]');
  if (!hiddenSearch) {
      hiddenSearch = document.createElement('input');
      hiddenSearch.type = 'hidden';
      hiddenSearch.name = 'search';
      form.appendChild(hiddenSearch);
  }
  hiddenSearch.value = document.getElementById('liveSearchInput').value;
  const params = new URLSearchParams(new FormData(form));
  params.set('type', 'resident_list');
  return params.toString();
}

function printCurrentFilter() {
  window.open('func/processPrintReports.php?' + getFilterParams(), '_blank');
}

function clearFilters() {
  document.getElementById('liveSearchInput').value = '';
  document.getElementById('sel-disability').value = '';
  document.getElementById('sel-category').value = '';
  document.getElementById('sel-sex').value = '';
  document.getElementById('sel-status').value = '';
  let hiddenSearch = document.getElementById('filterForm').querySelector('input[name="search"]');
  if (hiddenSearch) hiddenSearch.remove();
  updateDOM("resident.php?clear_filters=1");
}

function updateDOM(url) {
  fetch(url)
    .then(response => response.text())
    .then(html => {
      const doc = new DOMParser().parseFromString(html, "text/html");
      document.querySelector(".table-wrap").innerHTML = doc.querySelector(".table-wrap").innerHTML;
      document.querySelector(".pagination").innerHTML = doc.querySelector(".pagination").innerHTML;
      window.history.pushState({path: url}, "", url);
    })
    .catch(err => console.error("Fetch error:", err));
}

window.addEventListener("popstate", function() {
    updateDOM(window.location.href);
});

const searchForm = document.getElementById("searchForm");
const searchInput = document.getElementById("liveSearchInput");
let searchTimer;

searchForm.addEventListener("submit", function(e) { e.preventDefault(); });

searchInput.addEventListener("input", function() {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => {
    const formData = new FormData(searchForm);
    const params = new URLSearchParams(formData);
    params.set("page", 1); 
    updateDOM("resident.php?" + params.toString());
  }, 300);
});

document.addEventListener("click", function(e) {
    const pageLink = e.target.closest(".page-btn");
    if (pageLink && pageLink.tagName === "A" && !pageLink.classList.contains("disabled") && !pageLink.classList.contains("active")) {
        e.preventDefault();
        updateDOM(pageLink.getAttribute("href"));
    }
});

function applyFilters() {
    const form = document.getElementById('filterForm');
    let hiddenSearch = form.querySelector('input[name="search"]');
    if (!hiddenSearch) {
        hiddenSearch = document.createElement('input');
        hiddenSearch.type = 'hidden';
        hiddenSearch.name = 'search';
        form.appendChild(hiddenSearch);
    }
    hiddenSearch.value = document.getElementById('liveSearchInput').value;
    const params = new URLSearchParams(new FormData(form));
    params.delete("page"); 
    updateDOM("resident.php?" + params.toString());
}

const filterOptions = {
  'sel-disability': [
    {value:'', label:'All Disability Types'},
    {value:'Cognitive',    label:'Cognitive'},
    {value:'Visual',       label:'Visual'},
    {value:'Physical',     label:'Physical'},
    {value:'Auditory',     label:'Auditory'},
    {value:'Speech',       label:'Speech'},
    {value:'Psychosocial', label:'Psychosocial'},
    {value:'Others',       label:'Others'},
  ],
  'sel-category': [
    {value:'', label:'All Categories'},
    {value:'PWD', label:'PWD'},
    {value:'CWD', label:'CWD'},
  ],
  'sel-sex': [
    {value:'', label:'All Genders'},
    {value:'male',   label:'Male'},
    {value:'female', label:'Female'},
  ],
  'sel-status': [
    {value:'', label:'All Statuses'},
    {value:'Active',  label:'Active'},
    {value:'Under Review', label:'Under Review'},
    {value:'Needs Correction', label:'Needs Correction'},
    {value:'Rejected', label:'Rejected'},
    {value:'Expired', label:'Expired'},
  ],
};

let activeDropdown = null;
function closeDropdown() {
  const dropdown = document.getElementById('filterDropdown');
  dropdown.style.display = 'none';
  activeDropdown = null;
}

function openFilter(selectId, thElement) {
  const dropdown = document.getElementById('filterDropdown');
  const inner    = document.getElementById('filterDropdownInner');
  const sel      = document.getElementById(selectId);

  if (activeDropdown === selectId) {
    closeDropdown();
    return;
  }

  inner.innerHTML = '';
  filterOptions[selectId].forEach(opt => {
    const div = document.createElement('div');
    div.className = 'filter-option' + (sel.value === opt.value ? ' selected' : '');
    div.textContent = opt.label;
    div.onclick = (e) => {
      e.stopPropagation();
      sel.value = opt.value;
      closeDropdown();
      applyFilters(); 
    };
    inner.appendChild(div);
  });

  dropdown.style.visibility = 'hidden';
  dropdown.style.display    = 'block';
  dropdown.style.top        = '-9999px';
  dropdown.style.left       = '-9999px';

  const dropWidth  = dropdown.offsetWidth;
  const dropHeight = dropdown.offsetHeight;
  const rect       = thElement.getBoundingClientRect();
  const viewWidth  = window.innerWidth;
  const viewHeight = window.innerHeight;

  let left = rect.left;
  let top  = rect.bottom + 4;

  if (left + dropWidth > viewWidth - 8) { left = viewWidth - dropWidth - 8; }
  if (top + dropHeight > viewHeight - 8) { top = rect.top - dropHeight - 4; }
  if (left < 8) left = 8;

  dropdown.style.left       = left + 'px';
  dropdown.style.top        = top  + 'px';
  dropdown.style.visibility = 'visible';
  activeDropdown = selectId;
}

document.addEventListener('click', (e) => {
  if (activeDropdown === null) return;
  const dropdown = document.getElementById('filterDropdown');
  if (!dropdown.contains(e.target) && !e.target.closest('.th-filter') && !e.target.closest('.menu-toggle-btn')) {
    closeDropdown();
  }
});
</script>
</body>
</html>