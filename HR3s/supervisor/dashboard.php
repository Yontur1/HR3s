<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supervisor Control Center | HR3</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- SIDEBAR ACTIVE STATE LOGIC --- */
        
        /* --- DASHBOARD SPECIFIC UI UPGRADES --- */
        .widget-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .widget {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            transition: 0.3s;
        }
        .widget:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .widget h4 { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
        .widget .value { font-size: 1.8rem; font-weight: 800; color: var(--text-main); }
        
        .status-on-time { color: var(--brand-green); font-weight: 600; }
        .status-late { color: #f39c12; font-weight: 600; }
        .status-absent { color: #ef4444; font-weight: 600; }

        /* Notif Feed Styling */
        .notif-item {
            padding: 12px;
            border-radius: 8px;
            background: rgba(0,0,0,0.02);
            margin-bottom: 8px;
            font-size: 0.85rem;
            border-left: 3px solid var(--brand-green);
        }

        /* --- MODAL SYSTEM --- */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.75); display: none;
            justify-content: center; align-items: center; z-index: 2000;
            backdrop-filter: blur(8px);
        }
        .emp-modal {
            background: var(--card-bg); width: 90%; max-width: 400px;
            border-radius: 20px; padding: 2rem; border: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <?php 
        $current_page = 'dashboard'; // Trigger sidebar highlight
        include('sidebar.php'); 
    ?>

<div class="main">
    <div class="header" style="margin-bottom: 1.5rem;">
        <div class="search-container">
            <i class="fas fa-search" style="position: absolute; left: 15px; top: 12px; color: #aaa;"></i>
            <input type="text" class="search-input" style="padding-left: 40px;" placeholder="Search team members..." id="empSearch" onkeyup="filterEmployees()">
        </div>
        <div style="background: rgba(44, 160, 120, 0.1); padding: 8px 16px; border-radius: 50px; color: var(--brand-green); font-size: 0.85rem; font-weight: 600;">
            <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 5px;"></i> Live Monitor Active
        </div>
    </div>

    <div class="widget-grid">
        <div class="widget"><h4>Attendance Rate</h4><div class="value">92%</div></div>
        <div class="widget"><h4>Late Alerts</h4><div class="value status-late">03</div></div>
        <div class="widget"><h4>Pending Actions</h4><div class="value" style="color: #3b82f6;">12</div></div>
        <div class="widget"><h4>Active Shifts</h4><div class="value">08</div></div>
    </div>

    <div class="analytics-grid" style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; margin-bottom: 1.5rem;">
        <div class="card">
            <div class="card-title">Daily Composition</div>
            <div class="chart-container" style="height: 250px; position: relative;">
                <canvas id="dailyPieChart"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">Weekly Performance Trends</div>
                <button class="btn btn-export" onclick="alert('Exporting PDF...')"><i class="fas fa-file-pdf"></i> PDF</button>
            </div>
            <div class="chart-container" style="height: 250px;">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    <div class="dashboard-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div class="card">
            <div class="card-header">
                <div id="tableHeader" class="card-title">🚨 Real-Time Monitor</div>
                <div style="display: flex; gap: 8px;">
                    <button class="btn btn-outline" style="font-size: 0.7rem; padding: 5px 10px;" onclick="resetTable()">Reset</button>
                    <button class="btn btn-outline" style="font-size: 0.7rem; padding: 5px 10px;" onclick="alert('Exporting CSV...')">CSV</button>
                </div>
            </div>
            <table id="empTable">
                <thead><tr><th>Employee</th><th>Status</th><th>Time</th><th>Timesheet</th></tr></thead>
                <tbody>
                    <tr onclick="showEmployee('Juan Dela Cruz', 'On-time', '08:00 AM', '12 Days', 'Submitted', '₱2,500')">
                        <td><strong>Juan Dela Cruz</strong></td>
                        <td class="status-on-time">● On-time</td>
                        <td>08:00 AM</td>
                        <td><span style="color:var(--brand-green)">Submitted</span></td>
                    </tr>
                    <tr onclick="showEmployee('Maria Clara', 'Late', '08:45 AM', '8 Days', 'Pending', '₱0')">
                        <td><strong>Maria Clara</strong></td>
                        <td class="status-late">● Late</td>
                        <td>08:45 AM</td>
                        <td><span style="color:var(--warning)">Pending</span></td>
                    </tr>
                    <tr onclick="showEmployee('Pedro Penduko', 'Absent', '--:--', '10 Days', 'N/A', '₱500')">
                        <td><strong>Pedro Penduko</strong></td>
                        <td class="status-absent">● Absent</td>
                        <td>--:--</td>
                        <td><span style="color:#aaa">--</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="card" style="border-top: 4px solid #3b82f6;">
                <div class="card-title">🌴 Approvals Queue</div>
                <div style="margin: 1rem 0; font-size: 0.85rem; line-height: 2;">
                    <p style="display: flex; justify-content: space-between;"><span>Ana Reyes (Leave)</span> <span class="status-on-time">5d Left</span></p>
                    <p style="display: flex; justify-content: space-between;"><span>Jose Rizal (Claim)</span> <span class="status-late">₱1,200</span></p>
                </div>
                <button class="btn btn-primary" style="width: 100%;">Open Approval Center</button>
            </div>
            <div class="card">
                <div class="card-title">🔔 Activity Feed</div>
                <div class="notif-feed" style="max-height: 200px; overflow-y: auto;">
                    <div class="notif-item"><strong>Ana Reyes</strong> requested VL (Feb 20-22)</div>
                    <div class="notif-item" style="border-left-color: #f39c12;"><strong>Late:</strong> Maria Clara (Logistics)</div>
                    <div class="notif-item"><strong>Update:</strong> Shift schedule for March is ready</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="empModal">
    <div class="emp-modal">
        <h3 id="mName" style="margin-bottom: 5px;">--</h3>
        <p id="mStatus" style="font-size: 0.8rem; margin-bottom: 1.5rem;">--</p>
        
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 0.85rem;">
            <div><p style="color:#aaa;">Clock In</p><p id="mTime" style="font-weight:700;">--</p></div>
            <div><p style="color:#aaa;">Leave Bal</p><p id="mLeave" style="font-weight:700;">--</p></div>
            <div><p style="color:#aaa;">Timesheet</p><p id="mTS" style="font-weight:700;">--</p></div>
            <div><p style="color:#aaa;">Pending Claim</p><p id="mClaim" style="font-weight:700;">--</p></div>
        </div>
        
        <button class="btn btn-primary" style="width:100%; margin-top: 1.5rem;" onclick="closeModal()">Close Preview</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Logic to show/hide modal
function showEmployee(name, status, time, leave, ts, claim) {
    document.getElementById('mName').innerText = name;
    document.getElementById('mStatus').innerText = status;
    document.getElementById('mTime').innerText = time;
    document.getElementById('mLeave').innerText = leave;
    document.getElementById('mTS').innerText = ts;
    document.getElementById('mClaim').innerText = claim;
    document.getElementById('empModal').style.display = 'flex';
}
function closeModal() { document.getElementById('empModal').style.display = 'none'; }

// Reuse your filter and reset logic
function filterEmployees() {
    let input = document.getElementById('empSearch').value.toUpperCase();
    let tr = document.getElementById('empTable').getElementsByTagName('tr');
    for (let i = 1; i < tr.length; i++) {
        let textValue = tr[i].textContent || tr[i].innerText;
        tr[i].style.display = textValue.toUpperCase().indexOf(input) > -1 ? "" : "none";
    }
}

function resetTable() {
    let tr = document.getElementById('empTable').getElementsByTagName('tr');
    for (let i = 1; i < tr.length; i++) tr[i].style.display = "";
    document.getElementById('tableHeader').innerText = "🚨 Real-Time Monitor";
}

const pieCtx = document.getElementById('dailyPieChart').getContext('2d');
const dailyChart = new Chart(pieCtx, {
    type: 'pie', // Changed from doughnut to pie
    data: {
        labels: ['On-time', 'Late', 'Absent'],
        datasets: [{
            data: [18, 3, 2],
            backgroundColor: ['#2CA078', '#ffc107', '#ef4444'],
            borderWidth: 2,
            borderColor: '#ffffff' // Adds a clean slice separator
        }]
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: 15 // This reduces the chart size relative to its container
        },
        plugins: { 
            legend: { 
                position: 'bottom', 
                labels: { 
                    boxWidth: 8, 
                    font: { size: 10 },
                    padding: 20 // Space between chart and legend
                } 
            } 
        }
    }
});

// Line Chart with your date logic
const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
const today = new Date();
const currentDay = today.getDay();
const dateLabels = days.map((day, index) => {
    const d = new Date();
    const diff = d.getDate() - currentDay + (currentDay === 0 ? -6 : 1) + index;
    d.setDate(diff);
    return `${day} (${d.getDate()} ${d.toLocaleString('default', { month: 'short' })})`;
});

new Chart(document.getElementById('attendanceChart'), {
    type: 'line',
    data: {
        labels: dateLabels,
        datasets: [{
            label: 'Attendance %',
            data: [95, 92, 88, 94, 92],
            borderColor: '#2CA078',
            backgroundColor: 'rgba(44, 160, 120, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        maintainAspectRatio: false,
        scales: { y: { min: 70, max: 100 } }
    }
});
</script>
</body>
</html>