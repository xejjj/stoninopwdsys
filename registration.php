<?php 
require_once("func/auth.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PWD/CWD Hub – New Application</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/registration.css" />
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
        <a class="nav-sub-item" href="resident.php">View Residents</a>
        <a class="nav-sub-item active" href="registration.php">New Application</a>
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

<!-- ─── MAIN ─────────────────────────────────────── -->
<div class="main">
  <header class="topbar">
    <h1 class="topbar-title">New Application</h1>
  </header>

  <div class="content">

    <!-- Error alert only (success is now a modal) -->
    <?php if (isset($_SESSION["reg_error"])): ?>
      <div class="alert alert-error">⚠️ <?= htmlspecialchars($_SESSION["reg_error"]) ?></div>
      <?php unset($_SESSION["reg_error"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["duplicate_error"])): ?>

<div class="modal-overlay" id="duplicateModal">

    <div class="modal-box">

        <div class="modal-icon delete">
            !
        </div>

        <h2 class="modal-title">
            Duplicate Resident Detected
        </h2>

        <p class="modal-description">
            <?= htmlspecialchars(
                $_SESSION["duplicate_error"]
            ) ?>
        </p>

        <div class="modal-actions">

            <button
                type="button"
                class="modal-btn delete"
                onclick="closeDuplicateModal()"
            >
                OK
            </button>

        </div>

    </div>

</div>

<?php unset($_SESSION["duplicate_error"]); ?>
<?php endif; ?>

    <form action="func/processRegistration.php" method="POST" enctype="multipart/form-data" id="regForm">

      <!-- Personal Information -->
      <div class="card">
        <div class="card-title">Personal Information</div>
        <div class="form-grid">
          <div class="field">
            <label>First Name</label>
            <input type="text" name="first_name" placeholder="e.g. Juan">
          </div>
          <div class="field">
            <label>Middle Name</label>
            <input type="text" name="middle_name" placeholder="e.g. Dela">
          </div>
          <div class="field">
            <label>Last Name</label>
            <input type="text" name="last_name" placeholder="e.g. Cruz">
          </div>
          <div class="field">
            <label>Civil Status</label>
            <select name="civil_status">
              <option value="">Select status</option>
              <option>Single</option>
              <option>Married</option>
              <option>Widowed</option>
              <option>Separated</option>
            </select>
          </div>
          <div class="field">
            <label>Date of Birth</label>
            <input type="date" name="dob">
          </div>
          <div class="field">
            <label>Place of Birth</label>
            <input type="text" name="pob" placeholder="e.g. Tondo General Hospital">
          </div>
          <div class="field">
            <label>Age</label>
            <input type="number" name="age" placeholder="0" min="0" max="130">
          </div>
          <div class="field">
            <label>Gender</label>
            <select name="Gender">
              <option value="">Select Gender</option>
              <option>Male</option>
              <option>Female</option>
            </select>
          </div>
          <div class="field">
            <label>Upload Profile</label>
            <label class="file-input-wrap">
              <svg viewBox="0 0 24 24"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
              <span id="fileLabel">Choose file…</span>
              <input type="file" name="profile_pic" accept="image/*" onchange="updateFileLabel(this)">
            </label>
          </div>
        </div>
      </div>

      <!-- Contact and Address Information -->
      <div class="card">
        <div class="card-title">Contact and Address Information</div>
        <div class="form-grid">
          <div class="field">
            <label>Contact Number</label>
            <input
  type="text"
  name="contact_number"
  maxlength="10"
  inputmode="numeric"
  placeholder="+63 | 9XXXXXXXXX"
  value="<?= htmlspecialchars($form_data['contact_number'] ?? '') ?>"
  oninput="this.value=this.value.replace(/[^0-9]/g,'')"
>
          </div>
          <div class="field">
            <label>Email/Facebook Account</label>
            <input type="text" name="account_name" placeholder="e.g. kevincalon123@gmail.com">
          </div>
          <div class="field span-all" style="margin-top:1px;"></div>
          <div class="field">
            <label>Emergency Contact Name</label>
            <input type="text" name="emergency_name" placeholder="Full name">
          </div>
          <div class="field">
            <label>Emergency Contact Number</label>
            <input
  type="text"
  name="emergency_number"
  maxlength="10"
  inputmode="numeric"
  placeholder="+63 | 9XXXXXXXXX"
  value="<?= htmlspecialchars($form_data['emergency_number'] ?? '') ?>"
  oninput="this.value=this.value.replace(/[^0-9]/g,'')"
>
          </div>
          <div class="field">
            <label>Relationship with Emergency Contact</label>
            <select name="emergency_relation" onchange="toggleEmergencySpecify(this)">
            <option value="">Select relationship</option>
            <option value="Father">Father</option>
            <option value="Mother">Mother</option>
            <option value="Partner">Partner</option>
            <option value="Relative">Relative</option>
            <option value="Others">Others (Please specify)</option>
            </select>
          </div>
<div class="field" id="emergencySpecifyField" style="display:none;">
  <label>Specify Relationship</label>
  <input type="text" name="emergency_relation_specify" placeholder="e.g. Aunt, Uncle, Friend">
</div>
          <div class="field span-all" style="margin-top:1px;"></div>
          <div class="field span-2">
            <label>Full Address (House No. and Street)</label>
            <input type="text" name="address" placeholder="e.g. 12 Sampaguita St.">
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
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Physical"> Physical</label>
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Auditory"> Auditory</label>
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Speech"> Speech</label>
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Psychosocial"> Psychosocial</label>
              <label class="checkbox-label"><input type="checkbox" name="disability_type[]" value="Others"> Others</label>
            </div>
          </div>
          <div class="field span-all">
            <label>Remarks</label>
            <textarea name="remarks" rows="3" placeholder="Additional notes about the disability..." style="resize: none;"></textarea>
          </div>
          <div class="field span-all">
  <label>Medical Certificate</label>
  <label class="file-input-wrap">
    <svg viewBox="0 0 24 24"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
    <span id="medCertLabel">Upload medical certificate…</span>
    <input type="file" name="med_cert" accept=".pdf,.jpg,.jpeg,.png" id="medCertInput" onchange="document.getElementById('medCertLabel').textContent = this.files[0]?.name || 'Upload medical certificate…'">
  </label>
</div>
        </div>
      </div>


      <div class="card">
  <div class="card-title">Family / Guardian Information</div>

  <div class="form-grid cols-3">
    <div class="field">
      <label>Father Name</label>
      <input type="text" name="father_name" placeholder="Full Name">
    </div>

    <div class="field">
      <label>Mother Name</label>
      <input type="text" name="mother_name" placeholder="Full Name">
    </div>

    <div class="field">
      <label>Spouse Name</label>
      <input type="text" name="spouse_name" placeholder="Full Name">
    </div>
  <div class="field span-all" style="margin-top:1px;"></div>
    <div class="field">
      <label>Guardian Name</label>
      <input type="text" name="guardian_name" placeholder="Full Name">
    </div>

    <div class="field">
      <label>Guardian Number</label>
      <input
  type="text"
  name="guardian_number"
  maxlength="10"
  inputmode="numeric"
  placeholder="+63 | 9XXXXXXXXX"
  value="<?= htmlspecialchars($form_data['guardian_number'] ?? '') ?>"
  oninput="this.value=this.value.replace(/[^0-9]/g,'')"
>
    </div>

    <div class="field">
  <label>Guardian Relationship</label>
  <select name="child_relation" onchange="toggleRelativeSpecify(this)">
  <option value="">Select relationship</option>
  <option value="Father">Father</option>
  <option value="Mother">Mother</option>
  <option value="Partner">Partner</option>
  <option value="Relative">Relative</option>
  <option value="Others">Others (Please specify)</option>
</select>
  </div>
<div class="field" id="relativeSpecifyField" style="display:none;">
  <label>Specify Relationship</label>
  <input type="text" name="child_relation_specify" placeholder="e.g. Aunt, Uncle, Legal Guardian">
</div>
  </div>
</div>

      <!-- ID Registration Details -->
      <div class="card">
        <div class="card-title">ID Application Details</div>
        <div class="form-grid cols-4">
          <div class="field">
            <label>PWD ID Number</label>
            <input type="text" name="pwd_id" placeholder="XXXXXXXXXXXX">
          </div>
          <div class="field">
            <label>Control Number</label>
            <input type="text" name="control_id" placeholder="XXXXXXXXXXXX">
          </div>
          <div class="field">
            <label>Date Issued</label>
            <input type="date" name="date_issued">
          </div>
          <div class="field">
            <label>Expiration Date</label>
            <input type="date" name="expiration_date">
          </div>
          <div class="field">
            <label>PWD ID Card</label>
            <label class="file-input-wrap">
            <svg viewBox="0 0 24 24"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
            <span id="pwdCardLabel">Upload PWD ID Card</span>
            <input type="file" name="pwd_id_card" accept=".pdf,.jpg,.jpeg,.png" id="pwdCardInput"
            onchange="document.getElementById('pwdCardLabel').textContent = this.files[0]?.name || 'Upload PWD ID Card'">
  </label>
</div>
          <div class="field">
            <label>Valid ID</label>
            <label class="file-input-wrap">
            <svg viewBox="0 0 24 24"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
            <span id="validIdLabel">Upload Valid ID</span>
            <input type="file" name="valid_id" accept=".pdf,.jpg,.jpeg,.png" id="validIdInput"
            onchange="document.getElementById('validIdLabel').textContent = this.files[0]?.name || 'Upload Valid ID'">
  </label>
</div>
        </div>
      </div>

      <!-- Actions -->
      <div class="form-footer">
        <button class="btn btn-cancel" type="button" onclick="window.location.href='resident.php'">Cancel</button>
        <button class="btn btn-save" type="button" onclick="validateAndSubmit()">Save</button>
      </div>

    </form>
  </div><!-- /content -->
</div><!-- /main -->

<!-- ── Success Modal ── -->
<?php if (isset($_SESSION["reg_success"])): ?>
<div id="successModal" style="display:flex; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <?php unset($_SESSION["reg_success"]); ?>
  <div style="background:#fff; border-radius:16px; padding:36px 32px; max-width:420px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15); text-align:center;">
    <!-- Checkmark icon -->
    <div style="width:56px; height:56px; background:#EAF9EE; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#38C966" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; color:#1c0202; margin-bottom:8px;">Resident Registered!</h2>
    <p style="font-size:13.5px; color:rgba(28,2,2,0.55); margin-bottom:28px;">The Application was saved successfully. What would you like to do next?</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="window.location.href='resident.php'" style="padding:10px 22px; border-radius:10px; border:1.5px solid rgba(0,0,0,0.1); background:#fff; font-family:inherit; font-size:13.5px; font-weight:700; color:rgba(28,2,2,0.6); cursor:pointer;">
        View Residents
      </button>
      <button onclick="document.getElementById('successModal').style.display='none'; document.getElementById('regForm').reset(); document.getElementById('fileLabel').textContent='Choose file…';" style="padding:10px 22px; border-radius:10px; border:none; background:#A84040; color:#fff; font-family:inherit; font-size:13.5px; font-weight:700; cursor:pointer; box-shadow:0 3px 10px rgba(168,64,64,0.3);">
        Add Another
      </button>
    </div>
  </div>
</div>
<?php endif; ?>

<script>
  // ── AUTO-COMPUTE AGE ──────────────────────────────────────────────────
document.querySelector('input[name="dob"]').addEventListener('change', function () {
  const dob = new Date(this.value);
  if (isNaN(dob)) return;
  const today = new Date();
  let age = today.getFullYear() - dob.getFullYear();
  const m = today.getMonth() - dob.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
  document.querySelector('input[name="age"]').value = age >= 0 ? age : '';
});

// ── CIVIL STATUS → DISABLE SPOUSE ─────────────────────────────────────
document.querySelector('select[name="civil_status"]').addEventListener('change', function () {
  toggleSpouse(this.value);
});
function toggleSpouse(val) {
  const spouseInput = document.querySelector('input[name="spouse_name"]');
  if (!spouseInput) return;
  if (val === 'Single') {
    spouseInput.disabled = true;
    spouseInput.value = '';
    spouseInput.style.background = '#e8e8e8';
    spouseInput.style.cursor = 'not-allowed';
    spouseInput.style.color = 'rgba(28,2,2,0.3)';
  } else {
    spouseInput.disabled = false;
    spouseInput.style.background = '';
    spouseInput.style.cursor = '';
    spouseInput.style.color = '';
  }
}
// Run on load in case the page reloads with civil_status pre-selected
toggleSpouse(document.querySelector('select[name="civil_status"]').value);

// ── GUARDIAN RELATIONSHIP DROPDOWN ────────────────────────────────────
function validateAndSubmit() {
  // Clear old errors
  document.querySelectorAll('.field-error').forEach(el => el.remove());
  document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

  let valid = true;

  // ── Text / select fields ──
  const requiredFields = [
    { name: 'first_name',     label: 'First Name' },
    { name: 'last_name',      label: 'Last Name' },
    { name: 'dob',            label: 'Date of Birth' },
    { name: 'contact_number', label: 'Contact Number' },
    { name: 'address',        label: 'Full Address' },
  ];

  requiredFields.forEach(function (f) {
    const el = document.querySelector('[name="' + f.name + '"]');
    if (!el) return;
    let val = el.value.trim();
    
    // Phone number validation: must be exactly 10 digits
    if (f.name.includes('contact_number') || f.name.includes('emergency_number') || f.name.includes('guardian_number')) {
      if (val.length !== 10 || !/^\d{10}$/.test(val)) {
        valid = false;
        el.classList.add('input-error');
        const msg = document.createElement('span');
        msg.className = 'field-error';
        msg.textContent = f.label + ' must be exactly 10 digits.';
        el.parentNode.appendChild(msg);
        return;
      }
    }
    
    if (!val) {
      valid = false;
      el.classList.add('input-error');
      const msg = document.createElement('span');
      msg.className = 'field-error';
      msg.textContent = f.label + ' is required.';
      el.parentNode.appendChild(msg);
    }
  });

  // ── Phone number fields (emergency & guardian) - validate if filled ──
  ['emergency_number', 'guardian_number'].forEach(fieldName => {
    const el = document.querySelector('[name="' + fieldName + '"]');
    if (!el) return;
    const val = el.value.trim();
    if (val && (val.length !== 10 || !/^\d{10}$/.test(val))) {
      valid = false;
      el.classList.add('input-error');
      const msg = document.createElement('span');
      msg.className = 'field-error';
      msg.textContent = 'Phone number must be exactly 10 digits.';
      el.parentNode.appendChild(msg);
    }
  });

  // ── Disability checkboxes ──
  const disabilityChecked = document.querySelectorAll('input[name="disability_type[]"]:checked').length > 0;
  if (!disabilityChecked) {
    valid = false;
    const grid = document.querySelector('.checkbox-grid');
    const msg = document.createElement('span');
    msg.className = 'field-error';
    msg.textContent = 'Please select at least one disability type.';
    grid.parentNode.appendChild(msg);
  }

  // ── File uploads ──
  [
    { input: document.getElementById('medCertInput'), labelId: 'medCertLabel' },
    { input: document.getElementById('pwdCardInput'), labelId: 'pwdCardLabel' },
    { input: document.getElementById('validIdInput'), labelId: 'validIdLabel' },
  ].forEach(({ input, labelId }) => {
    const wrap = input.closest('.file-input-wrap');
    const errId = labelId + 'Err';
    let errEl = document.getElementById(errId);

    if (!input.files.length) {
      valid = false;
      wrap.style.borderColor = '#dc2626';
      wrap.style.boxShadow = '0 0 0 3px rgba(220,38,38,0.12)';
      wrap.style.background = '#fff5f5';
      wrap.style.color = '#dc2626';
      if (!errEl) {
        errEl = document.createElement('span');
        errEl.id = errId;
        errEl.className = 'field-error';
        errEl.textContent = 'This field is required.';
        wrap.parentElement.appendChild(errEl);
      }
    } else {
      wrap.style.borderColor = '';
      wrap.style.boxShadow = '';
      wrap.style.background = '';
      wrap.style.color = '';
      if (errEl) errEl.remove();
    }
  });

  if (!valid) return;
  document.getElementById('regForm').submit();
}
function toggleRelativeSpecify(select) {
  const field = document.getElementById('relativeSpecifyField');
  const input = field.querySelector('input');
  if (select.value === 'Relative') {
    field.style.display = '';
    input.placeholder = 'e.g. Aunt, Uncle, Cousin';
    input.focus();
  } else if (select.value === 'Others') {
    field.style.display = '';
    input.placeholder = 'e.g. Legal Guardian, Foster Parent, Step Parent';
    input.focus();
  } else {
    field.style.display = 'none';
    input.value = '';
  }
}

function toggleEmergencySpecify(select) {
  const field = document.getElementById('emergencySpecifyField');
  const input = field.querySelector('input');
  if (select.value === 'Relative') {
    field.style.display = '';
    input.placeholder = 'e.g. Aunt, Uncle, Cousin';
    input.focus();
  } else if (select.value === 'Others') {
    field.style.display = '';
    input.placeholder = 'e.g. Friend, Neighbor, Caregiver';
    input.focus();
  } else {
    field.style.display = 'none';
    input.value = '';
  }
}

  // Clear red highlight as soon as user fills a field
  document.getElementById('regForm').addEventListener('input', function(e) {
    const el = e.target;
    if (el.classList.contains('input-error')) {
      el.classList.remove('input-error');
      const err = el.parentNode.querySelector('.field-error');
      if (err) err.remove();
    }
  });

  function closeDuplicateModal() {

    const modal =
        document.getElementById(
            "duplicateModal"
        );

    if (modal) {
        modal.style.display = "none";
    }
}

function toggleMenu(event, id) {
  event.preventDefault();
  event.currentTarget.classList.toggle("open");
  document.getElementById(id).classList.toggle("open");
}
function logout() {
  window.location.href = "func/logout.php";
}
function updateFileLabel(input) {
  const label = document.getElementById('fileLabel');
  label.textContent = input.files.length > 0 ? input.files[0].name : 'Choose file…';
}
</script>

</body>
</html>