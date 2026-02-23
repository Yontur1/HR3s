<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DispatchPro - Shift & Schedule</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --border: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #3b82f6;
            --vacation: #10b981;
            --day-off: #0f172a;
            --emergency: #ef4444;
            --warning: #f59e0b;
            --role-it: #8b5cf6;
            --role-hr: #ec4899;
            --role-fin: #06b6d4;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            margin: 0;
            display: flex; /* Sidebar + Content Side-by-side */
            height: 100vh;
            overflow: hidden;
            padding: 24px;
            gap: 24px;
            box-sizing: border-box;
        }

        /* SIDEBAR STYLES (Your Provided Code) */
        .sidebar {
            width: 260px;
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            border: 1px solid var(--border);
            height: 100%;
            display: flex;
            flex-direction: column;
            z-index: 100;
            flex-shrink: 0;
        }

        .logo { 
            font-size: 20px; color: #10b981; font-weight: 700; 
            margin-bottom: 32px; cursor: default;
        }

        .nav-item {
            margin-bottom: 4px; padding: 10px 14px; border-radius: 8px;
            cursor: pointer; color: var(--text-muted);
            transition: all 0.2s ease; font-weight: 500; font-size: 14px;
            display: flex; align-items: center; gap: 12px;
        }

        .nav-item i { font-size: 16px; }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: var(--text-main); }
        .nav-item.active { background: #10b981; color: #fff; }

        /* MAIN CONTENT AREA */
        .main-workspace {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border);
            overflow: hidden;
        }

        /* Top Filter Bar */
        .top-filter-bar {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Table & Grid */
        .grid-container { flex: 1; overflow: auto; position: relative; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; min-width: 1000px; }
        th { position: sticky; top: 0; background: var(--border); padding: 12px; z-index: 20; font-size: 0.75rem; }
        
        .sticky-col {
            position: sticky; left: 0; background: var(--card-bg);
            z-index: 10; width: 220px; border-right: 2px solid var(--border);
            padding-left: 15px; cursor: pointer;
        }

        td { border: 1px solid var(--border); height: 50px; text-align: center; font-size: 0.8rem; }

        /* Status & Detail Panel */
        .shift-pill { background: var(--primary); border-radius: 4px; padding: 4px 8px; font-size: 0.7rem; }
        .day-off { background: repeating-linear-gradient(45deg, #1e293b, #1e293b 8px, #0f172a 8px, #0f172a 16px); color: var(--text-muted); }
        
        .detail-panel {
            position: fixed; right: -400px; top: 0; width: 350px; height: 100%;
            background: var(--card-bg); border-left: 1px solid var(--border);
            box-shadow: -10px 0 30px rgba(0,0,0,0.5);
            transition: 0.3s ease; z-index: 200; padding: 25px;
        }
        .panel-active { right: 0; }

        /* Inputs */
        input[type="date"], select {
            background: var(--bg-dark); border: 1px solid var(--border);
            color: white; padding: 6px; border-radius: 4px; font-size: 0.8rem;
        }
        .btn {
            background: var(--vacation); color: white; border: none; padding: 8px 16px;
            border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 0.8rem;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-workspace">
    <div class="top-filter-bar">
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: bold;">PERIOD:</span>
            <input type="date" id="startDate" value="2026-02-09" onchange="updateHeaders()">
            <span style="color: var(--text-muted)">-</span>
            <input type="date" id="endDate" value="2026-02-15">
        </div>

        <div style="display: flex; align-items: center; gap: 10px; margin-left: 20px;">
            <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: bold;">ROLE:</span>
            <select onchange="filterRole(this.value)">
                <option value="all">All Personnel</option>
                <option value="IT">IT Support</option>
                <option value="HR">Human Resources</option>
                <option value="FIN">Finance</option>
            </select>
        </div>

        <button class="btn" style="margin-left: auto;" onclick="alert('Roster Published')">Publish Schedule</button>
    </div>

    <div class="grid-container">
        <table>
            <thead id="tableHeader"></thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
</div>

<div id="detailPanel" class="detail-panel">
    <button onclick="closeDetails()" style="background:none; border:none; color:white; font-size:1.5rem; cursor:pointer; float:right;">&times;</button>
    <h3 id="panelName">Employee Name</h3>
    <p id="panelRole" style="color:var(--vacation); font-size: 0.85rem; font-weight: bold;"></p>
    <hr style="border-color: var(--border); margin: 20px 0;">
    
    <div style="background: var(--bg-dark); padding: 20px; border-radius: 12px; line-height: 2;">
        <div style="display:flex; justify-content:space-between;"><span>Shift:</span> <strong>08:00 - 17:00</strong></div>
        <div style="display:flex; justify-content:space-between; color:var(--vacation)"><span>AM Break:</span> <strong>10:15 (15m)</strong></div>
        <div style="display:flex; justify-content:space-between; color:var(--warning)"><span>Lunch:</span> <strong>12:00 (1h)</strong></div>
        <div style="display:flex; justify-content:space-between; color:var(--vacation)"><span>PM Break:</span> <strong>15:15 (15m)</strong></div>
        <div style="display:flex; justify-content:space-between; border-top: 1px solid var(--border); margin-top:15px; padding-top:10px;">
            <span>Effective Hours:</span> <strong>8.0 hrs</strong>
        </div>
    </div>
    <button class="btn" style="width: 100%; background: var(--emergency); margin-top: 20px;" onclick="alert('Manual adjustment request sent.')">Manual Conflict Request</button>
</div>

<script>
    function updateHeaders() {
        const start = new Date(document.getElementById('startDate').value);
        let html = `<tr><th class="sticky-col">Personnel</th>`;
        for (let i = 0; i < 7; i++) {
            let d = new Date(start); d.setDate(start.getDate() + i);
            html += `<th>${d.toLocaleDateString('en-US', {weekday: 'short'})}<br><span style="font-size:0.6rem; opacity:0.5">${d.toLocaleDateString()}</span></th>`;
        }
        document.getElementById('tableHeader').innerHTML = html + `</tr>`;
    }

    function renderData() {
        const roles = ['IT', 'HR', 'FIN'];
        let html = '';
        roles.forEach(r => {
            for(let i=1; i<=10; i++){
                html += `
                <tr class="emp-row" data-role="${r}">
                    <td class="sticky-col" onclick="showDetails('${r} Staff ${i}', '${r} Dept')">
                        ${r} Staff ${i} <i class="bi bi-info-circle" style="font-size:10px; opacity:0.5"></i>
                    </td>
                    <td><div class="shift-pill">08:00-17:00</div></td>
                    <td><div class="shift-pill">08:00-17:00</div></td>
                    <td><div class="shift-pill">08:00-17:00</div></td>
                    <td class="day-off">OFF</td><td class="day-off">OFF</td>
                    <td><div class="shift-pill">08:00-17:00</div></td>
                    <td><div class="shift-pill">08:00-17:00</div></td>
                </tr>`;
            }
        });
        document.getElementById('tableBody').innerHTML = html;
    }

    function showDetails(name, role) {
        document.getElementById('panelName').innerText = name;
        document.getElementById('panelRole').innerText = role;
        document.getElementById('detailPanel').classList.add('panel-active');
    }
    function closeDetails() { document.getElementById('detailPanel').classList.remove('panel-active'); }
    
    function filterRole(val) {
        document.querySelectorAll('.emp-row').forEach(row => {
            row.style.display = (val === 'all' || row.dataset.role === val) ? '' : 'none';
        });
    }

    updateHeaders(); renderData();
</script>

</body>
</html>