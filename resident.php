<?php require_once("func/getResidents.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resident</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/resident.css" />
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

<!-- ── MAIN ── -->
<main class="main-content">
  <div class="content-card">

    <!-- ── Header ── -->
    <div class="card-header">
      <div class="page-title">
        <img style="cursor:pointer;" src="assets/leftchevron.png" width="12" onclick="toDashboard()">
        <h1>Residents List</h1>
      </div>
      <form method="GET" action="resident.php" id="searchForm" style="display:flex;gap:10px;align-items:center;">
        <?php if (!empty($filter_cat)):    ?><input type="hidden" name="category"   value="<?= htmlspecialchars($filter_cat) ?>"><?php endif; ?>
        <?php if (!empty($filter_sex)):    ?><input type="hidden" name="sex"        value="<?= htmlspecialchars($filter_sex) ?>"><?php endif; ?>
        <?php if (!empty($filter_status)): ?><input type="hidden" name="status"     value="<?= htmlspecialchars($filter_status) ?>"><?php endif; ?>
        <?php if (!empty($filter_disab)):  ?><input type="hidden" name="disability" value="<?= htmlspecialchars($filter_disab) ?>"><?php endif; ?>
        <div class="search-bar">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          <input type="text" name="search" placeholder="Search name" value="<?= htmlspecialchars($search) ?>" oninput="debounceSearch()">
        </div>
      </form>
    </div>

    <!-- ── Hidden filter form (driven by th clicks) ── -->
    <form method="GET" action="resident.php" id="filterForm">
      <?php if (!empty($search)): ?>
        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
      <?php endif; ?>
      <select name="disability" id="sel-disability" style="display:none" onchange="document.getElementById('filterForm').submit()">
        <option value="">All Disability Types</option>
        <option value="Cognitive"    <?= $filter_disab === "Cognitive"    ? "selected" : "" ?>>Cognitive</option>
        <option value="Visual"       <?= $filter_disab === "Visual"       ? "selected" : "" ?>>Visual</option>
        <option value="Physical"        <?= $filter_disab === "Physical"        ? "selected" : "" ?>>Physical</option>
        <option value="Auditory"     <?= $filter_disab === "Auditory"     ? "selected" : "" ?>>Auditory</option>
        <option value="Speech"       <?= $filter_disab === "Speech"       ? "selected" : "" ?>>Speech</option>
        <option value="Psychosocial" <?= $filter_disab === "Psychosocial" ? "selected" : "" ?>>Psychosocial</option>
        <option value="Others" <?= $filter_disab === "Others" ? "selected" : "" ?>>Others</option>
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
      <select name="status" id="sel-status" style="display:none" onchange="document.getElementById('filterForm').submit()">
        <option value="">All Statuses</option>
        <option value="Active"  <?= $filter_status === "Active"  ? "selected" : "" ?>>Active</option>
        <option value="Pending" <?= $filter_status === "Pending" ? "selected" : "" ?>>Pending</option>
        <option value="Expired" <?= $filter_status === "Expired" ? "selected" : "" ?>>Expired</option>
      </select>
    </form>

    <!-- ── Table (wrapped so it grows and pushes pagination down) ── -->
    <div class="table-wrap">
      <table class="data-table">
        <thead>
          <tr>
            <th>
              Full Name
              <?php if (!empty($search) || !empty($filter_cat) || !empty($filter_sex) || !empty($filter_status) || !empty($filter_disab)): ?>
                <a href="resident.php" style="font-size:10px;font-weight:700;color:var(--primary);text-decoration:none;margin-left:8px;">✕ Clear</a>
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
            <th class="th-filter <?= !empty($filter_status) ? 'th-active' : '' ?>" onclick="openFilter('sel-status', this)">
              Status
              <svg width="14" height="14" class="th-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
            </th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($residents_result) === 0): ?>
            <tr>
              <td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted); font-weight:600;">
                No residents found.
              </td>
            </tr>
          <?php else: ?>
            <?php while ($user = mysqli_fetch_assoc($residents_result)): ?>
              <?php
                $full_name  = htmlspecialchars($user['last_name'] . ", " . $user['first_name'] . " " . $user['middle_name']);
                $disability = $user['disablity_type'] ?? '';
                $category   = htmlspecialchars($user['resident_type'] ?? '—');
                $sex        = htmlspecialchars(strtoupper($user['sex'] ?? '—'));
                $status     = htmlspecialchars($user['status'] ?? 'Pending');
                $status_cls = "status-" . strtolower($status);
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
                <td><span class="status <?= $status_cls ?>"><?= $status ?></span></td>
                <td class="actions">
                  <button title="Edit" onclick="window.location.href='editResident.php?id=<?= $user['ID'] ?>'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button title="View" onclick="window.location.href='viewprofile.php?id=<?= $user['ID'] ?>'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div><!-- end table-wrap -->

    <!-- ── Pagination (pinned to bottom-right of card) ── -->
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

  </div><!-- end content-card -->
</main>

<!-- ── Filter Dropdown (outside main so position:fixed works) ── -->
<div id="filterDropdown" class="filter-dropdown" style="display:none;">
  <div id="filterDropdownInner"></div>
</div>

<script>
function toggleMenu(event, id) {
  event.preventDefault();
  event.currentTarget.classList.toggle("open");
  document.getElementById(id).classList.toggle("open");
}
function toDashboard() { window.location.href = "dashboard.php"; }
function logout()      { window.location.href = "login.php"; }

let searchTimer;
function debounceSearch() {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => {
    document.getElementById("searchForm").submit();
  }, 400);
}

const filterOptions = {
  'sel-disability': [
    {value:'', label:'All Disability Types'},
    {value:'Cognitive',    label:'Cognitive'},
    {value:'Visual',       label:'Visual'},
    {value:'Physical',        label:'Physical'},
    {value:'Auditory',     label:'Auditory'},
    {value:'Speech',       label:'Speech'},
    {value:'Psychosocial', label:'Psychosocial'},
    {value:'Others', label:'Others'},
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
  'sel-status': [
    {value:'', label:'All Statuses'},
    {value:'Active',  label:'Active'},
    {value:'Pending', label:'Pending'},
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

  // Toggle: close if same header clicked while open
  if (activeDropdown === selectId) {
    closeDropdown();
    return;
  }

  // Build option list
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

  // Hide off-screen first to measure real dimensions
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

  // Clamp right edge
  if (left + dropWidth > viewWidth - 8) {
    left = viewWidth - dropWidth - 8;
  }
  // Clamp bottom edge — show above th if no room below
  if (top + dropHeight > viewHeight - 8) {
    top = rect.top - dropHeight - 4;
  }
  // Never go off left edge
  if (left < 8) left = 8;

  dropdown.style.left       = left + 'px';
  dropdown.style.top        = top  + 'px';
  dropdown.style.visibility = 'visible';
  activeDropdown = selectId;
}

// Close when clicking anywhere outside the dropdown or th headers
document.addEventListener('click', (e) => {
  if (activeDropdown === null) return;
  const dropdown = document.getElementById('filterDropdown');
  if (!dropdown.contains(e.target) && !e.target.closest('.th-filter')) {
    closeDropdown();
  }
});
</script>

</body>
</html>