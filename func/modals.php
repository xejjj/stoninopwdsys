<?php
$isAdmin = ($_SESSION["role"] ?? "") === "admin";
?>
<style>
/* Scoped View Modal Layout Rules */
#viewModalOverlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center; padding:20px; font-family:'DM Sans', sans-serif; }
#viewModalContainer { background:#f5f4f2; border-radius:20px; width:100%; max-width:1100px; max-height:90vh; display:flex; flex-direction:column; position:relative; box-shadow:0 10px 40px rgba(0,0,0,0.3); }
#viewModalBody { padding:24px; overflow-y:auto; flex:1; }
.m-profile-header { background:#fff; border-radius:20px; position:relative; box-shadow:0 4px 15px rgba(0,0,0,0.02); margin-bottom:15px; }
.m-banner { height:48px; background:linear-gradient(90deg, #D6A886 0%, #D86B69 100%); border-radius:20px 20px 0 0; width:100%; }
.m-profile-content { padding:0 32px 32px 32px; display:flex; justify-content:space-between; }
.m-profile-left { display:flex; gap:20px; }
.m-avatar { width:100px; height:100px; border-radius:16px; border:4px solid #FFF; object-fit:cover; margin-top:-24px; background:#333; box-shadow:0 4px 10px rgba(0,0,0,0.08); }
.m-profile-info { margin-top:16px; }
.m-profile-name-row { display:flex; align-items:center; gap:12px; margin-bottom:4px; }
.m-profile-name { font-size:30px; font-weight:800; letter-spacing:-0.5px; color:#1A1A1A; margin:0; }
.m-profile-id { font-size:15px; color:#6B7280; font-weight:500; }
.m-classification-box { margin-top:-16px; background:#FFF; border-radius:30px; padding:20px 38px; box-shadow:0 4px 15px rgba(0,0,0,0.06); display:flex; flex-direction:column; align-items:flex-end; gap:8px; position:relative; z-index:2; }
.m-classification-title { font-size:22px; font-weight:700; color:#1A1A1A; }
.m-details-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:24px; }
.m-card { background:#fff; border-radius:20px; padding:32px; box-shadow:0 4px 15px rgba(0,0,0,0.02); display:flex; flex-direction:column; }
.m-card-contact { grid-column:1 / -1; }
.m-card-header { display:flex; align-items:center; gap:12px; margin-bottom:24px; }
.m-card-header h3 { font-size:18px; font-weight:700; color:#1A1A1A; margin:0; }
.m-icon-box { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; }
.m-icon-box svg { width:16px; height:16px; }
.m-bg-orange { background:#FFF7ED; color:#EA580C; }
.m-bg-blue { background:#EFF6FF; color:#3B82F6; }
.m-bg-red { background:#FEF2F2; color:#EF4444; }
.m-bg-purple { background:#F5F3FF; color:#9333EA; }
.m-info-list { display:flex; flex-direction:column; gap:20px; }
.m-contact-grid { display:grid; grid-template-columns:repeat(3, 1fr); gap:24px; }
.m-info-group { display:flex; flex-direction:column; gap:4px; }
.m-info-label { font-size:13px; font-weight:600; color:#6B7280; }
.m-info-value { font-size:14px; font-weight:600; color:#1A1A1A; }
.m-badge { display:inline-flex; align-items:center; justify-content:center; padding:5px 16px; border-radius:999px; font-size:11px; font-weight:700; white-space:nowrap; border:1.5px solid transparent; }
.m-badge-active { background:#DFF5E3; color:#32C24D; border-color:#32C24D; }
.m-badge-under-review { background:#FFF1E8; color:#E57A39; border-color:#F0BE9B; }
.m-badge-needs-correction { background:#FFF4E8; color:#D17A2B; border-color:#E7BC8D; }
.m-badge-expired { background:#FFDCDC; color:#E02424; border-color:#FF4D4D; }
.m-badge-rejected { background:#F9E2E2; color:#B42323; border-color:#D96B6B; }
.m-badge-visual { background:#E6EAFB; color:#155DA4; border:1px solid #C4D2F2; }
.m-badge-auditory { background:#FFF8D7; color:#C5AD11; border:1px solid #F0DF87; }
.m-badge-cognitive { background:#F7E6FB; color:#7E15A4; border:1px solid #E9D5FF; }
.m-badge-physical { background:#FFDCDC; color:#A41515; border:1px solid #F2C4C4; }
.m-badge-speech { background:#FFE9D7; color:#D13E0D; border:1px solid #F2D1C4; }
.m-badge-psycho { background:#E6FBE6; color:#15A44E; border:1px solid #C4F2D2; }
.m-badge-others { background:#F0F0F0; color:#666; border:1px solid #DDD; }

/* Missing Info Highlighting Styles */
body.show-missing-info tr[data-missing="true"] td { background-color: #FEF2F2 !important; }
body.show-missing-info tr[data-missing="true"] td:first-child { border-left: 4px solid #EF4444; }
.m-missing-field { background: #FEF2F2; padding: 6px 10px; border-radius: 6px; margin: -6px -10px; border: 1px dashed #FCA5A5; }
.m-missing-field .m-info-label { color: #DC2626 !important; font-weight: 700; }
.m-missing-field .m-info-value { color: #DC2626 !important; font-weight: 700; }

@media (max-width: 992px) {
  #viewModalContainer { max-height: 95vh; }
  .m-profile-content { flex-direction: column; align-items: center; text-align: center; padding: 0 20px 20px 20px; }
  .m-profile-left { flex-direction: column; align-items: center; gap: 10px; }
  .m-profile-name-row { flex-direction: column; gap: 6px; }
  .m-classification-box { margin-top: 15px; align-items: center; padding: 15px; width: 100%; }
  #m-dis-badges { justify-content: center !important; }
  .m-details-grid { grid-template-columns: 1fr; gap: 15px; }
  .m-contact-grid { grid-template-columns: 1fr; gap: 15px; }
  .m-card { padding: 20px; }
}
</style>

<div id="viewModalOverlay">
  <div id="viewModalContainer">
    <div style="padding:15px 24px; background:#fff; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center; border-radius: 20px 20px 0 0;">
      <h2 style="margin:0; font-size:18px; font-weight:700; color:#1A1A1A;">Resident Profile</h2>
      <div style="display:flex; gap:10px;">
        <?php if ($isAdmin): ?>
        <button id="m-renew-btn" data-id="" onclick="openRenewModal()" style="border:none; cursor:pointer; padding:8px 16px; background:#ECFDF5; color:#10B981; border-radius:8px; font-weight:600; font-size:14px; display:flex; align-items:center; gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg> Renew
        </button>
        <button id="m-archive-btn" data-id="" onclick="confirmArchive(this.dataset.id)" style="border:none; cursor:pointer; padding:8px 16px; background:#FEF2F2; color:#EF4444; border-radius:8px; font-weight:600; font-size:14px; display:flex; align-items:center; gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="12" x2="14" y2="12"/></svg> Archive
        </button>
        <?php endif; ?>
        <a id="m-edit-btn" href="#" style="text-decoration:none; padding:8px 16px; background:#EFF6FF; color:#3B82F6; border-radius:8px; font-weight:600; font-size:14px; display:flex; align-items:center; gap:6px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit
        </a>
        <button onclick="document.getElementById('viewModalOverlay').style.display='none'" style="background:none; border:none; cursor:pointer; padding:4px;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#333" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
    </div>

    <div id="viewModalBody">
      <div class="m-profile-header">
        <div class="m-banner"></div>
        <div class="m-profile-content">
          <div class="m-profile-left">
            <img id="m-avatar" src="" alt="Profile" class="m-avatar">
            <div class="m-profile-info">
              <div class="m-profile-name-row">
                <h1 class="m-profile-name" id="m-name"></h1>
                <span class="m-badge" id="m-status"></span>
              </div>
              <div class="m-profile-id">ID #: <span id="m-pwdid-top"></span></div>
            </div>
          </div>
          <div class="m-classification-box">
            <div class="m-classification-title">Disability Classification</div>
            <div id="m-dis-badges" style="display:flex; gap:6px; flex-wrap:wrap; justify-content:flex-end;"></div>
          </div>
        </div>
      </div>

      <div class="m-details-grid">
        <div class="m-card">
          <div class="m-card-header">
            <div class="m-icon-box m-bg-orange"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            <h3>Personal</h3>
          </div>
          <div class="m-info-list">
            <div class="m-info-group"><span class="m-info-label">Date of Birth</span><span class="m-info-value" id="m-dob"></span></div>
            <div class="m-info-group"><span class="m-info-label">Gender</span><span class="m-info-value" id="m-gender"></span></div>
            <div class="m-info-group"><span class="m-info-label">Civil Status</span><span class="m-info-value" id="m-civil"></span></div>
          </div>
        </div>

        <div class="m-card">
          <div class="m-card-header">
            <div class="m-icon-box m-bg-blue"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg></div>
            <h3>Disability</h3>
          </div>
          <div class="m-info-list">
            <div class="m-info-group"><span class="m-info-label">Type of Disability/ies</span><span class="m-info-value" id="m-dis-type"></span></div>
            <div class="m-info-group"><span class="m-info-label">Cause of Disability</span><span class="m-info-value" id="m-dis-cause"></span></div>
            <div class="m-info-group"><span class="m-info-label">Medical Certificate</span><span class="m-info-value" id="m-medcert"></span></div>
          </div>
        </div>

        <div class="m-card">
          <div class="m-card-header">
            <div class="m-icon-box m-bg-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
            <h3>ID Information</h3>
          </div>
          <div class="m-info-list">
            <div class="m-info-group">
                <span class="m-info-label">PWD ID Number</span>
                <span class="m-info-value" id="m-pwdid"></span>
            </div>
            <div class="m-info-group">
                <span class="m-info-label">PWD ID Image</span>
                <span class="m-info-value" id="m-pwd-img-val" style="display:none;"></span>
                <div id="m-pwd-image-container" style="margin-top: 4px;"></div>
            </div>
            <div class="m-info-group"><span class="m-info-label">Control Number</span><span class="m-info-value" id="m-control"></span></div>
            <div class="m-info-group"><span class="m-info-label">Date Issued</span><span class="m-info-value" id="m-issued"></span></div>
            <div class="m-info-group"><span class="m-info-label">Expiration Date</span><span class="m-info-value" id="m-expired"></span></div>
          </div>
        </div>

        <div class="m-card m-card-contact">
          <div class="m-card-header">
            <div class="m-icon-box m-bg-purple"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <h3>Contact and Address</h3>
          </div>
          <div class="m-contact-grid">
            <div class="m-info-list">
              <div class="m-info-group"><span class="m-info-label">Mobile Number</span><span class="m-info-value" id="m-mobile"></span></div>
              <div class="m-info-group"><span class="m-info-label">House No. / Street</span><span class="m-info-value" id="m-address"></span></div>
              <div class="m-info-group"><span class="m-info-label">Email/Facebook Account</span><span class="m-info-value" id="m-socials"></span></div>
            </div>
            <div class="m-info-list">
              <div class="m-info-group"><span class="m-info-label">Emergency Contact Name</span><span class="m-info-value" id="m-em-name"></span></div>
              <div class="m-info-group"><span class="m-info-label">Emergency Contact Number</span><span class="m-info-value" id="m-em-num"></span></div>
              <div class="m-info-group"><span class="m-info-label">Relationship</span><span class="m-info-value" id="m-em-rel"></span></div>
            </div>
            <div class="m-info-list">
              <div class="m-info-group"><span class="m-info-label">Father Name</span><span class="m-info-value" id="m-father"></span></div>
              <div class="m-info-group"><span class="m-info-label">Mother Name</span><span class="m-info-value" id="m-mother"></span></div>
              <div class="m-info-group"><span class="m-info-label">Spouse Name</span><span class="m-info-value" id="m-spouse"></span></div>
              <div class="m-info-group"><span class="m-info-label">Guardian</span><span class="m-info-value" id="m-guardian"></span></div>
              <div class="m-info-group"><span class="m-info-label">Guardian Relationship</span><span class="m-info-value" id="m-guardian-rel"></span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="archiveModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:10000; align-items:center; justify-content:center;">
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

<div id="renewModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:10000; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
    <div style="width:48px; height:48px; background:#ECFDF5; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; margin-bottom:10px; color:#1c0202; text-align:center;">Renew ID Dates</h2>
    <form action="func/processRenew.php" method="POST" style="margin:0; text-align:left;">
      <input type="hidden" name="resident_id" id="renewId">
      <div style="margin-bottom: 15px;">
          <label style="display:block; font-size:13px; font-weight:600; color:#6B7280; margin-bottom:4px;">New Date Issued</label>
          <input type="date" name="new_date_issued" required style="width:100%; padding:8px 12px; border-radius:8px; border:1px solid #ccc; font-family:inherit; box-sizing:border-box;">
      </div>
      <div style="margin-bottom: 24px;">
          <label style="display:block; font-size:13px; font-weight:600; color:#6B7280; margin-bottom:4px;">New Expiration Date</label>
          <input type="date" name="new_expiration_date" required style="width:100%; padding:8px 12px; border-radius:8px; border:1px solid #ccc; font-family:inherit; box-sizing:border-box;">
      </div>
      <div style="display:flex; gap:10px; justify-content:center;">
        <button type="button" onclick="document.getElementById('renewModal').style.display='none'" style="padding:8px 18px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:none; font-family:inherit; font-weight:700; cursor:pointer;">Cancel</button>
        <button type="submit" style="padding:8px 18px; border-radius:8px; border:none; background:#10B981; color:#fff; font-family:inherit; font-weight:700; cursor:pointer;">Save Renewal</button>
      </div>
    </form>
  </div>
</div>

<script>
function checkMissingField(id, val) {
    const el = document.getElementById(id);
    if (!val || val === 'N/A' || val === '0000-00-00' || val.includes('Invalid Date')) {
        el.closest('.m-info-group').classList.add('m-missing-field');
    }
}

function openViewModal(btnElement) {
    const data = JSON.parse(btnElement.getAttribute('data-info'));
    
    // Track Recent View
    fetch(window.location.pathname + '?track_view_id=' + data.ID);

    // Clear old missing field highlights
    document.querySelectorAll('.m-missing-field').forEach(el => el.classList.remove('m-missing-field'));

    document.getElementById('m-edit-btn').href = 'editResident.php?id=' + data.ID;
    
    const archiveBtn = document.getElementById('m-archive-btn');
    if (archiveBtn) archiveBtn.dataset.id = data.ID;
    
    const renewBtn = document.getElementById('m-renew-btn');
    if (renewBtn) renewBtn.dataset.id = data.ID;

    const fullName = [data.first_name, data.middle_name, data.last_name].filter(Boolean).join(' ').trim();
    document.getElementById('m-name').textContent = fullName;
    document.getElementById('m-avatar').src = data.profile ? data.profile : `https://ui-avatars.com/api/?name=${encodeURIComponent(fullName)}&background=1c0202&color=fff&size=200`;
    document.getElementById('m-pwdid-top').textContent = data.pwdid_num || 'N/A';

    let status = 'Active';
    if (data.record_status === 'expired') status = 'Expired';
    else if (data.application_status === 'needs correction') status = 'Needs Correction';
    else if (data.application_status === 'rejected') status = 'Rejected';
    else if (data.application_status === 'under review') status = 'Under Review';
    const statusEl = document.getElementById('m-status');
    statusEl.textContent = status;
    statusEl.className = 'm-badge m-badge-' + status.toLowerCase().replace(/ /g, '-');

    const disContainer = document.getElementById('m-dis-badges');
    disContainer.innerHTML = '';
    if (data.disability_type) {
        const badgeMap = {
            "cognitive": "m-badge-cognitive", "visual": "m-badge-visual",
            "physical": "m-badge-physical", "auditory": "m-badge-auditory",
            "speech": "m-badge-speech", "psychosocial": "m-badge-psycho",
            "others": "m-badge-others"
        };
        data.disability_type.split(',').forEach(t => {
            t = t.trim();
            if(t) {
                const span = document.createElement('span');
                span.textContent = t;
                span.className = 'm-badge ' + (badgeMap[t.toLowerCase()] || 'm-badge-physical');
                disContainer.appendChild(span);
            }
        });
    }

    let ageText = 'N/A';
    if (data.birthdate && data.birthdate !== '0000-00-00') {
        const dob = new Date(data.birthdate);
        const age = Math.abs(new Date(Date.now() - dob.getTime()).getUTCFullYear() - 1970);
        ageText = dob.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) + ` (${age} years old)`;
    }
    
    document.getElementById('m-dob').textContent = ageText;
    document.getElementById('m-gender').textContent = data.sex ? (data.sex.charAt(0).toUpperCase() + data.sex.slice(1)) : 'N/A';
    document.getElementById('m-civil').textContent = data.civil_status || 'N/A';
    document.getElementById('m-dis-type').textContent = data.disability_type || 'N/A';
    document.getElementById('m-dis-cause').textContent = data.disability_remarks || 'N/A';
    
    const mc = document.getElementById('m-medcert');
    if (data.med_cert) mc.innerHTML = `<a href="${data.med_cert}" style="color:#3B82F6; text-decoration:underline;" target="_blank">View Medical Certificate</a>`;
    else mc.textContent = 'N/A';

    document.getElementById('m-pwdid').textContent = data.pwdid_num || 'N/A';
    
    // PWD ID Card Image
    const pwdImgContainer = document.getElementById('m-pwd-image-container');
    const pwdImgVal = document.getElementById('m-pwd-img-val');
    if (data.pwd_id_card) {
        pwdImgContainer.innerHTML = `<a href="${data.pwd_id_card}" target="_blank"><img src="${data.pwd_id_card}" alt="PWD ID" style="max-width: 100%; max-height: 120px; border-radius: 8px; border: 1px solid #ccc; object-fit: cover; cursor: pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'"></a>`;
        pwdImgVal.textContent = data.pwd_id_card;
    } else {
        pwdImgContainer.innerHTML = '<span class="m-info-value" style="font-size:14px; color:#888;">N/A</span>';
        pwdImgVal.textContent = '';
    }

    document.getElementById('m-control').textContent = data.control_num || 'N/A';
    document.getElementById('m-issued').textContent = data.idissue_date || 'N/A';
    document.getElementById('m-expired').textContent = data.idexpiration_date || 'N/A';
    document.getElementById('m-mobile').textContent = data.contact_num || 'N/A';
    document.getElementById('m-address').textContent = data.address || 'N/A';
    document.getElementById('m-socials').textContent = data.socials || 'N/A';
    document.getElementById('m-em-name').textContent = data.emergency_name || 'N/A';
    document.getElementById('m-em-num').textContent = data.emergency_number || 'N/A';
    document.getElementById('m-em-rel').textContent = data.emergency_relation || 'N/A';
    document.getElementById('m-father').textContent = data.father_name || 'N/A';
    document.getElementById('m-mother').textContent = data.mother_name || 'N/A';
    document.getElementById('m-spouse').textContent = data.spouse_name || 'N/A';
    document.getElementById('m-guardian').textContent = data.guardian_name || 'N/A';
    document.getElementById('m-guardian-rel').textContent = data.guardian_rel || 'N/A';

    // Apply Highlight Logic for Missing Required Fields
    checkMissingField('m-dob', ageText);
    checkMissingField('m-gender', document.getElementById('m-gender').textContent);
    checkMissingField('m-civil', data.civil_status);
    checkMissingField('m-dis-type', data.disability_type);
    checkMissingField('m-pwdid', data.pwdid_num);
    checkMissingField('m-pwd-img-val', data.pwd_id_card); // Enforce image requirement here
    checkMissingField('m-control', data.control_num);
    checkMissingField('m-issued', data.idissue_date);
    checkMissingField('m-expired', data.idexpiration_date);
    checkMissingField('m-mobile', data.contact_num);
    checkMissingField('m-address', data.address);

    document.getElementById('viewModalOverlay').style.display = 'flex';
}

function confirmArchive(id) {
  document.getElementById("archiveId").value = id;
  document.getElementById('viewModalOverlay').style.display = 'none'; 
  document.getElementById("archiveModal").style.display = "flex";
}

function openRenewModal() {
  document.getElementById("renewId").value = document.getElementById("m-renew-btn").dataset.id;
  document.getElementById('viewModalOverlay').style.display = 'none'; 
  document.getElementById("renewModal").style.display = "flex";
}
</script>