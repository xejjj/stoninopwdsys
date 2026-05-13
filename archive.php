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
<?php require_once("func/getArchive.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Archived Residents</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/archive.css" />
</head>
<body>

<?php
// Capture and clear session flags before output
$show_restore_success = isset($_SESSION["arch_success"]) && $_SESSION["arch_success"] === "Resident restored successfully.";
$show_delete_success  = isset($_SESSION["arch_success"]) && $_SESSION["arch_success"] === "Resident permanently deleted.";
$arch_error           = $_SESSION["arch_error"] ?? "";
if (isset($_SESSION["arch_success"])) unset($_SESSION["arch_success"]);
if (isset($_SESSION["arch_error"]))   unset($_SESSION["arch_error"]);
?>

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
        <img src="assets/overviewicon.png" width="20">Overview
      </a>
    </div>
    <div class="nav-group">
      <a class="nav-item open" href="#" onclick="toggleMenu(event,'mgmt-sub')">
        <img src="assets/users.png" width="20">Management
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
        <img src="assets/reporticon.png" width="20">Reports
      </a>
    </div>
    <div class="nav-group">
      <a class="nav-item active" href="#" onclick="toggleMenu(event,'system-sub')">
        <img src="assets/settingicon.png" width="20">System
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub open" id="system-sub">
        <a class="nav-sub-item" href="system.php">System Tools</a>
        <a class="nav-sub-item" href="account.php">Accounts</a>
        <a class="nav-sub-item active" href="archive.php">Archive</a>
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
  <div class="content-card">

    <!-- Error banner (only for errors, not success) -->
    <?php if ($arch_error): ?>
      <div style="background:#FFE0E0;color:#c0392b;border:1px solid #f5c6c6;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-weight:700;font-size:13.5px;">
        ⚠️ <?= htmlspecialchars($arch_error) ?>
      </div>
    <?php endif; ?>

    <!-- ── Header ── -->
    <div class="card-header">
      <div class="page-title">
        <img style="cursor:pointer;" src="assets/leftchevron.png" width="12" onclick="toDashboard()">
        <h1>Archived Residents</h1>
      </div>
      <form method="GET" action="archive.php" id="searchForm" style="display:flex;gap:10px;align-items:center;">
        <?php if (!empty($filter_cat)):   ?><input type="hidden" name="category"   value="<?= htmlspecialchars($filter_cat) ?>"><?php endif; ?>
        <?php if (!empty($filter_sex)):   ?><input type="hidden" name="sex"        value="<?= htmlspecialchars($filter_sex) ?>"><?php endif; ?>
        <?php if (!empty($filter_disab)): ?><input type="hidden" name="disability" value="<?= htmlspecialchars($filter_disab) ?>"><?php endif; ?>
        <div class="search-bar">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          <input type="text" name="search" placeholder="Search name" value="<?= htmlspecialchars($search) ?>" oninput="debounceSearch()">
        </div>
      </form>
    </div>

    <!-- ── Hidden filter form ── -->
    <form method="GET" action="archive.php" id="filterForm">
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
      </select>
      <select name="category" id="sel-category" style="display:none" onchange="document.getElementById('filterForm').submit()">
        <option value="">All Categories</option>
        <option value="PWD" <?= $filter_cat === "PWD" ? "selected" : "" ?>>PWD</option>
        <option value="CWD" <?= $filter_cat === "CWD" ? "selected" : "" ?>>CWD</option>
      </select>
      <select name="sex" id="sel-sex" style="display:none" onchange="document.getElementById('filterForm').submit()">
        <option value="">All Sexes</option>
        <option value="male"   <?= $filter_sex === "male"   ? "selected" : "" ?>>Male</option>
        <option value="female" <?= $filter_sex === "female" ? "selected" : "" ?>>Female</option>
      </select>
    </form>

    <!-- ── Table ── -->
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>
              Full Name
              <?php if (!empty($search) || !empty($filter_cat) || !empty($filter_sex) || !empty($filter_disab)): ?>
                <a href="archive.php" style="font-size:10px;font-weight:700;color:var(--primary);text-decoration:none;margin-left:8px;">✕ Clear</a>
              <?php endif; ?>
            </th>
            <th class="th-filter <?= !empty($filter_disab) ? 'th-active' : '' ?>" onclick="openFilter('sel-disability', this)">
              Disability Type
              <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </th>
            <th class="th-filter <?= !empty($filter_cat) ? 'th-active' : '' ?>" onclick="openFilter('sel-category', this)">
              Category
              <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </th>
            <th class="th-filter <?= !empty($filter_sex) ? 'th-active' : '' ?>" onclick="openFilter('sel-sex', this)">
              Sex
              <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($archived_result) === 0): ?>
            <tr>
              <td colspan="5" style="text-align:center; padding:40px; color:var(--text-muted); font-weight:600;">
                No archived residents found.
              </td>
            </tr>
          <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($archived_result)): ?>
              <?php
                $full_name  = htmlspecialchars($row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_name']);
                $disability = $row['disablity_type'] ?? '';
                $category   = htmlspecialchars($row['resident_type'] ?? '—');
                $sex        = htmlspecialchars(strtoupper($row['sex'] ?? '—'));
                $types_arr  = array_filter(array_map('trim', explode(",", $disability)));
              ?>
              <tr>
                <td class="fw-bold"><?= $full_name ?></td>
                <td>
                  <div class="badge-group">
                    <?php foreach ($types_arr as $t): ?>
                      <span class="badge <?= badgeClass($t) ?>"><?= htmlspecialchars($t) ?></span>
                    <?php endforeach; ?>
                  </div>
                </td>
                <td class="fw-bold"><?= $category ?></td>
                <td class="fw-bold"><?= $sex ?></td>
                <td class="actions">
                  <button title="View" onclick="window.location.href='viewprofile.php?id=<?= $row['ID'] ?>&source=archive'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                  <button title="Restore" onclick="confirmRestore(<?= $row['ID'] ?>)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                  </button>
                  <button title="Delete permanently" onclick="confirmDelete(<?= $row['ID'] ?>)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                  </button>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- ── Pagination ── -->
    <div class="pagination">
      <span class="pagination-info">
        Page <?= $current_page ?> of <?= $total_pages ?> &nbsp;·&nbsp; <?= $total_rows ?> resident<?= $total_rows !== 1 ? "s" : "" ?>
      </span>
      <div class="pagination-btns">
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
</main>

<!-- ── Filter Dropdown ── -->
<div id="filterDropdown" class="filter-dropdown" style="display:none;">
  <div id="filterDropdownInner"></div>
</div>

<!-- ── Restore Confirm Modal ── -->
<div id="restoreModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15); text-align:center;">
    <div style="width:48px; height:48px; background:#EAF9EE; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#38C966" stroke-width="2.5"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; margin-bottom:10px; color:#1c0202;">Restore this resident?</h2>
    <p style="font-size:13.5px; color:rgba(28,2,2,0.6); margin-bottom:24px;">This will move the resident back to the active residents list.</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="document.getElementById('restoreModal').style.display='none'" style="padding:8px 18px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:none; font-family:inherit; font-weight:700; cursor:pointer;">Cancel</button>
      <form action="func/processRestore.php" method="POST" style="margin:0;">
        <input type="hidden" name="archive_id" id="restoreId">
        <button type="submit" style="padding:8px 18px; border-radius:8px; border:none; background:#38C966; color:#fff; font-family:inherit; font-weight:700; cursor:pointer;">Yes, Restore</button>
      </form>
    </div>
  </div>
</div>

<!-- ── Delete Confirm Modal ── -->
<div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15); text-align:center;">
    <div style="width:48px; height:48px; background:#FFE0E0; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#c0392b" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; margin-bottom:10px; color:#1c0202;">Permanently delete?</h2>
    <p style="font-size:13.5px; color:rgba(28,2,2,0.6); margin-bottom:24px;">This action cannot be undone. The resident record will be deleted forever.</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="document.getElementById('deleteModal').style.display='none'" style="padding:8px 18px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:none; font-family:inherit; font-weight:700; cursor:pointer;">Cancel</button>
      <form action="func/processDeleteArchive.php" method="POST" style="margin:0;">
        <input type="hidden" name="archive_id" id="deleteId">
        <button type="submit" style="padding:8px 18px; border-radius:8px; border:none; background:#A84040; color:#fff; font-family:inherit; font-weight:700; cursor:pointer;">Delete Permanently</button>
      </form>
    </div>
  </div>
</div>

<!-- ── Restore Success Modal ── -->
<?php if ($show_restore_success): ?>
<div id="restoreSuccessModal" style="display:flex; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:36px 32px; max-width:420px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15); text-align:center;">
    <div style="width:56px; height:56px; background:#EAF9EE; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#38C966" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; color:#1c0202; margin-bottom:8px;">Resident Restored!</h2>
    <p style="font-size:13.5px; color:rgba(28,2,2,0.55); margin-bottom:28px;">The resident has been moved back to the active residents list.</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="document.getElementById('restoreSuccessModal').style.display='none'" style="padding:10px 22px; border-radius:10px; border:1.5px solid rgba(0,0,0,0.1); background:#fff; font-family:inherit; font-size:13.5px; font-weight:700; color:rgba(28,2,2,0.6); cursor:pointer;">
        Stay in Archive
      </button>
      <button onclick="window.location.href='resident.php'" style="padding:10px 22px; border-radius:10px; border:none; background:#38C966; color:#fff; font-family:inherit; font-size:13.5px; font-weight:700; cursor:pointer;">
        View Residents
      </button>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- ── Delete Success Modal ── -->
<?php if ($show_delete_success): ?>
<div id="deleteSuccessModal" style="display:flex; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:36px 32px; max-width:420px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15); text-align:center;">
    <div style="width:56px; height:56px; background:#FFE0E0; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#c0392b" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; color:#1c0202; margin-bottom:8px;">Record Deleted</h2>
    <p style="font-size:13.5px; color:rgba(28,2,2,0.55); margin-bottom:28px;">The resident record has been permanently deleted.</p>
    <button onclick="document.getElementById('deleteSuccessModal').style.display='none'" style="padding:10px 22px; border-radius:10px; border:none; background:#A84040; color:#fff; font-family:inherit; font-size:13.5px; font-weight:700; cursor:pointer;">
      OK
    </button>
  </div>
</div>
<?php endif; ?>

<script>
function toggleMenu(event, id) {
  event.preventDefault();
  event.currentTarget.classList.toggle("open");
  document.getElementById(id).classList.toggle("open");
}
function toDashboard() { window.location.href = "dashboard.php"; }
function logout() {
  window.location.href = "func/logout.php";
}

let searchTimer;
function debounceSearch() {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => document.getElementById("searchForm").submit(), 400);
}

function confirmRestore(id) {
  document.getElementById("restoreId").value = id;
  document.getElementById("restoreModal").style.display = "flex";
}
function confirmDelete(id) {
  document.getElementById("deleteId").value = id;
  document.getElementById("deleteModal").style.display = "flex";
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
  ],
  'sel-category': [
    {value:'', label:'All Categories'},
    {value:'PWD', label:'PWD'},
    {value:'CWD', label:'CWD'},
  ],
  'sel-sex': [
    {value:'', label:'All Sexes'},
    {value:'male',   label:'Male'},
    {value:'female', label:'Female'},
  ],
};

let activeDropdown = null;

function closeDropdown() {
  document.getElementById('filterDropdown').style.display = 'none';
  activeDropdown = null;
}

function openFilter(selectId, thElement) {
  const dropdown = document.getElementById('filterDropdown');
  const inner    = document.getElementById('filterDropdownInner');
  const sel      = document.getElementById(selectId);

  if (activeDropdown === selectId) { closeDropdown(); return; }

  inner.innerHTML = '';
  filterOptions[selectId].forEach(opt => {
    const div = document.createElement('div');
    div.className = 'filter-option' + (sel.value === opt.value ? ' selected' : '');
    div.textContent = opt.label;
    div.onclick = (e) => {
      e.stopPropagation();
      sel.value = opt.value;
      closeDropdown();
      document.getElementById('filterForm').submit();
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

  let left = rect.left;
  let top  = rect.bottom + 4;

  if (left + dropWidth > window.innerWidth - 8) left = window.innerWidth - dropWidth - 8;
  if (top + dropHeight > window.innerHeight - 8) top = rect.top - dropHeight - 4;
  if (left < 8) left = 8;

  dropdown.style.left       = left + 'px';
  dropdown.style.top        = top  + 'px';
  dropdown.style.visibility = 'visible';
  activeDropdown = selectId;
}

document.addEventListener('click', (e) => {
  if (activeDropdown === null) return;
  if (!document.getElementById('filterDropdown').contains(e.target) && !e.target.closest('.th-filter')) {
    closeDropdown();
  }
});
</script>
</body>
</html>