<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }

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

@media (max-width: 992px) {
  .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; box-shadow: 2px 0 15px rgba(0,0,0,0.1); }
  .sidebar.mobile-open { transform: translateX(0); }
  .main-content { margin-left: 0 !important; padding: 16px !important; }
  .menu-toggle-btn { display: flex; }
  .breadcrumb { margin-left: 10px; }
  .card-header { flex-direction: column !important; align-items: stretch !important; gap: 15px !important; }
  #searchForm { width: 100% !important; margin-bottom: 0; }
  .search-bar { width: 100% !important; }
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
                
                // Track Missing Required Data including image field
                $is_missing = false;
                if (empty($user['birthdate']) || $user['birthdate'] == '0000-00-00' ||
                    empty($user['sex']) || empty($user['civil_status']) ||
                    empty($disability) || empty($user['pwdid_num']) || empty($user['pwd_id_card']) ||
                    empty($user['control_num']) || empty($user['idissue_date']) || $user['idissue_date'] == '0000-00-00' ||
                    empty($user['idexpiration_date']) || $user['idexpiration_date'] == '0000-00-00' ||
                    empty($user['contact_num']) || empty($user['address'])) {
                    $is_missing = true;
                }

                $json_data = htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8');
              ?>
              <tr data-missing="<?= $is_missing ? 'true' : 'false' ?>">
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
            
            <button onclick="toggleMissingHighlight()" id="toggleMissingBtn" style="background: #ffffff; border: 1px solid #e0e0e0; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 13px; color: #DC2626; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: background 0.2s; white-space: nowrap;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                Toggle Missing Info
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

<?php require_once("func/modals.php"); ?>

<script>
function toggleMenu(event, id) {
  event.preventDefault();
  event.currentTarget.classList.toggle("open");
  document.getElementById(id).classList.toggle("open");
}
function toDashboard() { window.location.href = "dashboard.php"; }
function toggleSidebarMenu() { document.getElementById('appSidebar').classList.toggle('mobile-open'); }
function logout() { window.location.href = "func/logout.php"; }

function toggleMissingHighlight() {
    document.body.classList.toggle('show-missing-info');
    const btn = document.getElementById('toggleMissingBtn');
    if (document.body.classList.contains('show-missing-info')) {
        btn.style.background = '#FEF2F2';
        btn.style.borderColor = '#FCA5A5';
    } else {
        btn.style.background = '#ffffff';
        btn.style.borderColor = '#e0e0e0';
    }
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