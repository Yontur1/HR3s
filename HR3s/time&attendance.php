<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time & Attendance | DispatchPro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --border: #334155;
            --primary: #10b981;
            --primary-hover: #059669;
            --danger: #ef4444;
            --warning: #f59e0b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            margin: 0;
            padding: 24px;
            display: flex;
            gap: 24px;
            background: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .main-content { flex: 1; max-width: 1400px; }

        /* --- SUB-NAV TABS --- */
        .sub-nav { display: flex; gap: 20px; border-bottom: 1px solid var(--border); margin-bottom: 24px; padding-bottom: 10px; }
        .nav-tab { 
            color: var(--text-muted); cursor: pointer; padding: 8px 12px; font-weight: 500; transition: 0.2s; position: relative;
        }
        .nav-tab:hover { color: white; }
        .nav-tab.active { color: var(--primary); font-weight: 600; }
        .nav-tab.active::after {
            content: ''; position: absolute; bottom: -11px; left: 0; width: 100%; height: 2px; background: var(--primary);
        }

        /* --- SECTIONS --- */
        .content-section { display: none; animation: fadeIn 0.3s ease; }
        .content-section.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }

        /* --- WEB CLOCK WIDGET --- */
        .clock-container {
            display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; text-align: center;
        }
        .live-time { font-size: 48px; font-weight: 700; font-variant-numeric: tabular-nums; letter-spacing: -1px; margin-bottom: 8px; }
        .live-date { color: var(--text-muted); font-size: 16px; margin-bottom: 30px; }
        
        .clock-actions { display: flex; gap: 20px; }
        .btn-clock {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            width: 140px; height: 140px; border-radius: 50%; border: none; cursor: pointer;
            font-weight: 600; font-size: 16px; transition: transform 0.1s;
        }
        .btn-clock:active { transform: scale(0.95); }
        
        .btn-in { background: rgba(16, 185, 129, 0.1); color: var(--primary); border: 2px solid var(--primary); }
        .btn-in:hover { background: var(--primary); color: white; }
        
        .btn-out { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 2px solid var(--danger); }
        .btn-out:hover { background: var(--danger); color: white; }

        /* --- STATS GRID --- */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
        .stat-tile { background: var(--bg-dark); padding: 20px; border-radius: 12px; border: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .stat-val { font-size: 24px; font-weight: 700; color: white; }
        .stat-label { font-size: 13px; color: var(--text-muted); }

        /* --- TABLES --- */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { text-align: left; padding: 12px 16px; font-size: 12px; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        .data-table td { padding: 16px; border-bottom: 1px solid var(--border); font-size: 14px; vertical-align: middle; }
        .data-table tr:hover { background: rgba(255,255,255,0.02); }

        .badge { font-size: 11px; padding: 4px 10px; border-radius: 20px; font-weight: 600; text-transform: uppercase; }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: var(--primary); }
        .badge-warn { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .badge-danger { background: rgba(239, 68, 68, 0.1); color: var(--danger); }

        .btn-sm { padding: 6px 12px; border-radius: 6px; border: 1px solid var(--border); background: transparent; color: var(--text-muted); cursor: pointer; font-size: 12px; }
        .btn-sm:hover { border-color: var(--text-muted); color: white; }

    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
            <h2 style="margin:0;">Time & Attendance</h2>
            <div style="font-size:13px; color:var(--text-muted);">
                <i class="bi bi-geo-alt-fill"></i> Location: <b>Main Office (IP: 192.168.1.45)</b>
            </div>
        </div>

        <div class="sub-nav">
            <div class="nav-tab active" onclick="switchTab('my-dtr', this)">My DTR (Clock In/Out)</div>
            <div class="nav-tab" onclick="switchTab('team-monitor', this)">Team Monitoring</div>
            <div class="nav-tab" onclick="switchTab('corrections', this)">Corrections & Disputes</div>
        </div>

        <div id="my-dtr" class="content-section active">
            <div class="card">
                <div class="clock-container">
                    <div class="live-time" id="clockDisplay">00:00:00</div>
                    <div class="live-date" id="dateDisplay">Monday, October 24, 2023</div>
                    
                    <div class="clock-actions">
                        <button class="btn-clock btn-in" onclick="punch('IN')">
                            <i class="bi bi-box-arrow-in-right" style="font-size:24px; margin-bottom:8px;"></i>
                            TIME IN
                        </button>
                        <button class="btn-clock btn-out" onclick="punch('OUT')">
                            <i class="bi bi-box-arrow-right" style="font-size:24px; margin-bottom:8px;"></i>
                            TIME OUT
                        </button>
                    </div>
                    <p id="punchStatus" style="margin-top:20px; height:20px; font-size:14px; color:var(--text-muted);"></p>
                </div>
            </div>

            <div class="card">
                <h3 style="font-size:16px; margin-bottom:16px;">My Recent Activity</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Total Hours</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Today</td>
                            <td>07:55 AM</td>
                            <td>-- : --</td>
                            <td>Running...</td>
                            <td><span class="badge badge-success">Active</span></td>
                        </tr>
                        <tr>
                            <td>Oct 23, 2023</td>
                            <td>08:02 AM</td>
                            <td>05:05 PM</td>
                            <td>9h 03m</td>
                            <td><span class="badge badge-warn">Late (2m)</span></td>
                        </tr>
                        <tr>
                            <td>Oct 22, 2023</td>
                            <td>07:58 AM</td>
                            <td>05:00 PM</td>
                            <td>9h 02m</td>
                            <td><span class="badge badge-success">On Time</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="team-monitor" class="content-section">
            
            <div class="stat-grid">
                <div class="stat-tile">
                    <div>
                        <div class="stat-label">Present Today</div>
                        <div class="stat-val" style="color:#10b981">42</div>
                    </div>
                    <i class="bi bi-person-check" style="font-size:24px; color:#10b981; opacity:0.5;"></i>
                </div>
                <div class="stat-tile">
                    <div>
                        <div class="stat-label">Late Arrivals</div>
                        <div class="stat-val" style="color:#f59e0b">3</div>
                    </div>
                    <i class="bi bi-alarm" style="font-size:24px; color:#f59e0b; opacity:0.5;"></i>
                </div>
                <div class="stat-tile">
                    <div>
                        <div class="stat-label">Absent / Leave</div>
                        <div class="stat-val" style="color:#ef4444">5</div>
                    </div>
                    <i class="bi bi-person-x" style="font-size:24px; color:#ef4444; opacity:0.5;"></i>
                </div>
                <div class="stat-tile">
                    <div>
                        <div class="stat-label">Field Staff</div>
                        <div class="stat-val" style="color:#3b82f6">8</div>
                    </div>
                    <i class="bi bi-truck" style="font-size:24px; color:#3b82f6; opacity:0.5;"></i>
                </div>
            </div>

            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h3 style="margin:0; font-size:18px;">Daily Attendance Log</h3>
                    <input type="text" placeholder="Search employee..." style="background:var(--bg-dark); border:1px solid var(--border); padding:8px 12px; border-radius:8px; color:white; outline:none;">
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Shift Schedule</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div><strong>Sarah Miller</strong></div>
                                <div style="font-size:12px; color:var(--text-muted)">Warehouse Ops</div>
                            </td>
                            <td>08:00 - 17:00</td>
                            <td>08:15 AM</td>
                            <td>--</td>
                            <td>Office Wifi</td>
                            <td><span class="badge badge-warn">Late</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div><strong>Mike Chen</strong></div>
                                <div style="font-size:12px; color:var(--text-muted)">Driver</div>
                            </td>
                            <td>08:00 - 17:00</td>
                            <td>07:45 AM</td>
                            <td>05:30 PM</td>
                            <td>GPS: Zone A</td>
                            <td><span class="badge badge-success">On Time</span></td>
                        </tr>
                        <tr>
                            <td>
                                <div><strong>John Doe</strong></div>
                                <div style="font-size:12px; color:var(--text-muted)">Manager</div>
                            </td>
                            <td>09:00 - 18:00</td>
                            <td>--</td>
                            <td>--</td>
                            <td>--</td>
                            <td><span class="badge badge-danger">Absent</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="corrections" class="content-section">
            <div class="card">
                <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                    <h3 style="margin:0; font-size:18px;">Pending Correction Requests</h3>
                    <button class="btn-sm" style="background:var(--primary); color:white; border:none;">+ New Request</button>
                </div>
                <p style="font-size:13px; color:var(--text-muted); margin-bottom:20px;">Employees can request adjustments if they forgot to clock in/out or had device issues.</p>
                
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Date Issue</th>
                            <th>Original Log</th>
                            <th>Requested Change</th>
                            <th>Reason</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sarah Miller</td>
                            <td>Oct 23</td>
                            <td>No Time Out</td>
                            <td>Set Out: 05:00 PM</td>
                            <td>Forgot ID badge</td>
                            <td>
                                <button class="btn-sm" style="color:var(--primary); border-color:var(--primary)">Approve</button>
                                <button class="btn-sm" style="color:var(--danger); border-color:var(--danger)">Deny</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        // --- TAB SWITCHING ---
        function switchTab(tabId, navElement) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-tab').forEach(el => el.classList.remove('active'));

            // Show target
            document.getElementById(tabId).classList.add('active');
            navElement.classList.add('active');
        }

        // --- REAL-TIME CLOCK ---
        function updateClock() {
            const now = new Date();
            document.getElementById('clockDisplay').innerText = now.toLocaleTimeString('en-US', { hour12: false });
            document.getElementById('dateDisplay').innerText = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        setInterval(updateClock, 1000);
        updateClock();

        // --- MOCK PUNCH FUNCTION ---
        function punch(type) {
            const status = document.getElementById('punchStatus');
            const time = new Date().toLocaleTimeString();
            
            if(type === 'IN') {
                status.style.color = "#10b981";
                status.innerText = "✅ Successfully Timed IN at " + time;
            } else {
                status.style.color = "#ef4444";
                status.innerText = "🛑 Successfully Timed OUT at " + time;
            }

            // Reset msg after 3s
            setTimeout(() => { status.innerText = ""; }, 3000);
        }
    </script>
</body>
</html>