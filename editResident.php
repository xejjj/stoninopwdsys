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
        <img src = "assets/overviewicon.png" width="20">
        Overview
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item open active" href="#" onclick="toggleMenu(event,'mgmt-sub')">
        <img src = "assets/users.png" width="20" >
        Management
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub" id="mgmt-sub">
        <a class="nav-sub-item" href="resident.php">View Residents</a>
        <a class="nav-sub-item active" href="registration.php">New Registration</a>
        <a class="nav-sub-item" href="#">Review Submissions</a>
      </div>
    </div>

    <div class="nav-group">
      <a class="nav-item" href="reports.php">
        <img src = "assets/reporticon.png" width="20" >
        Reports
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item open" href="#" onclick="toggleMenu(event,'system-sub')">
        <img src = "assets/settingicon.png" width="20" >
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

<!-- ─── MAIN ───────────────────────────────────────── -->
<div class="main">
  <header class="topbar">
    <h1 class="topbar-title">Edit Registration</h1>
  </header>

  <div class="content">

    <!-- Personal Information -->
    <div class="card">
      <div class="card-title">Personal Information</div>
      <div class="form-grid">
        <div class="field">
          <label>First Name <span class="req">*</span></label>
          <input type="text" placeholder="e.g. Juan">
        </div>
        <div class="field">
          <label>Middle Name <span class="req">*</span></label>
          <input type="text" placeholder="e.g. Dela">
        </div>
        <div class="field">
          <label>Last Name <span class="req">*</span></label>
          <input type="text" placeholder="e.g. Cruz">
        </div>
        <div class="field">
          <label>Civil Status <span class="req">*</span></label>
          <select>
            <option value="">Select status</option>
            <option>Single</option>
            <option>Married</option>
            <option>Widowed</option>
            <option>Separated</option>
          </select>
        </div>
        <div class="field">
          <label>Date of Birth <span class="req">*</span></label>
          <input type="date">
        </div>
        <div class="field">
          <label>Place of Birth <span class="req">*</span></label>
          <input type="text" placeholder="e.g. Tondo General Hospital">
        </div>
        <div class="field">
          <label>Age <span class="req">*</span></label>
          <input type="number" placeholder="0" min="0" max="130">
        </div>
        <div class="field">
          <label>Sex <span class="req">*</span></label>
          <select>
            <option value="">Select sex</option>
            <option>Male</option>
            <option>Female</option>
          </select>
        </div>
        <div class="field">
          <label>Upload Profile <span class="req">*</span></label>
          <label class="file-input-wrap">
            <svg viewBox="0 0 24 24"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
            Choose file…
            <input type="file" accept="image/*">
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
          <input type="tel" placeholder="09XX XXX XXXX">
        </div>
        <div class="field">
          <label>Emergency Contact Name <span class="req">*</span></label>
          <input type="text" placeholder="Full name">
        </div>
        <div class="field">
          <label>Emergency Contact Number <span class="req">*</span></label>
          <input type="tel" placeholder="09XX XXX XXXX">
        </div>
        <div class="field">
          <label>Relationship with Emergency Contact <span class="req">*</span></label>
          <input type="text" placeholder="e.g. Parent, Sibling">
        </div>
        <div class="field">
          <label>Email/Facebook Account <span class="req">*</span></label>
          <input type="text" name="account_name">
        </div>
        <div class="field span-2">
          <label>House No. and Street <span class="req">*</span></label>
          <input type="text" placeholder="e.g. 12 Sampaguita St.">
        </div>
      </div>
    </div>

    <!-- Disability Information -->
    <div class="card">
      <div class="card-title">Disability Information</div>
      <div class="form-grid cols-2">
        <div class="field span-all">
          <div class="checkbox-grid">
            <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Cognitive"> Cognitive</label>
            <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Visual"> Visual</label>
            <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Motor"> Motor</label>
            <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Auditory"> Auditory</label>
            <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Speech"> Speech</label>
            <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Psychosocial"> Psychosocial</label>
          </div>
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
          <label>Parent/Guardian Name <span class="req">*</span></label>
          <input type="text" placeholder="Full name">
        </div>
        <div class="field">
          <label>Parent/Guardian Number <span class="req">*</span></label>
          <input type="tel" placeholder="09XX XXX XXXX">
        </div>
        <div class="field">
          <label>Relationship with Child <span class="req">*</span></label>
          <input type="text" placeholder="e.g. Mother, Father">
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-title">Family Information</div>
      <div class="form-grid cols-3">
        <div class="field">
          <label>Father Name<span class="req">*</span></label>
          <input type="text" name="father_name">
        </div>
        <div class="field">
          <label>Mother Name<span class="req">*</span></label>
          <input type="text" name="mother_name">
        </div>
        <div class="field">
          <label>Spouse Name<span class="req">*</span></label>
          <input type="text" name="spouse_name">
        </div>
      </div>
    </div>

    <!-- ID Registration Details -->
    <div class="card">
      <div class="card-title">ID Registration Details</div>
      <div class="form-grid cols-4">
        <div class="field">
          <label>PWD ID Number <span class="req">*</span></label>
          <input type="text" name="pwd_id">
        </div>
        <div class="field">
          <label>Control Number <span class="req">*</span></label>
          <input type="text" name="control_id">
        </div>
        <div class="field">
          <label>Date Issued <span class="req">*</span></label>
          <input type="date" name="date_issued">
        </div>
        <div class="field">
          <label>Expiration Date <span class="req">*</span></label>
          <input type="date" name="expiration_date">
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="form-footer">
      <button class="btn btn-cancel" type="button">Cancel</button>
      <button class="btn btn-save" type="button">Save</button>
    </div>

  </div><!-- /content -->
</div><!-- /main -->

<script>
function toggleMenu(event, id) {
  event.preventDefault();

  const trigger = event.currentTarget;
  const submenu = document.getElementById(id);

  // Toggle open class on parent
  trigger.classList.toggle("open");

  // Toggle submenu
  submenu.classList.toggle("open");
}

function logout(){
  // Clear session or authentication tokens here if needed
  window.location.href = "login.php"; // Redirect to login page
}
</script>

</body>
</html>
