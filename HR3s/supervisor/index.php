<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DispatchPro - Supervisor Hub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-dark: #0f172a; --card-bg: #1e293b; --border: #334155;
            --text-main: #f8fafc; --text-muted: #94a3b8; --primary: #3b82f6;
            --vacation: #10b981; --warning: #f59e0b; --emergency: #ef4444;
        }
        body { background: var(--bg-dark); color: var(--text-main); font-family: 'Inter', sans-serif; margin: 0; display: flex; height: 100vh; padding: 24px; gap: 24px; box-sizing: border-box; }
        
        /* SIDEBAR */
        .sidebar { width: 260px; background: var(--card-bg); border-radius: 16px; padding: 24px; border: 1px solid var(--border); height: 100%; display: flex; flex-direction: column; }
        .logo { font-size: 20px; color: #10b981; font-weight: 700; margin-bottom: 32px; }
        .nav-item { margin-bottom: 4px; padding: 10px 14px; border-radius: 8px; cursor: pointer; color: var(--text-muted); display: flex; align-items: center; gap: 12px; transition: 0.2s; }
        .nav-item.active { background: #10b981; color: #fff; }

        /* MAIN CONTENT */
        .workspace { flex: 1; overflow-y: auto; }
        .hub-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 30px; }
        .hub-card { background: var(--card-bg); padding: 32px; border-radius: 20px; border: 1px solid var(--border); cursor: pointer; transition: 0.3s; position: relative; }
        .hub-card:hover { transform: translateY(-5px); border-color: var(--primary); }
        .hub-card i { font-size: 2.5rem; margin-bottom: 20px; display: block; }
        .counter { position: absolute; top: 20px; right: 20px; background: var(--emergency); color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">🏢 DispatchPro</div>
        <div class="nav-item active"><i class="bi bi-speedometer2"></i> Dashboard</div>
        <div class="nav-item" onclick="location.href='attendance.html'"><i class="bi bi-clock-history"></i> Attendance</div>
        <div class="nav-item" onclick="location.href='scheduling.html'"><i class="bi bi-calendar3"></i> Scheduling</div>
        <div class="nav-item" onclick="location.href='leave.html'"><i class="bi bi-calendar-minus"></i> Leave Requests</div>
        <div class="nav-item" onclick="location.href='claims.html'"><i class="bi bi-cash-coin"></i> Claims Review</div>
    </div>

    <div class="workspace">
        <h1>Supervisor Command Center</h1>
        <p style="color: var(--text-muted);">Manage team operations and initial validations.</p>
        
        <div class="hub-grid">
            <div class="hub-card" onclick="location.href='attendance.php'">
                <span class="counter">3</span>
                <i class="bi bi-clock-history" style="color: var(--emergency);"></i>
                <h2>Attendance Validation</h2>
                <p style="color: var(--text-muted);">Verify lateness, undertime, and absences from logs.</p>
            </div>
            <div class="hub-card" onclick="location.href='scheduling.html'">
                <i class="bi bi-calendar3" style="color: var(--primary);"></i>
                <h2>Shift & Scheduling</h2>
                <p style="color: var(--text-muted);">Create new shifts and assign employees to weekly rosters.</p>
            </div>
            <div class="hub-card" onclick="location.href='leave.html'">
                <span class="counter">2</span>
                <i class="bi bi-calendar-minus" style="color: var(--vacation);"></i>
                <h2>Leave & Manpower</h2>
                <p style="color: var(--text-muted);">Assess team coverage impact before initial approval.</p>
            </div>
            <div class="hub-card" onclick="location.href='claims.html'">
                <span class="counter">5</span>
                <i class="bi bi-cash-coin" style="color: var(--warning);"></i>
                <h2>Claims Review</h2>
                <p style="color: var(--text-muted);">Validate receipt legitimacy and initial claim review.</p>
            </div>
        </div>
    </div>
</body>
</html>