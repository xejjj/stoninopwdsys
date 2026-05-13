<?php require_once("func/getReviewData.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PWD/CWD Hub – Review</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/review.css" />
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
      <a class="nav-item active open" href="#" onclick="toggleMenu(event,'mgmt-sub')">
        <img src="assets/users.png" width="20" alt="Management">
        Management
        <svg class="chevron" viewBox="0 0 24 24"><polyline points="6 15 12 9 18 15"/></svg>
      </a>
      <div class="nav-sub open" id="mgmt-sub">
        <a class="nav-sub-item" href="resident.php">View Residents</a>
        <a class="nav-sub-item" href="registration.php">New Registration</a>
        <a class="nav-sub-item active" href="review.php">Review Submissions</a>
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

<main class="main-content">
  <div class="container">
    
    <div class="page-header">
      <button class="back-btn" onclick="history.back()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
      </button>
      <h1 class="page-title">Review Submissions</h1>
    </div>

    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-info">
          <div class="stat-label">UNDER REVIEW</div>
          <div class="stat-val text-orange"><?php echo $under_review_count; ?></div>
        </div>
        <div class="stat-icon bg-orange-light text-orange">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 16 14"></polyline></svg>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <div class="stat-label">REJECTED</div>
          <div class="stat-val text-red"><?php echo $rejected_count; ?></div>
        </div>
        <div class="stat-icon bg-red-light text-red">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <div class="stat-label">TOTAL SUBMISSIONS</div>
          <div class="stat-val text-blue"><?php echo $total_count; ?></div>
        </div>
        <div class="stat-icon bg-blue-light text-blue">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
        </div>
      </div>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar">
      <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
      </svg>
      <span class="filter-label">Filter:</span>
      
      <button class="filter-btn-review <?= (strtolower($filter) === 'under review') ? 'active' : '' ?>" onclick="window.location.href='review.php?filter=Under%20Review'">Under Review</button>
      <button class="filter-btn-correction <?= (strtolower($filter) === 'needs correction') ? 'active' : '' ?>" onclick="window.location.href='review.php?filter=Needs%20Correction'">Needs Correction</button>
      <button class="rejectedfilter-btn <?= (strtolower($filter) === 'rejected') ? 'active' : '' ?>" onclick="window.location.href='review.php?filter=Rejected'">Rejected</button>
      <button class="allfilter-btn <?= (strtolower($filter) === 'all') ? 'active' : '' ?>" onclick="window.location.href='review.php?filter=All'">All</button>
    </div>

    <?php 
    if (mysqli_num_rows($submissions) > 0):
        while ($resident = mysqli_fetch_assoc($submissions)): 
            $full_name = htmlspecialchars(trim(($resident['first_name'] ?? '') . ' ' . ($resident['middle_name'] ?? '') . ' ' . ($resident['last_name'] ?? '')));
            // Defaulting fallback status to Under Review now
            $status = htmlspecialchars($resident['status'] ?: 'Under Review');
            $status_cls = "badge-" . str_replace(' ', '-', strtolower($status)); 
            
            $dob_string = $resident['birthdate'] ?? null;
            $dob_formatted = 'N/A';
            if ($dob_string) {
                try {
                    $dob = new DateTime($dob_string);
                    $dob_formatted = $dob->format('F j, Y');
                } catch (Exception $e) { }
            }
    ?>
    <div class="submission-card">
      <div class="sub-header">
        <div class="sub-user">
          <div class="sub-avatar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          </div>
          <div class="sub-meta">
            <h2 class="sub-name"><?php echo $full_name; ?></h2>
            <div class="sub-status-row">
              <span class="badge <?php echo $status_cls; ?>"><?php echo $status; ?></span>
            </div>
          </div>
        </div>
        
        <?php if ($filter !== 'All'): ?>
        <div class="sub-actions">
          <?php if ($filter === 'Rejected'): ?>
            <button class="btn btn-reject" onclick="openDeleteModal(<?php echo $resident['ID']; ?>)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
              Delete
            </button>
          <?php elseif ($filter === 'Needs Correction'): ?>
          <button class="btn btn-approve" onclick="window.location.href='func/updateStatus.php?id=<?php echo $resident['ID']; ?>&status=Active'">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
            Approve
          </button>
          <!-- Triggers the new Hard Reject Modal instead of browser confirm -->
          <button class="btn btn-reject" onclick="openHardRejectModal(<?php echo $resident['ID']; ?>)">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-3"></path></svg>
            Reject
          </button>
          <?php else: ?>
            <button class="btn btn-approve" onclick="window.location.href='func/updateStatus.php?id=<?php echo $resident['ID']; ?>&status=Active'">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
              Approve
            </button>
            <button class="btn btn-reject" onclick="openRejectModal(<?php echo $resident['ID']; ?>)">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-3"></path></svg>
              Reject
            </button>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>

      <div class="sub-details">
        <div class="detail-row">
          <div class="detail-col">
            <div class="detail-item"><span>Date of Birth:</span> <?php echo htmlspecialchars($dob_formatted); ?></div>
            <div class="detail-item"><span>Sex:</span> <?php echo htmlspecialchars(ucfirst($resident['sex'] ?: 'N/A')); ?></div>
            <div class="detail-item"><span>Civil Status:</span> <?php echo htmlspecialchars($resident['civil_status'] ?: 'N/A'); ?></div>
          </div>
          <div class="detail-col">
            <div class="detail-item"><span>Disability:</span> <?php echo htmlspecialchars($resident['disablity_type'] ?: 'N/A'); ?></div>
            <div class="detail-item"><span>Cause of Disability:</span> <?php echo htmlspecialchars($resident['disability_remarks'] ?: 'N/A'); ?></div>
            <div class="detail-item"><span>Medical Certificate:</span> <a href="#" class="link">View</a></div>
          </div>
        </div>
        
        <hr class="divider">
        
        <div class="detail-row">
          <div class="detail-col">
            <div class="detail-item"><span>Mobile Number:</span> <?php echo htmlspecialchars($resident['contact_num'] ?: 'N/A'); ?></div>
            <div class="detail-item"><span>House No. / Street:</span> <?php echo htmlspecialchars($resident['address'] ?: 'N/A'); ?></div>
            <div class="detail-item"><span>Email/Facebook Account: </span> <?php echo htmlspecialchars($resident['socials'] ?: 'N/A'); ?></div>
            <div class="detail-item"><span>Emergency Contact Name:</span> <?php echo htmlspecialchars($resident['guardian_name'] ?: 'N/A'); ?></div>
            <div class="detail-item"><span>Emergency Contact Number:</span> <?php echo htmlspecialchars($resident['guardian_cont_num'] ?: 'N/A'); ?></div>
            <div class="detail-item"><span>Relationship:</span> <?php echo htmlspecialchars($resident['guardian_rel'] ?: 'N/A'); ?></div>
          </div>
          <div class="detail-col">
            <div class="detail-item"><span>Mother Name:</span> <?php echo htmlspecialchars($resident['mother_name'] ?: 'N/A'); ?></div>
            <div class="detail-item"><span>Father Name:</span> <?php echo htmlspecialchars($resident['father_name'] ?: 'N/A'); ?></div>
            <div class="detail-item"><span>Spouse Name:</span> <?php echo htmlspecialchars($resident['spouse_name'] ?: 'N/A'); ?></div>
            <div class="detail-item"><span>PWD ID Date Issued:</span> <?php echo htmlspecialchars($resident['idissue_date'] ?: 'N/A'); ?></div>
            <div class="detail-item"><span>PWD ID Expiration Date:</span> <?php echo htmlspecialchars($resident['idexpiration_date'] ?: 'N/A'); ?></div>
          </div>
        </div>
      </div>
      
      <?php if (($status === 'Needs Correction' || $filter === 'Needs Correction') && !empty($resident['correction_remarks'])): ?>
      <div style="background: var(--yellow-light); padding: 18px 24px; border-radius: 8px; border-left: 5px solid var(--yellow); margin-top: 10px;">
        <h4 style="color: var(--yellow); font-size: 14px; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Correction Reason / Remarks:</h4>
        <p style="color: var(--text-main); font-size: 15px; font-weight: 500; margin: 0; line-height: 1.4;"><?php echo nl2br(htmlspecialchars($resident['correction_remarks'])); ?></p>
      </div>
      <?php endif; ?>

    </div>
    <?php 
        endwhile; 
    else: 
    ?>
      <p style="text-align: center; color: var(--text-muted); padding: 40px 0;">No submissions found for this category.</p>
    <?php endif; ?>

    <?php if ($total_pages > 0): ?>
    <div class="pagination">
      <span class="pagination-info">
        Page <?= $page ?> of <?= $total_pages ?> &nbsp;·&nbsp; <?= $total_records ?> submission<?= $total_records !== 1 ? "s" : "" ?>
      </span>
      <div class="pagination-btns">
        <a href="?filter=<?= $filter ?>&page=<?= $page - 1 ?>" class="page-btn <?= $page <= 1 ? 'disabled' : '' ?>">‹</a>
        <?php
          $start = max(1, $page - 2);
          $end   = min($total_pages, $page + 2);
          if ($start > 1): ?>
            <a href="?filter=<?= $filter ?>&page=1" class="page-btn">1</a>
            <?php if ($start > 2): ?><span class="page-btn" style="pointer-events:none;">…</span><?php endif; ?>
        <?php endif;
          for ($i = $start; $i <= $end; $i++): ?>
            <a href="?filter=<?= $filter ?>&page=<?= $i ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor;
          if ($end < $total_pages): ?>
            <?php if ($end < $total_pages - 1): ?><span class="page-btn" style="pointer-events:none;">…</span><?php endif; ?>
            <a href="?filter=<?= $filter ?>&page=<?= $total_pages ?>" class="page-btn"><?= $total_pages ?></a>
        <?php endif; ?>
        <a href="?filter=<?= $filter ?>&page=<?= $page + 1 ?>" class="page-btn <?= $page >= $total_pages ? 'disabled' : '' ?>">›</a>
      </div>
    </div>
    <?php endif; ?>

  </div>
</main>

<?php include("func/review_modals.php"); ?>

<script>
  function toggleMenu(event, id) {
    event.preventDefault();
    const trigger = event.currentTarget;
    const submenu = document.getElementById(id);
    trigger.classList.toggle("open");
    submenu.classList.toggle("open");
  }

  function logout() {
    window.location.href = "login.php"; 
  }
</script>

</body>
</html>