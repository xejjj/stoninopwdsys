<?php require_once("getDashboardData.php"); ?>
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
      <a class="nav-item active" href="dashboard.php">
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
        <a class="nav-sub-item" href="#">Review Submissions</a>
      </div>
    </div>

    <div class="nav-group">
      <a class="nav-item" href="reports.php">
        <img src="assets/reporticon.png" width="20" alt="Reports">
        Reports
      </a>
    </div>

    <div class="nav-group">
      <a class="nav-item open" href="#" onclick="toggleMenu(event,'system-sub')">
        <img src="assets/settingicon.png" width="20" alt="System">
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

<main class="main-content">
  <div class="container">
    
    <div class="action-bar">
      <button class="btn btn-back" onclick="history.back()">
        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        Back
      </button>
      <div class="actions-right">
        <button class="btn btn-edit">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
          Edit Profile
        </button>
        <button class="btn btn-archive">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
          Archive
        </button>
      </div>
    </div>

    <div class="profile-header">
      <div class="banner"></div>
      <div class="profile-content">
        <div class="profile-left">
          <img src="https://ui-avatars.com/api/?name=Adrian+Cruz&background=1c0202&color=fff&size=200" alt="Profile" class="avatar">
          <div class="profile-info">
            <div class="profile-name-row">
              <h1 class="profile-name">Adrian Cruz Santos</h1>
              <span class="badge badge-active"><span class="dot"></span> Active</span>
            </div>
            <div class="profile-id"># ID: QC-2026-000123</div>
          </div>
        </div>
        <div class="classification-box">
          <div class="classification-title">Disability Classification</div>
          <span class="badge badge-cognitive">Cognitive</span>
        </div>
      </div>
    </div>

    <div class="details-grid">
      
      <div class="card">
        <div class="card-header">
          <div class="icon-box bg-orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></div>
          <h3>Personal</h3>
        </div>
        <div class="info-list">
          <div class="info-group">
            <span class="info-label">Date of Birth</span>
            <span class="info-value">May 12, 2005 (20 years old)</span>
          </div>
          <div class="info-group">
            <span class="info-label">Sex</span>
            <span class="info-value">Male</span>
          </div>
          <div class="info-group">
            <span class="info-label">Civil Status</span>
            <span class="info-value">Single</span>
          </div>
        </div>
      </div>

      <div class="card card-contact">
        <div class="card-header">
          <div class="icon-box bg-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></div>
          <h3>Contact and Address</h3>
        </div>
        <div class="contact-grid">
          <div class="info-list">
            <div class="info-group">
              <span class="info-label">Mobile Number</span>
              <span class="info-value">09171234567</span>
            </div>
            <div class="info-group">
              <span class="info-label">House No. / Street</span>
              <span class="info-value">123 Mabini Street</span>
            </div>
          </div>
          <div class="info-list">
            <div class="info-group">
              <span class="info-label">Emergency Contact Name</span>
              <span class="info-value">Maria Santos</span>
            </div>
            <div class="info-group">
              <span class="info-label">Emergency Contact Number</span>
              <span class="info-value">09187654321</span>
            </div>
            <div class="info-group">
              <span class="info-label">Relationship</span>
              <span class="info-value">Mother</span>
            </div>
          </div>
          <div class="info-list">
            <div class="info-group">
              <span class="info-label">Parent/Guardian Name</span>
              <span class="info-value">N/A</span>
            </div>
            <div class="info-group">
              <span class="info-label">Parent/Guardian Contact Number</span>
              <span class="info-value">N/A</span>
            </div>
            <div class="info-group">
              <span class="info-label">Relationship with Child</span>
              <span class="info-value">N/A</span>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="icon-box bg-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg></div>
          <h3>Disability</h3>
        </div>
        <div class="info-list">
          <div class="info-group">
            <span class="info-label">Type of Disability/ies</span>
            <span class="info-value">Cognitive</span>
          </div>
          <div class="info-group">
            <span class="info-label">Cause of Disability</span>
            <span class="info-value">Congenital</span>
          </div>
          <div class="info-group">
            <span class="info-label">Medical Certificate</span>
            <span class="info-value"><a href="#" class="link">View <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a></span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="icon-box bg-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></div>
          <h3>ID Information</h3>
        </div>
        <div class="info-list">
          <div class="info-group">
            <span class="info-label">PWD ID Date Issued</span>
            <span class="info-value">March 18, 2026</span>
          </div>
          <div class="info-group">
            <span class="info-label">PWD ID Expiration Date</span>
            <span class="info-value">March 18, 2029</span>
          </div>
          <div class="info-group">
            <span class="info-label">ID Status</span>
            <span class="info-value"><span class="badge badge-active"><span class="dot"></span> Active</span></span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="icon-box bg-pink"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg></div>
          <h3>Renewal</h3>
        </div>
        <div class="info-list">
          <div class="info-group">
            <span class="info-label">Last Renewal Date</span>
            <span class="info-value">New Registration</span>
          </div>
          <div class="info-group">
            <span class="info-label">Next Renewal Due</span>
            <span class="info-value">January 10, 2029</span>
          </div>
          <div class="info-group">
            <span class="info-label">Renewal Status</span>
            <span class="info-value">Active</span>
          </div>
          <div class="info-group">
            <span class="info-label">Remarks</span>
            <span class="info-value"><a href="#" class="link">View <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a></span>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="icon-box bg-green"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></div>
          <h3>Other</h3>
        </div>
        <div class="info-list">
          <div class="info-group">
            <span class="info-label">Date Registered</span>
            <span class="info-value">March 18, 2026</span>
          </div>
          <div class="info-group">
            <span class="info-label">Registered By</span>
            <span class="info-value">Ana Lopez</span>
          </div>
          <div class="info-group">
            <span class="info-label">Last Updated By</span>
            <span class="info-value">March 18, 2026</span>
          </div>
          <div class="info-group">
            <span class="info-label">Remarks</span>
            <span class="info-value"><a href="#" class="link">View <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></a></span>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

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

  function logout() {
    window.location.href = "login.php"; 
  }
</script>

</body>
</html>