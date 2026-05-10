<?php
session_start();
require_once("db.php");

// ── Get resident by ID ────────────────────────────────
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
if ($id <= 0) {
    header("Location: resident.php");
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT * FROM residents WHERE ID = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$r = mysqli_fetch_assoc($result);

if (!$r) {
    header("Location: resident.php");
    exit();
}

// Pre-split disability types for checkbox checking
$saved_disabilities = array_map('trim', explode(",", $r['disablity_type'] ?? ""));
function isChecked($val, $arr) {
    return in_array($val, $arr) ? "checked" : "";
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
      <a class="nav-item open active" href="#" onclick="toggleMenu(event,'mgmt-sub')">
        <img src="assets/users.png" width="20">
        Management
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub open" id="mgmt-sub">
        <a class="nav-sub-item active" href="resident.php">View Residents</a>
        <a class="nav-sub-item" href="registration.php">New Registration</a>
        <a class="nav-sub-item" href="#">Review Submissions</a>
      </div>
    </div>

    <div class="nav-group">
      <a class="nav-item" href="reports.php">
        <img src="assets/reporticon.png" width="20">
        Reports
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item" href="#" onclick="toggleMenu(event,'system-sub')">
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

<!-- ─── MAIN ─────────────────────────────────────── -->
<div class="main">
  <header class="topbar">
    <h1 class="topbar-title">Edit Registration</h1>
  </header>

  <div class="content">

    <!-- Alerts -->
    <?php if (isset($_SESSION["edit_success"])): ?>
      <div class="alert alert-success">✅ <?= htmlspecialchars($_SESSION["edit_success"]) ?></div>
      <?php unset($_SESSION["edit_success"]); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION["edit_error"])): ?>
      <div class="alert alert-error">⚠️ <?= htmlspecialchars($_SESSION["edit_error"]) ?></div>
      <?php unset($_SESSION["edit_error"]); ?>
    <?php endif; ?>

    <form action="processEdit.php" method="POST" enctype="multipart/form-data">
      <!-- Pass the resident ID -->
      <input type="hidden" name="resident_id" value="<?= $r['ID'] ?>">

      <!-- Personal Information -->
      <div class="card">
        <div class="card-title">Personal Information</div>
        <div class="form-grid">
          <div class="field">
            <label>First Name <span class="req">*</span></label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($r['first_name']) ?>" placeholder="e.g. Juan">
          </div>
          <div class="field">
            <label>Middle Name</label>
            <input type="text" name="middle_name" value="<?= htmlspecialchars($r['middle_name']) ?>" placeholder="e.g. Dela">
          </div>
          <div class="field">
            <label>Last Name <span class="req">*</span></label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($r['last_name']) ?>" placeholder="e.g. Cruz">
          </div>
          <div class="field">
            <label>Civil Status <span class="req">*</span></label>
            <select name="civil_status">
              <option value="">Select status</option>
              <?php foreach (["Single","Married","Widowed","Separated"] as $cs): ?>
                <option <?= $r['civil_status'] === $cs ? "selected" : "" ?>><?= $cs ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="field">
            <label>Date of Birth <span class="req">*</span></label>
            <input type="date" name="dob" value="<?= htmlspecialchars($r['birthdate']) ?>">
          </div>
          <div class="field">
            <label>Place of Birth <span class="req">*</span></label>
            <input type="text" name="pob" value="<?= htmlspecialchars($r['birthplace']) ?>" placeholder="e.g. Tondo General Hospital">
          </div>
          <div class="field">
            <label>Age <span class="req">*</span></label>
            <input type="number" name="age" value="<?= htmlspecialchars($r['age']) ?>" min="0" max="130">
          </div>
          <div class="field">
            <label>Sex <span class="req">*</span></label>
            <select name="sex">
              <option value="">Select sex</option>
              <option value="male"   <?= strtolower($r['sex']) === "male"   ? "selected" : "" ?>>Male</option>
              <option value="female" <?= strtolower($r['sex']) === "female" ? "selected" : "" ?>>Female</option>
            </select>
          </div>
          <div class="field">
            <label>Upload Profile</label>
            <?php if (!empty($r['profile'])): ?>
              <img src="<?= htmlspecialchars($r['profile']) ?>" alt="Profile" style="width:60px;height:60px;border-radius:50%;object-fit:cover;margin-bottom:8px;display:block;">
            <?php endif; ?>
            <label class="file-input-wrap">
              <svg viewBox="0 0 24 24"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
              Choose file…
              <input type="file" name="profile_pic" accept="image/*">
            </label>
          </div>
        </div>
      </div>

      <!-- Contact and Address Information -->
      <div class="card">
        <div class="card-title">Contact and Address Information</div>
        <div class="form-grid">
          <div class="field">
            <label>Contact Number <span class="req">*</span></label>
            <input type="tel" name="contact_number" value="<?= htmlspecialchars($r['contact_num']) ?>" placeholder="09XX XXX XXXX">
          </div>
          <div class="field">
            <label>Emergency Contact Name <span class="req">*</span></label>
            <input type="text" name="emergency_name" value="<?= htmlspecialchars($r['emergency_cont']) ?>" placeholder="Full name">
          </div>
          <div class="field">
            <label>Emergency Contact Number <span class="req">*</span></label>
            <input type="tel" name="emergency_number" value="<?= htmlspecialchars($r['emergency_cont_num']) ?>" placeholder="09XX XXX XXXX">
          </div>
          <div class="field">
            <label>Relationship with Emergency Contact <span class="req">*</span></label>
            <input type="text" name="emergency_relation" value="<?= htmlspecialchars($r['emergency_cont_rel']) ?>" placeholder="e.g. Parent, Sibling">
          </div>
          <div class="field">
            <label>Email/Facebook Account</label>
            <input type="text" name="account_name" value="<?= htmlspecialchars($r['socials']) ?>">
          </div>
          <div class="field span-2">
            <label>House No. and Street <span class="req">*</span></label>
            <input type="text" name="address" value="<?= htmlspecialchars($r['address']) ?>" placeholder="e.g. 12 Sampaguita St.">
          </div>
        </div>
      </div>

      <!-- Disability Information -->
      <div class="card">
        <div class="card-title">Disability Information</div>
        <div class="form-grid cols-2">
          <div class="field span-all">
            <div class="checkbox-grid">
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Cognitive" <?= isChecked("Cognitive", $saved_disabilities) ?>> Cognitive</label>
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Visual"    <?= isChecked("Visual",    $saved_disabilities) ?>> Visual</label>
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Physical"     <?= isChecked("Physical",     $saved_disabilities) ?>> Physical</label>
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Auditory"  <?= isChecked("Auditory",  $saved_disabilities) ?>> Auditory</label>
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Speech"    <?= isChecked("Speech",    $saved_disabilities) ?>> Speech</label>
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Psychosocial" <?= isChecked("Psychosocial", $saved_disabilities) ?>> Psychosocial</label>
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Others" <?= isChecked("Others", $saved_disabilities) ?>> Others</label>
            </div>
          </div>
          <div class="field span-all">
            <label>Remarks</label>
            <textarea name="remarks" rows="3" placeholder="Additional notes about the disability..."><?= htmlspecialchars($r['disability_remarks'] ?? "") ?></textarea>
          </div>
        </div>
      </div>

      <!-- For CWD Only -->
      <div class="card">
        <div class="card-title">For CWD Only</div>
        <div class="cwd-note">
          <svg style="width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          Fill this section only for Children with Disabilities (CWD)
        </div>
        <div class="form-grid cols-3">
          <div class="field">
            <label>Parent/Guardian Name</label>
            <input type="text" name="guardian_name" value="<?= htmlspecialchars($r['guardian_name']) ?>" placeholder="Full name">
          </div>
          <div class="field">
            <label>Parent/Guardian Number</label>
            <input type="tel" name="guardian_number" value="<?= htmlspecialchars($r['guardian_cont_num']) ?>" placeholder="09XX XXX XXXX">
          </div>
          <div class="field">
            <label>Relationship with Child</label>
            <input type="text" name="child_relation" value="<?= htmlspecialchars($r['guardian_rel']) ?>" placeholder="e.g. Mother, Father">
          </div>
        </div>
      </div>

      <!-- Family Information -->
      <div class="card">
        <div class="card-title">Family Information</div>
        <div class="form-grid cols-3">
          <div class="field">
            <label>Father Name</label>
            <input type="text" name="father_name" value="<?= htmlspecialchars($r['father_name']) ?>">
          </div>
          <div class="field">
            <label>Mother Name</label>
            <input type="text" name="mother_name" value="<?= htmlspecialchars($r['mother_name']) ?>">
          </div>
          <div class="field">
            <label>Spouse Name</label>
            <input type="text" name="spouse_name" value="<?= htmlspecialchars($r['spouse_name']) ?>">
          </div>
        </div>
      </div>

      <!-- ID Registration Details -->
      <div class="card">
        <div class="card-title">ID Registration Details</div>
        <div class="form-grid cols-4">
          <div class="field">
            <label>PWD ID Number <span class="req">*</span></label>
            <input type="text" name="pwd_id" value="<?= htmlspecialchars($r['pwdid_num']) ?>">
          </div>
          <div class="field">
            <label>Control Number <span class="req">*</span></label>
            <input type="text" name="control_id" value="<?= htmlspecialchars($r['control_num']) ?>">
          </div>
          <div class="field">
            <label>Date Issued <span class="req">*</span></label>
            <input type="date" name="date_issued" value="<?= htmlspecialchars($r['idissue_date']) ?>">
          </div>
          <div class="field">
            <label>Expiration Date <span class="req">*</span></label>
            <input type="date" name="expiration_date" value="<?= htmlspecialchars($r['idexpiration_date']) ?>">
          </div>
        </div>
      </div>

      <!-- Status -->
      <div class="card">
        <div class="card-title">Registration Status</div>
        <div class="form-grid cols-3">
          <div class="field">
            <label>Status <span class="req">*</span></label>
            <select name="status">
              <?php foreach (["Active","Pending","Expired"] as $st): ?>
                <option <?= ($r['status'] ?? "") === $st ? "selected" : "" ?>><?= $st ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="form-footer">
        <button class="btn btn-archive" type="button" onclick="confirmArchive(<?= $r['ID'] ?>)">
          Archive
        </button>
        <div style="display:flex;gap:10px;">
          <button class="btn btn-cancel" type="button" onclick="window.location.href='resident.php'">Cancel</button>
          <button class="btn btn-save" type="submit">Save Changes</button>
        </div>
      </div>

    </form>
  </div><!-- /content -->
</div><!-- /main -->

<!-- Archive confirmation modal -->
<div id="archiveModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
    <h2 style="font-size:18px; font-weight:800; margin-bottom:10px; color:#1c0202;">Archive this resident?</h2>
    <p style="font-size:13.5px; color:rgba(28,2,2,0.6); margin-bottom:24px;">This will move the resident to the archive. You can restore them from the Archive page.</p>
    <div style="display:flex; gap:10px; justify-content:flex-end;">
      <button onclick="document.getElementById('archiveModal').style.display='none'" style="padding:8px 18px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:none; font-family:inherit; font-weight:700; cursor:pointer;">Cancel</button>
      <form action="Processarchive.php" method="POST" style="margin:0;">
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
function logout() { window.location.href = "login.php"; }

function confirmArchive(id) {
  document.getElementById("archiveId").value = id;
  document.getElementById("archiveModal").style.display = "flex";
}
</script>
</body>
</html>