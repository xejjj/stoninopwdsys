<!-- DELETE MODAL -->
<div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
    <div style="width:48px; height:48px; background:#FEE2E2; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.5"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; margin-bottom:10px; color:#1c0202; text-align:center;">Delete Record?</h2>
    <p style="font-size:13.5px; color:rgba(28,2,2,0.6); margin-bottom:24px; text-align:center;">This action cannot be undone. Are you sure you want to permanently delete this submission?</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="closeReviewModals()" style="padding:8px 18px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:none; font-family:inherit; font-weight:700; cursor:pointer; color:rgba(28,2,2,0.6);">Cancel</button>
      <button id="confirmDeleteBtn" style="padding:8px 18px; border-radius:8px; border:none; background:#DC2626; color:#fff; font-family:inherit; font-weight:700; cursor:pointer;">Yes, Delete</button>
    </div>
  </div>
</div>

<!-- HARD REJECT MODAL (Moves from Correction to Rejected Tab) -->
<div id="hardRejectModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
    <div style="width:48px; height:48px; background:#FEE2E2; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-3"></path></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; margin-bottom:10px; color:#1c0202; text-align:center;">Move to Rejected?</h2>
    <p style="font-size:13.5px; color:rgba(28,2,2,0.6); margin-bottom:24px; text-align:center;">Are you sure you want to move this resident to the Rejected tab? They will be removed from the review queue.</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="closeReviewModals()" style="padding:8px 18px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:none; font-family:inherit; font-weight:700; cursor:pointer; color:rgba(28,2,2,0.6);">Cancel</button>
      <button id="confirmHardRejectBtn" style="padding:8px 18px; border-radius:8px; border:none; background:#DC2626; color:#fff; font-family:inherit; font-weight:700; cursor:pointer;">Yes</button>
    </div>
  </div>
</div>

<!-- REASON FOR CORRECTION MODAL (Moves to Needs Correction) -->
<div id="rejectModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div style="background:#fff; border-radius:16px; padding:32px; max-width:400px; width:90%; box-shadow:0 8px 32px rgba(0,0,0,0.15);">
    <div style="width:48px; height:48px; background:#FEF08A; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#CA8A04" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-3"></path></svg>
    </div>
    <h2 style="font-size:18px; font-weight:800; margin-bottom:20px; color:#1c0202; text-align:center;">Needs Correction</h2>
    
    <form id="rejectForm" style="margin:0;">
      <div style="text-align: left; margin-bottom: 24px;">
        <label style="display:block; font-size:13px; font-weight:700; color:rgba(28,2,2,0.6); margin-bottom:6px;">Reason for Correction / Rejection</label>
        <textarea id="rejectReasonInput" required style="width:100%; padding:10px 14px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:#f9f9f9; font-family:inherit; font-size:14px; outline:none; box-sizing:border-box; resize:vertical; min-height:80px;" placeholder="Please type the reason..."></textarea>
      </div>
      <div style="display:flex; gap:10px; justify-content:center;">
        <button type="button" onclick="closeReviewModals()" style="padding:8px 18px; border-radius:8px; border:1px solid rgba(0,0,0,0.1); background:none; font-family:inherit; font-weight:700; cursor:pointer; color:rgba(28,2,2,0.6);">Cancel</button>
        <button type="submit" style="padding:8px 18px; border-radius:8px; border:none; background:#CA8A04; color:#fff; font-family:inherit; font-weight:700; cursor:pointer;">Submit</button>
      </div>
    </form>
  </div>
</div>

<script>
  let currentRecordId = null;

  // 1. DELETE FROM REJECTED TAB
  function openDeleteModal(id) {
    currentRecordId = id;
    document.getElementById('deleteModal').style.display = 'flex';
  }

  document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (currentRecordId) {
      window.location.href = `func/deleteRejected.php?id=${currentRecordId}`;
    }
  });

  // 2. MOVE TO REJECTED TAB
  function openHardRejectModal(id) {
    currentRecordId = id;
    document.getElementById('hardRejectModal').style.display = 'flex';
  }

  document.getElementById('confirmHardRejectBtn').addEventListener('click', function() {
    if (currentRecordId) {
      window.location.href = `func/updateStatus.php?id=${currentRecordId}&status=Rejected`;
    }
  });

  // 3. MOVE TO NEEDS CORRECTION TAB (WITH REASON)
  function openRejectModal(id) {
    currentRecordId = id;
    document.getElementById('rejectReasonInput').value = ''; 
    document.getElementById('rejectModal').style.display = 'flex';
  }

  document.getElementById('rejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const reason = document.getElementById('rejectReasonInput').value;
    if (reason.trim() !== "" && currentRecordId) {
        window.location.href = `func/updateStatus.php?id=${currentRecordId}&status=Needs%20Correction&reason=${encodeURIComponent(reason)}`;
    }
  });

  // CLOSE ALL MODALS
  function closeReviewModals() {
    document.getElementById('deleteModal').style.display = 'none';
    document.getElementById('hardRejectModal').style.display = 'none';
    document.getElementById('rejectModal').style.display = 'none';
    currentRecordId = null;
  }
</script>