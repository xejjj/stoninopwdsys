<?php 
session_start(); 

// Fetch saved form data and clear it from session
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PWD Registration Form</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/selfregistration.css" />
</head>
<script>
function previewProfile(input) {
  const label = document.getElementById("profileFileLabel");
  const preview = document.getElementById("profilePreview");

  if (input.files && input.files[0]) {
    const file = input.files[0];

    label.textContent = file.name;

    const reader = new FileReader();

    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.style.display = "block";
    };

    reader.readAsDataURL(file);
  } else {
    label.textContent = "Choose profile picture…";
    preview.src = "";
    preview.style.display = "none";
  }
}
</script>
<body>

<div class="top-nav">
  <a href="index.php" class="btn-return">
    <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
    Return to Home
  </a>
</div>

<div class="registration-container">
  
  <div class="header-section">
    <div class="logo-box">
      <svg viewBox="0 0 24 24" fill="white"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
    </div>
    <h1 class="page-title">PWD Registration Form</h1>
  </div>

  <div class="info-box">
    <div class="info-icon">
      <img src="assets/exclamark.png" width="75" style="margin-top: 10px;">
    </div>
    <div class="info-content">
      <div class="info-title">Important Information</div>
      <div class="info-desc">All information provided will be reviewed by barangay staff. Please ensure all details are accurate. Your registration will be processed within 3-5 business days.</div>
    </div>
  </div>

  <form action="func/processSelfReg.php" method="POST" enctype="multipart/form-data">

     <?php if (isset($_SESSION["reg_success"])): ?>
      <div class="alert alert-success">
        ✅ <?= htmlspecialchars($_SESSION["reg_success"]) ?>
      </div>
    <?php unset($_SESSION["reg_success"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["reg_error"])): ?>
      <div class="alert alert-error">
          ⚠️ <?= htmlspecialchars($_SESSION["reg_error"]) ?>
      </div>
    <?php unset($_SESSION["reg_error"]); ?>
    <?php endif; ?>

    <div class="card">
      <div class="card-title">Personal Information</div>
      <div class="form-grid cols-4">
        <div class="field">
          <label>First Name <span class="req">*</span></label>
          <input type="text" name="first_name" placeholder="e.g. Juan" value="<?= htmlspecialchars($form_data['first_name'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Middle Name <span class="req">*</span></label>
          <input type="text" name="middle_name" placeholder="e.g. Dela" value="<?= htmlspecialchars($form_data['middle_name'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Last Name <span class="req">*</span></label>
          <input type="text" name="last_name" placeholder="e.g. Cruz" value="<?= htmlspecialchars($form_data['last_name'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Civil Status <span class="req">*</span></label>
          <select name="civil_status">
            <option value=""></option>
            <option <?= (isset($form_data['civil_status']) && $form_data['civil_status'] == 'Single') ? 'selected' : '' ?>>Single</option>
            <option <?= (isset($form_data['civil_status']) && $form_data['civil_status'] == 'Married') ? 'selected' : '' ?>>Married</option>
            <option <?= (isset($form_data['civil_status']) && $form_data['civil_status'] == 'Widowed') ? 'selected' : '' ?>>Widowed</option>
            <option <?= (isset($form_data['civil_status']) && $form_data['civil_status'] == 'Separated') ? 'selected' : '' ?>>Separated</option>
          </select>
        </div>
        <div class="field">
          <label>Date of Birth <span class="req">*</span></label>
          <input type="date" name="dob" value="<?= htmlspecialchars($form_data['dob'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Place of Birth <span class="req">*</span></label>
          <input type="text" name="pob" placeholder="e.g. Tondo General Hospital" value="<?= htmlspecialchars($form_data['pob'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Age <span class="req">*</span></label>
          <input type="number" name="age" placeholder="0" min="0" max="130" value="<?= htmlspecialchars($form_data['age'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Sex <span class="req">*</span></label>
          <select name="sex">
            <option value=""></option>
            <option <?= (isset($form_data['sex']) && strtolower($form_data['sex']) == 'male') ? 'selected' : '' ?>>Male</option>
            <option <?= (isset($form_data['sex']) && strtolower($form_data['sex']) == 'female') ? 'selected' : '' ?>>Female</option>
          </select>
        </div>
        <div class="field">
  <label>Upload Profile <span class="req">*</span></label>

  <img id="profilePreview"
       src=""
       alt="Profile Preview"
       style="display:none;width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:8px;">

  <label class="file-input-wrap">
    <svg viewBox="0 0 24 24">
      <polyline points="16 16 12 12 8 16"/>
      <line x1="12" y1="12" x2="12" y2="21"/>
      <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
    </svg>

    <span id="profileFileLabel">Choose profile picture…</span>

    <input type="file"
           name="profile_pic"
           accept="image/*"
           onchange="previewProfile(this)">
  </label>
</div>
      </div>
    </div>

    <div class="card">
      <div class="card-title">Contact and Address Information</div>
      <div class="form-grid cols-4">
        <div class="field">
          <label>Contact Number <span class="req">*</span></label>
          <input type="text" name="contact_number" placeholder="09XX XXX XXXX" value="<?= htmlspecialchars($form_data['contact_number'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Emergency Contact Name <span class="req">*</span></label>
          <input type="text" name="emergency_name" placeholder="Full name" value="<?= htmlspecialchars($form_data['emergency_name'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Emergency Contact Number <span class="req">*</span></label>
          <input type="text" name="emergency_number" placeholder="09XX XXX XXXX" value="<?= htmlspecialchars($form_data['emergency_number'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Relationship with Emergency Contact <span class="req">*</span></label>
          <input type="text" name="emergency_relation" placeholder="e.g. Parent, Sibling" value="<?= htmlspecialchars($form_data['emergency_relation'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Email/Facebook Account <span class="req">*</span></label>
          <input type="text" name="account_name" placeholder="e.g. kevincalon123@gmail.com" value="<?= htmlspecialchars($form_data['account_name'] ?? '') ?>">
        </div>
        <div class="field">
          <label>House No. and Street <span class="req">*</span></label>
          <input type="text" name="address" placeholder="e.g. 12 Sampaguita St." value="<?= htmlspecialchars($form_data['address'] ?? '') ?>">
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-title">Disability Information</div>
      <div class="form-grid cols-2">
        <div class="field span-all">
          <label>Disability Types <span class="req">*</span></label>
          <div class="checkbox-grid">
            <?php $disabilities = $form_data['disablity_type'] ?? []; ?>
            <label class="checkbox-label"><input type="checkbox" name="disablity_type[]" value="Cognitive" <?= in_array('Cognitive', $disabilities) ? 'checked' : '' ?>> Cognitive</label>
            <label class="checkbox-label"><input type="checkbox" name="disablity_type[]" value="Visual" <?= in_array('Visual', $disabilities) ? 'checked' : '' ?>> Visual</label>
            <label class="checkbox-label"><input type="checkbox" name="disablity_type[]" value="Physical" <?= in_array('Physical', $disabilities) ? 'checked' : '' ?>> Physical</label>
            <label class="checkbox-label"><input type="checkbox" name="disablity_type[]" value="Auditory" <?= in_array('Auditory', $disabilities) ? 'checked' : '' ?>> Auditory</label>
            <label class="checkbox-label"><input type="checkbox" name="disablity_type[]" value="Speech" <?= in_array('Speech', $disabilities) ? 'checked' : '' ?>> Speech</label>
            <label class="checkbox-label"><input type="checkbox" name="disablity_type[]" value="Psychosocial" <?= in_array('Psychosocial', $disabilities) ? 'checked' : '' ?>> Psychosocial</label>
            <label class="checkbox-label"><input type="checkbox" name="disablity_type[]" value="Others" <?= in_array('Others', $disabilities) ? 'checked' : '' ?>> Others</label>
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
    <input type="file" name="med_cert" accept=".pdf,.jpg,.jpeg,.png" onchange="document.getElementById('medCertLabel').textContent = this.files[0]?.name || 'Upload medical certificate…'">
  </label>
</div>
      </div>
      
    </div>

    <div class="card">
      <div class="card-title">For CWD Only</div>
      <div class="form-grid cols-3">
        <div class="field">
          <label>Parent/Guardian Name <span class="req">*</span></label>
          <input type="text" name="guardian_name" placeholder="Full name" value="<?= htmlspecialchars($form_data['guardian_name'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Parent/Guardian Number <span class="req">*</span></label>
          <input type="text" name="guardian_number" placeholder="09XX XXX XXXX" value="<?= htmlspecialchars($form_data['guardian_number'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Relationship with Child <span class="req">*</span></label>
          <input type="text" name="child_relation" placeholder="e.g. Mother, Father" value="<?= htmlspecialchars($form_data['child_relation'] ?? '') ?>">
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-title">Family Information</div>
      <div class="form-grid cols-3">
        <div class="field">
          <label>Father Name<span class="req">*</span></label>
          <input type="text" name="father_name" placeholder="Full Name" value="<?= htmlspecialchars($form_data['father_name'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Mother Name<span class="req">*</span></label>
          <input type="text" name="mother_name" placeholder="Full Name" value="<?= htmlspecialchars($form_data['mother_name'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Spouse Name<span class="req">*</span></label>
          <input type="text" name="spouse_name" placeholder="Full Name" value="<?= htmlspecialchars($form_data['spouse_name'] ?? '') ?>">
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-title">ID Registration Details</div>
      <div class="form-grid cols-4">
        <div class="field">
          <label>PWD ID Number <span class="req">*</span></label>
          <input type="text" name="pwd_id" placeholder="XXXXXXXXXXXX" value="<?= htmlspecialchars($form_data['pwd_id'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Control Number <span class="req">*</span></label>
          <input type="text" name="control_id" placeholder="XXXXXXXXXXXX" value="<?= htmlspecialchars($form_data['control_id'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Date Issued <span class="req">*</span></label>
          <input type="date" name="date_issued" value="<?= htmlspecialchars($form_data['date_issued'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Expiration Date <span class="req">*</span></label>
          <input type="date" name="expiration_date" value="<?= htmlspecialchars($form_data['expiration_date'] ?? '') ?>">
        </div>
      </div>
    </div>

    <div class="submit-wrapper">
      <button class="btn btn-save btn-large" type="submit">Submit Registration</button>
    </div>

    <div class="footer-note">
      By submitting this form, you agree to the processing of your personal data for barangay PWD registration purposes.<br>
      For assistance, please contact the barangay office during business hours.
    </div>
    
  </form>
</div>

</body>
</html>