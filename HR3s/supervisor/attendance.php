<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Management | HR3</title>
    <link rel="stylesheet" href="css/attendance.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>

<?php 
    $current_page = 'attendance'; // Matches the logic in your sidebar.php
    include('sidebar.php'); 
?>

<div class="main">
    <div class="header" style="margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-weight: 800; font-size: 1.8rem;">Attendance Log Manager</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Team: Logistics Department | Feb 19, 2026</p>
        </div>
        <div class="search-container">
            <input type="text" class="search-input" id="logSearch" placeholder="Search employee..." onkeyup="filterLogs()">
        </div>
    </div>

    <div class="kpi-row">
        <div class="kpi-card">
            <div class="kpi-icon icon-pres"><i class="fas fa-user-check"></i></div>
            <div><span class="kpi-val">42</span><span class="kpi-lab">Present</span></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon icon-late"><i class="fas fa-clock"></i></div>
            <div><span class="kpi-val">05</span><span class="kpi-lab">Late Arrivals</span></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon icon-miss"><i class="fas fa-fingerprint"></i></div>
            <div><span class="kpi-val">02</span><span class="kpi-lab">Missing Logs</span></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon icon-pend"><i class="fas fa-exclamation-circle"></i></div>
            <div><span class="kpi-val">01</span><span class="kpi-lab">To Verify</span></div>
        </div>
    </div>

    <div class="attendance-flex">
        <div class="col">
            <div class="card">
                <div class="card-header"><div class="card-title">Live Attendance Feed</div></div>
                <table id="attendanceTable">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Juan Dela Cruz</strong></td>
                            <td>Feb 19, 2026</td>
                            <td>08:00 AM</td>
                            <td><span class="status-badge bg-approved">Active</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-title" style="margin-bottom: 1.2rem;">🚨 Action Required</div>
                <div class="log-card">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-weight: 700;">Maria Clara</span>
                        <span class="status-badge bg-pending">Verify</span>
                    </div>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Adjustment Request • Feb 18</p>
                    <div style="margin-top: 15px; display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 0.75rem; color: var(--brand-green); font-weight: 700;">+45m Variance</span>
                        <button class="btn btn-primary" style="padding: 8px 14px; font-size: 0.7rem;" 
                                onclick="openReviewModal('Maria Clara', '08:45 AM', '08:00 AM', 'Feb 18, 2026', 'Network error at the turnstile gate during morning rush.', '08:00 AM - 05:00 PM', 2, 1)">
                            Verify Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="reviewModal">
    <div class="review-modal">
        <h2 id="mEmpName" style="font-size: 1.4rem; font-weight: 800; margin-bottom: 2px;">--</h2>
        <p id="mSchedule" style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1.2rem;">--</p>

        <div class="stats-bar">
            <div class="stat-item"><p style="font-size:0.6rem; color:var(--text-muted); text-transform:uppercase;">Lates</p><p id="sLates" style="font-weight:800;">0</p></div>
            <div class="stat-item"><p style="font-size:0.6rem; color:var(--text-muted); text-transform:uppercase;">Adjustments</p><p id="sAdjust" style="font-weight:800;">0</p></div>
            <div class="stat-item"><p style="font-size:0.6rem; color:var(--text-muted); text-transform:uppercase;">Reliability</p><p id="sScore" style="font-weight:800; color:var(--brand-green);">100%</p></div>
        </div>
        
        <div class="comparison-grid">
            <div class="comp-item"><p style="font-size: 0.6rem; color: var(--text-muted);">SYSTEM RECORD</p><p id="mRecorded" style="font-size: 1.2rem; font-weight: 800; color: #e74c3c;">--</p></div>
            <div class="comp-item" style="background:rgba(44,160,120,0.03);"><p style="font-size: 0.6rem; color: var(--text-muted);">EMPLOYEE CLAIM</p><p id="mRequested" style="font-size: 1.2rem; font-weight: 800; color: var(--brand-green);">--</p></div>
        </div>
        
        <p style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">Justification:</p>
        <div id="mReason" style="background: rgba(0,0,0,0.03); padding: 12px; border-radius: 10px; font-size: 0.85rem; font-style: italic; border-left: 4px solid var(--brand-green); margin-bottom: 1rem;">--</div>

        <p style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted);">Supervisor's Audit Note:</p>
        <textarea class="comment-area" id="mComment" rows="2" placeholder="e.g. 'Confirmed with CCTV footage'"></textarea>

        <div style="display: flex; gap: 12px; margin-top: 1.5rem;">
            <button class="btn btn-primary" style="flex: 2; height: 48px;" onclick="closeReviewModal()">Verify & Send to HR</button>
            <button class="btn btn-outline" style="flex: 1; border-color: #e74c3c; color: #e74c3c;" onclick="closeReviewModal()">Decline</button>
        </div>
    </div>
</div>

<script>
    function openReviewModal(name, recorded, requested, date, reason, schedule, lates, adjusts) {
        document.getElementById('mEmpName').innerText = name;
        document.getElementById('mRecorded').innerText = recorded;
        document.getElementById('mRequested').innerText = requested;
        document.getElementById('mReason').innerText = `"${reason}"`;
        document.getElementById('mSchedule').innerText = `Assigned Shift: ${schedule}`;
        
        // Populate Stats
        document.getElementById('sLates').innerText = lates;
        document.getElementById('sAdjust').innerText = adjusts;
        const score = 100 - (lates * 5);
        document.getElementById('sScore').innerText = score + "%";
        document.getElementById('sScore').style.color = score < 90 ? "#e74c3c" : "var(--brand-green)";

        document.getElementById('reviewModal').style.display = 'flex';
    }

    function closeReviewModal() { document.getElementById('reviewModal').style.display = 'none'; }
    window.onclick = function(event) { if (event.target == document.getElementById('reviewModal')) closeReviewModal(); }
    
    function filterLogs() {
        let input = document.getElementById('logSearch').value.toUpperCase();
        let rows = document.getElementById('attendanceTable').getElementsByTagName('tr');
        for (let i = 1; i < rows.length; i++) {
            let text = rows[i].textContent || rows[i].innerText;
            rows[i].style.display = text.toUpperCase().includes(input) ? "" : "none";
        }
    }
</script>

</body>
</html>