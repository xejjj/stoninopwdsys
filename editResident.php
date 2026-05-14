<?php
session_start();
require_once("func/db.php");

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($id <= 0) {
    header("Location: resident.php");
    exit();
}

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

    MAX(CASE
        WHEN resident_family_members.relationship = 'Father'
        THEN resident_family_members.name
    END) AS father_name,

    MAX(CASE
        WHEN resident_family_members.relationship = 'Mother'
        THEN resident_family_members.name
    END) AS mother_name,

    MAX(CASE
        WHEN resident_family_members.relationship = 'Spouse'
        THEN resident_family_members.name
    END) AS spouse_name,

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
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$r = mysqli_fetch_assoc($result);

if (!$r) {
    header("Location: resident.php");
    exit();
}

$saved_disabilities = array_map(
    'trim',
    explode(",", $r['disability_type'] ?? "")
);

function isChecked($val, $arr) {
    return in_array($val, $arr) ? "checked" : "";
}

$show_edit_success    = isset($_SESSION["edit_success"]);
$show_archive_success = isset($_SESSION["arch_success"]);
$edit_error           = $_SESSION["edit_error"] ?? "";

if ($show_edit_success) {
    unset($_SESSION["edit_success"]);
}

if ($show_archive_success) {
    unset($_SESSION["arch_success"]);
}

if ($edit_error) {
    unset($_SESSION["edit_error"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PWD/CWD Hub – Edit Registration</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/editResident.css" />
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
        <img src="assets/overviewicon.png" width="20">Overview
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item open active" href="#" onclick="toggleMenu(event,'mgmt-sub')">
        <img src="assets/users.png" width="20">Management
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
        <img src="assets/reporticon.png" width="20">Reports
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

<div class="main">
  <header class="topbar">
    <h1 class="topbar-title">Edit Registration</h1>
  </header>

  <div class="content">

    <?php if ($edit_error): ?>
      <div class="alert alert-error">⚠️ <?= htmlspecialchars($edit_error) ?></div>
    <?php endif; ?>

    <form action="func/processEdit.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="resident_id" value="<?= htmlspecialchars($r['ID']) ?>">

      <div class="card">
        <div class="card-title">Personal Information</div>
        <div class="form-grid">
          <div class="field">
            <label>First Name</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($r['first_name'] ?? '') ?>" placeholder="e.g. Juan">
          </div>
          <div class="field">
            <label>Middle Name</label>
            <input type="text" name="middle_name" value="<?= htmlspecialchars($r['middle_name'] ?? '') ?>" placeholder="e.g. Dela">
          </div>
          <div class="field">
            <label>Last Name</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($r['last_name'] ?? '') ?>" placeholder="e.g. Cruz">
          </div>
          <div class="field">
            <label>Civil Status</label>
            <select name="civil_status">
              <option value="">Select status</option>
              <?php foreach (["Single", "Married", "Widowed", "Separated"] as $cs): ?>
                <option value="<?= $cs ?>" <?= ($r['civil_status'] ?? '') === $cs ? "selected" : "" ?>><?= $cs ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($r['birthdate'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Place of Birth</label>
            <input type="text" name="pob" value="<?= htmlspecialchars($r['birthplace'] ?? '') ?>" placeholder="e.g. Tondo General Hospital">
          </div>
          <div class="field">
            <label>Age</label>
            <input type="number" value="<?= !empty($r['birthdate']) ? htmlspecialchars(date_diff(date_create($r['birthdate']), date_create('today'))->y) : '' ?>" min="0" max="130" readonly>
          </div>
          <div class="field">
            <label>Sex</label>
            <select name="sex">
              <option value="">Select sex</option>
              <option value="male" <?= strtolower($r['sex'] ?? '') === "male" ? "selected" : "" ?>>Male</option>
              <option value="female" <?= strtolower($r['sex'] ?? '') === "female" ? "selected" : "" ?>>Female</option>
            </select>
          </div>
          <div class="field">
            <label>Upload Profile</label>
            <?php if (!empty($r['profile'])): ?>
              <img src="<?= htmlspecialchars($r['profile']) ?>" alt="Profile" style="width:60px;height:60px;border-radius:50%;object-fit:cover;margin-bottom:8px;display:block;">
            <?php endif; ?>
            <label class="file-input-wrap">
              <svg viewBox="0 0 24 24"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
              <span id="fileLabel">Choose file…</span>
              <input type="file" name="profile_pic" accept="image/*" onchange="document.getElementById('fileLabel').textContent = this.files[0]?.name || 'Choose file…'">
            </label>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-title">Contact and Address Information</div>
        <div class="form-grid">
          <div class="field">
            <label>Contact Number</label>
            <input type="tel" name="contact_number" value="<?= htmlspecialchars($r['contact_num'] ?? '') ?>" placeholder="09XX XXX XXXX">
          </div>
          <div class="field">
            <label>Emergency Contact Name</label>
            <input type="text" name="emergency_name" value="<?= htmlspecialchars($r['emergency_name'] ?? '') ?>" placeholder="Full name">
          </div>
          <div class="field">
            <label>Emergency Contact Number</label>
            <input type="tel" name="emergency_number" value="<?= htmlspecialchars($r['emergency_number'] ?? '') ?>" placeholder="09XX XXX XXXX">
          </div>
          <div class="field">
            <label>Relationship with Emergency Contact</label>
            <input type="text" name="emergency_relation" value="<?= htmlspecialchars($r['emergency_relation'] ?? '') ?>" placeholder="e.g. Parent, Sibling">
          </div>
          <div class="field">
            <label>Email/Facebook Account</label>
            <input type="text" name="account_name" value="<?= htmlspecialchars($r['socials'] ?? '') ?>">
          </div>
          <div class="field span-2">
            <label>House No. and Street</label>
            <input type="text" name="address" value="<?= htmlspecialchars($r['address'] ?? '') ?>" placeholder="e.g. 12 Sampaguita St.">
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-title">Disability Information</div>
        <div class="form-grid cols-2">
          <div class="field span-all">
            <div class="checkbox-grid">
              <?php foreach (["Cognitive", "Visual", "Physical", "Auditory", "Speech", "Psychosocial", "Others"] as $disability): ?>
                <label class="checkbox-label">
                  <input type="checkbox" name="disability_type[]" value="<?= $disability ?>" <?= isChecked($disability, $saved_disabilities) ?>>
                  <?= $disability ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="field span-all">
            <label>Remarks</label>
            <textarea name="remarks" rows="3" placeholder="Additional notes about the disability..." style="resize:none;"><?= htmlspecialchars($r['disability_remarks'] ?? '') ?></textarea>
          </div>
          <div class="field span-all">
            <label>Medical Certificate</label>

            <?php if (!empty($r['med_cert'])): ?>
              <a href="<?= htmlspecialchars($r['med_cert']) ?>" target="_blank" style="display:inline-block;margin-bottom:10px;color:#A84040;font-weight:700;text-decoration:none;">
                View Current Medical Certificate
              </a>
            <?php endif; ?>

            <label class="file-input-wrap">
              <svg viewBox="0 0 24 24"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
              <span id="medCertLabel">Upload new medical certificate…</span>
              <input type="file" name="med_cert" accept=".pdf,.jpg,.jpeg,.png" onchange="document.getElementById('medCertLabel').textContent = this.files[0]?.name || 'Upload new medical certificate…'">
            </label>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-title">Family Information</div>
        <div class="form-grid cols-3">
          <div class="field">
            <label>Father Name</label>
            <input type="text" name="father_name" value="<?= htmlspecialchars($r['father_name'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Mother Name</label>
            <input type="text" name="mother_name" value="<?= htmlspecialchars($r['mother_name'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Spouse Name</label>
            <input type="text" name="spouse_name" value="<?= htmlspecialchars($r['spouse_name'] ?? '') ?>">
          </div>
          <div class="field span-all" style="margin-top:1px;"></div>
          <div class="field">
            <label>Guardian Name</label>
            <input type="text" name="guardian_name" value="<?= htmlspecialchars($r['guardian_name'] ?? '') ?>" placeholder="Full name">
          </div>
          <div class="field">
            <label>Relationship</label>
            <input type="text" name="child_relation" value="<?= htmlspecialchars($r['guardian_rel'] ?? '') ?>" placeholder="e.g. Mother, Father">
          </div>
          <div class="field">
            <label>Guardian Number</label>
            
    <input
    type="tel"
    name="guardian_number"
    value="<?= htmlspecialchars($r['guardian_number'] ?? '') ?>"
    placeholder="09XX XXX XXXX"
>

          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-title">ID Registration Details</div>
        <div class="form-grid cols-4">
          <div class="field">
            <label>PWD ID Number</label>
            <input type="text" name="pwd_id" value="<?= htmlspecialchars($r['pwdid_num'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Control Number</label>
            <input type="text" name="control_id" value="<?= htmlspecialchars($r['control_num'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Date Issued</label>
            <input type="date" name="date_issued" value="<?= htmlspecialchars($r['idissue_date'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Expiration Date</label>
            <input type="date" name="expiration_date" value="<?= htmlspecialchars($r['idexpiration_date'] ?? '') ?>">
          </div>
        </div>
      </div>

      <div class="form-footer">
        <button class="btn btn-archive" type="button" onclick="confirmArchive(<?= $r['ID'] ?>)">Archive</button>
        <div style="display:flex;gap:10px;">
          <button class="btn btn-cancel" type="button" onclick="window.location.href='resident.php'">Cancel</button>
          <button class="btn btn-save" type="submit">Save Changes</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php if ($show_edit_success): ?>
<div id="editSuccessModal" style="display:flex; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:36px 32px; max-width:420px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15); text-align:center;">
    <div style="width:56px; height:56px; background:#EAF9EE; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#38C966" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; color:#1c0202; margin-bottom:8px;">Changes Saved!</h2>
    <p style="font-size:13.5px; color:rgba(28,2,2,0.55); margin-bottom:28px;">The resident's information has been updated successfully.</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="window.location.href='resident.php'" style="padding:10px 22px; border-radius:10px; border:1.5px solid rgba(0,0,0,0.1); background:#fff; font-family:inherit; font-size:13.5px; font-weight:700; color:rgba(28,2,2,0.6); cursor:pointer;">View Residents</button>
      <button onclick="document.getElementById('editSuccessModal').style.display='none'" style="padding:10px 22px; border-radius:10px; border:none; background:#A84040; color:#fff; font-family:inherit; font-size:13.5px; font-weight:700; cursor:pointer; box-shadow:0 3px 10px rgba(168,64,64,0.3);">Continue Editing</button>
    </div>
  </div>
</div>
<?php endif; ?>

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
  event.currentTarget.classList.toggle("open");
  document.getElementById(id).classList.toggle("open");
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
