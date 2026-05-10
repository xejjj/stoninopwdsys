<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PWD Registration Form</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/selfregistration.css" />
</head>
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
      <img src = "assets/exclamark.png" width="75" style="margin-top: 10px;">
    </div>
    <div class="info-content">
      <div class="info-title">Important Information</div>
      <div class="info-desc">All information provided will be reviewed by barangay staff. Please ensure all details are accurate. Your registration will be processed within 3-5 business days.</div>
    </div>
  </div>

  <form action="sqlFormaction.php" method="POST" enctype="multipart/form-data">

     <!-- success and error FOR NOW, will replace with popups later -->
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
          <input type="text" name="first_name">
        </div>
        <div class="field">
          <label>Middle Name <span class="req">*</span></label>
          <input type="text" name="middle_name">
        </div>
        <div class="field">
          <label>Last Name <span class="req">*</span></label>
          <input type="text" name="last_name">
        </div>
        <div class="field">
          <label>Civil Status <span class="req">*</span></label>
          <select name="civil_status">
            <option value=""></option>
            <option>Single</option>
            <option>Married</option>
            <option>Widowed</option>
            <option>Separated</option>
          </select>
        </div>
        <div class="field">
          <label>Date of Birth <span class="req">*</span></label>
          <input type="date" name="dob">
        </div>
        <div class="field">
          <label>Place of Birth <span class="req">*</span></label>
          <input type="text" name="pob">
        </div>
        <div class="field">
          <label>Age <span class="req">*</span></label>
          <input type="number" name="age">
        </div>
        <div class="field">
          <label>Sex <span class="req">*</span></label>
          <select name="sex">
            <option value=""></option>
            <option>Male</option>
            <option>Female</option>
          </select>
        </div>
        <div class="field">
          <label>Upload Profile <span class="req">*</span></label>
          <label class="file-input-wrap">
            <svg viewBox="0 0 24 24"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
            <span class="file-placeholder"></span>
            <input type="file" name="profile_pic" accept="image/*">
          </label>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-title">Contact and Address Information</div>
      <div class="form-grid cols-4">
        <div class="field">
          <label>Contact Number <span class="req">*</span></label>
          <input type="text" name="contact_number">
        </div>
        <div class="field">
          <label>Emergency Contact Name <span class="req">*</span></label>
          <input type="text" name="emergency_name">
        </div>
        <div class="field">
          <label>Emergency Contact Number <span class="req">*</span></label>
          <input type="text" name="emergency_number">
        </div>
        <div class="field">
          <label>Relationship with Emergency Contact <span class="req">*</span></label>
          <input type="text" name="emergency_relation">
        </div>
        <div class="field">
          <label>Email/Facebook Account <span class="req">*</span></label>
          <input type="text" name="account_name">
        </div>
        <div class="field">
          <label>House No. and Street <span class="req">*</span></label>
          <input type="text" name="address">
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-title">Disability Information</div>
      <div class="form-grid cols-2">
        <div class="field span-all">
          <label>Disability Types <span class="req">*</span></label>
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
      
      </div>
    </div>

    <div class="card">
      <div class="card-title">For CWD Only</div>
      <div class="form-grid cols-3">
        <div class="field">
          <label>Parent/Guardian Name <span class="req">*</span></label>
          <input type="text" name="guardian_name">
        </div>
        <div class="field">
          <label>Parent/Guardian Number <span class="req">*</span></label>
          <input type="text" name="guardian_number">
        </div>
        <div class="field">
          <label>Relationship with Child <span class="req">*</span></label>
          <input type="text" name="child_relation">
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

    <div class="submit-wrapper">
      <button class="btn btn-save btn-large" type="submit" href = "dashboard.php">Submit Registration</button>
    </div>

    <div class="footer-note">
      By submitting this form, you agree to the processing of your personal data for barangay PWD registration purposes.<br>
      For assistance, please contact the barangay office during business hours.
    </div>
    
  </form>
</div>

</body>
</html>