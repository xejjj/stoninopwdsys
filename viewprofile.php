<?php
require_once("func/auth.php");
require_once("func/db.php");
require_once("func/helpers.php");

if (!isset($_GET['id'])) {
    header("Location: resident.php");
    exit();
}

$current_resident_id = intval($_GET['id']);
$source = $_GET["source"] ?? "";
$is_archive_view = ($source === "archive");

if (!isset($_SESSION['recent_views'])) {
    $_SESSION['recent_views'] = [];
}

$pos = array_search($current_resident_id, $_SESSION['recent_views']);

if ($pos !== false) {
    unset($_SESSION['recent_views'][$pos]);
}

array_unshift($_SESSION['recent_views'], $current_resident_id);
$_SESSION['recent_views'] = array_slice($_SESSION['recent_views'], 0, 5);

$sql = "
SELECT
    residents.*,

    resident_contacts.contact_num,
    resident_contacts.socials,

    resident_emergency_contacts.name AS emergency_name,
    resident_emergency_contacts.contact_num AS emergency_number,
    resident_emergency_contacts.relationship AS emergency_relation,

    GROUP_CONCAT(
        DISTINCT resident_disabilities.disability_type
        SEPARATOR ', '
    ) AS disability_type,

    MAX(resident_disabilities.notes) AS disability_remarks,

    MAX(CASE WHEN resident_family_members.relationship = 'Father'
        THEN resident_family_members.name END) AS father_name,

    MAX(CASE WHEN resident_family_members.relationship = 'Mother'
        THEN resident_family_members.name END) AS mother_name,

    MAX(CASE WHEN resident_family_members.relationship = 'Spouse'
        THEN resident_family_members.name END) AS spouse_name,

    MAX(CASE
        WHEN resident_family_members.relationship NOT IN ('Father', 'Mother', 'Spouse')
        THEN resident_family_members.name
    END) AS guardian_name,

    MAX(CASE
        WHEN resident_family_members.relationship NOT IN ('Father', 'Mother', 'Spouse')
        THEN resident_family_members.relationship
    END) AS guardian_rel,

    MAX(CASE
        WHEN resident_family_members.relationship NOT IN ('Father', 'Mother', 'Spouse')
        THEN resident_family_members.contact_num
    END) AS guardian_number

FROM residents

LEFT JOIN resident_contacts
ON residents.ID = resident_contacts.resident_id

LEFT JOIN resident_emergency_contacts
ON residents.ID = resident_emergency_contacts.resident_id

LEFT JOIN resident_disabilities
ON residents.ID = resident_disabilities.resident_id

LEFT JOIN resident_family_members
ON residents.ID = resident_family_members.resident_id

WHERE residents.ID = ?

GROUP BY residents.ID
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $current_resident_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$resident = mysqli_fetch_assoc($result);

if (!$resident) {
    die("Resident not found in the database.");
}

$full_name = htmlspecialchars(
    trim(
        ($resident['first_name'] ?? '') . ' ' .
        ($resident['middle_name'] ?? '') . ' ' .
        ($resident['last_name'] ?? '')
    )
);

if (($resident['record_status'] ?? '') === 'archived') {
    $status = 'Archived';
} elseif (($resident['record_status'] ?? '') === 'expired') {
    $status = 'Expired';
} elseif (($resident['application_status'] ?? '') === 'needs correction') {
    $status = 'Needs Correction';
} elseif (($resident['application_status'] ?? '') === 'rejected') {
    $status = 'Rejected';
} elseif (($resident['application_status'] ?? '') === 'under review') {
    $status = 'Under Review';
} else {
    $status = 'Active';
}

$status = htmlspecialchars($status);
$status_cls = "badge-" . strtolower(str_replace(' ', '-', $status));

$age_text = getFormattedAge($resident['birthdate'] ?? null);

function disabilityBadgeClass($type) {
    $map = [
        "cognitive"    => "badge-cognitive",
        "visual"       => "badge-visual",
        "physical"     => "badge-physical",
        "auditory"     => "badge-auditory",
        "speech"       => "badge-speech",
        "psychosocial" => "badge-psycho",
        "others"       => "badge-others",
    ];

    return $map[strtolower(trim($type))] ?? "badge-physical";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PWD/CWD Hub – Profile View</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/viewprofile.css" />
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon">
      <img src="assets/barangay-logo.png" width="50" alt="Logo">
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
        <img src="assets/overviewicon.png" width="20" alt="Overview">
        Overview
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item open" href="#" onclick="toggleMenu(event,'mgmt-sub')">
        <img src="assets/users.png" width="20" alt="Management">
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
        <img src="assets/reporticon.png" width="20" alt="Reports">
        Reports
      </a>
    </div>

    <?php
    $isAdmin = ($_SESSION["role"] ?? "") === "admin";
    if ($isAdmin):
    ?>
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
  <div class="container">

    <div class="action-bar">
      <button class="btn btn-back" onclick="history.back()">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back
      </button>
      <div class="actions-right">
        <?php if (!$is_archive_view): ?>
        <button class="btn btn-edit" onclick="window.location.href='editResident.php?id=<?= $current_resident_id ?>'">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
          Edit Profile
        </button>
        <button class="btn btn-archive" onclick="confirmArchive(<?= $current_resident_id ?>)">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
          Archive
        </button>
         <?php endif; ?>
      </div>
    </div>

    <div class="profile-header">
      <div class="banner"></div>
      <div class="profile-content">
        <div class="profile-left">
          <?php
          $profile_img = !empty($resident['profile'])
              ? htmlspecialchars($resident['profile'])
              : "https://ui-avatars.com/api/?name=" . urlencode($full_name) . "&background=1c0202&color=fff&size=200";
          ?>
          <img src="<?= $profile_img ?>" alt="Profile" class="avatar">
          <div class="profile-info">
            <div class="profile-name-row">
              <h1 class="profile-name"><?= $full_name ?></h1>
              <span class="badge <?= $status_cls ?>"><span class="dot"></span> <?= $status ?></span>
            </div>
            <div class="profile-id">ID #: <?= htmlspecialchars($resident['pwdid_num'] ?? 'N/A') ?></div>
          </div>
        </div>
        <div class="classification-box">
          <div class="classification-title">Disability Classification</div>
          <?php
          $disability = htmlspecialchars($resident['disability_type'] ?? 'None');
          $types = explode(",", $disability);
          foreach ($types as $t):
              $t = trim($t);
              if ($t):
          ?>
            <span class="badge <?= disabilityBadgeClass($t) ?>"><?= htmlspecialchars($t) ?></span>
          <?php endif; endforeach; ?>
        </div>
      </div>
    </div>

    <div class="details-grid">

      <div class="card">
        <div class="card-header">
          <div class="icon-box bg-orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
          <h3>Personal</h3>
        </div>
        <div class="info-list">
          <div class="info-group">
            <span class="info-label">Date of Birth</span>
            <span class="info-value"><?= htmlspecialchars($age_text) ?></span>
          </div>
          <div class="info-group">
            <span class="info-label">Sex</span>
            <span class="info-value"><?= htmlspecialchars(ucfirst($resident['sex'] ?: 'N/A')) ?></span>
          </div>
          <div class="info-group">
            <span class="info-label">Civil Status</span>
            <span class="info-value"><?= htmlspecialchars($resident['civil_status'] ?: 'N/A') ?></span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="icon-box bg-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg></div>
          <h3>Disability</h3>
        </div>
        <div class="info-list">
          <div class="info-group">
            <span class="info-label">Type of Disability/ies</span>
            <span class="info-value"><?= htmlspecialchars($resident['disability_type'] ?: 'N/A') ?></span>
          </div>
          <div class="info-group">
            <span class="info-label">Cause of Disability</span>
            <span class="info-value"><?= htmlspecialchars($resident['disability_remarks'] ?: 'N/A') ?></span>
          </div>
          <div class="info-group">
            <span class="info-label">Medical Certificate</span>
            <span class="info-value">
              <?php if (!empty($resident['med_cert'])): ?>
                <a href="<?= htmlspecialchars($resident['med_cert']) ?>" class="link" target="_blank">View Medical Certificate</a>
              <?php else: ?>
                N/A
              <?php endif; ?>
            </span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="icon-box bg-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
          <h3>ID Information</h3>
        </div>
        <div class="info-list">
          <div class="info-group">
            <span class="info-label">PWD ID Number</span>
            <span class="info-value"><?= htmlspecialchars($resident['pwdid_num'] ?: 'N/A') ?></span>
          </div>
          <div class="info-group">
            <span class="info-label">Control Number</span>
            <span class="info-value"><?= htmlspecialchars($resident['control_num'] ?: 'N/A') ?></span>
          </div>
          <div class="info-group">
            <span class="info-label">Date Issued</span>
            <span class="info-value"><?= htmlspecialchars($resident['idissue_date'] ?: 'N/A') ?></span>
          </div>
          <div class="info-group">
            <span class="info-label">Expiration Date</span>
            <span class="info-value"><?= htmlspecialchars($resident['idexpiration_date'] ?: 'N/A') ?></span>
          </div>
        </div>
      </div>

      <div class="card card-contact">
        <div class="card-header">
          <div class="icon-box bg-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
          <h3>Contact and Address</h3>
        </div>
        <div class="contact-grid">
          <div class="info-list">
            <div class="info-group">
              <span class="info-label">Mobile Number</span>
              <span class="info-value"><?= htmlspecialchars($resident['contact_num'] ?: 'N/A') ?></span>
            </div>
            <div class="info-group">
              <span class="info-label">House No. / Street</span>
              <span class="info-value"><?= htmlspecialchars($resident['address'] ?: 'N/A') ?></span>
            </div>
            <div class="info-group">
              <span class="info-label">Email/Facebook Account</span>
              <span class="info-value"><?= htmlspecialchars($resident['socials'] ?: 'N/A') ?></span>
            </div>
          </div>
          <div class="info-list">
            <div class="info-group">
              <span class="info-label">Emergency Contact Name</span>
              <span class="info-value"><?= htmlspecialchars($resident['emergency_name'] ?: 'N/A') ?></span>
            </div>
            <div class="info-group">
              <span class="info-label">Emergency Contact Number</span>
              <span class="info-value"><?= htmlspecialchars($resident['emergency_number'] ?: 'N/A') ?></span>
            </div>
            <div class="info-group">
              <span class="info-label">Relationship</span>
              <span class="info-value"><?= htmlspecialchars($resident['emergency_relation'] ?: 'N/A') ?></span>
            </div>
          </div>
          <div class="info-list">
            <div class="info-group">
              <span class="info-label">Father Name</span>
              <span class="info-value"><?= htmlspecialchars($resident['father_name'] ?: 'N/A') ?></span>
            </div>
            <div class="info-group">
              <span class="info-label">Mother Name</span>
              <span class="info-value"><?= htmlspecialchars($resident['mother_name'] ?: 'N/A') ?></span>
            </div>
            <div class="info-group">
              <span class="info-label">Spouse Name</span>
              <span class="info-value"><?= htmlspecialchars($resident['spouse_name'] ?: 'N/A') ?></span>
            </div>
            <div class="info-group">
              <span class="info-label">Guardian</span>
              <span class="info-value"><?= htmlspecialchars($resident['guardian_name'] ?: 'N/A') ?></span>
            </div>
            <div class="info-group">
              <span class="info-label">Guardian Relationship</span>
              <span class="info-value"><?= htmlspecialchars($resident['guardian_rel'] ?: 'N/A') ?></span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<div id="archiveModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
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
  const trigger = event.currentTarget;
  const submenu = document.getElementById(id);
  trigger.classList.toggle("open");
  submenu.classList.toggle("open");
}

function logout() {
  window.location.href = "func/logout.php";
}

function confirmArchive(id) {
  document.getElementById("archiveId").value = id;
  document.getElementById("archiveModal").style.display = "flex";
}
</script>
</body>
</html>
