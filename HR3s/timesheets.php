<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DispatchPro - Timesheets</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-dark: #0f172a; --card-bg: #1e293b; --border: #334155;
            --text-main: #f8fafc; --text-muted: #94a3b8; --primary: #3b82f6;
            --vacation: #10b981; --warning: #f59e0b; --emergency: #ef4444;
        }

        body {
            background-color: var(--bg-dark); color: var(--text-main);
            font-family: 'Inter', sans-serif; margin: 0; display: flex;
            height: 100vh; overflow: hidden; padding: 24px; gap: 24px; box-sizing: border-box;
        }

        /* SIDEBAR (Reuse your exact component) */
        .sidebar {
            width: 260px; background: var(--card-bg); border-radius: 16px;
            padding: 24px; border: 1px solid var(--border); height: 100%;
            display: flex; flex-direction: column; flex-shrink: 0;
        }
        .logo { font-size: 20px; color: #10b981; font-weight: 700; margin-bottom: 32px; }
        .nav-item {
            margin-bottom: 4px; padding: 10px 14px; border-radius: 8px; cursor: pointer;
            color: var(--text-muted); font-size: 14px; display: flex; align-items: center; gap: 12px;
        }
        .nav-item.active { background: #10b981; color: #fff; }

        /* TIMESHEET CONTENT */
        .main-workspace {
            flex: 1; display: flex; flex-direction: column;
            background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border); overflow: hidden;
        }

        .header-bar {
            padding: 20px; border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
        }

        .status-pill {
            font-size: 10px; padding: 2px 8px; border-radius: 10px; font-weight: bold; text-transform: uppercase;
        }
        .status-pending { background: rgba(245, 158, 11, 0.2); color: var(--warning); border: 1px solid var(--warning); }
        .status-approved { background: rgba(16, 185, 129, 0.2); color: var(--vacation); border: 1px solid var(--vacation); }

        /* Grid */
        .table-container { flex: 1; overflow: auto; padding: 0 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; padding: 15px; border-bottom: 2px solid var(--border); font-size: 0.8rem; color: var(--text-muted); }
        td { padding: 15px; border-bottom: 1px solid var(--border); font-size: 0.85rem; }

        .btn {
            background: var(--primary); color: white; border: none; padding: 6px 12px;
            border-radius: 4px; cursor: pointer; font-size: 0.75rem;
        }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text-muted); }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-workspace">
    <div class="header-bar">
        <div>
            <h2 style="margin:0;">Timesheet Validation</h2>
            <p style="margin:5px 0 0; font-size: 0.75rem; color: var(--text-muted);">Processing Period: Feb 01 - Feb 15, 2026</p>
        </div>
        <div style="display:flex; gap: 10px;">
            <button class="btn btn-outline"><i class="bi bi-download"></i> Export CSV</button>
            <button class="btn" onclick="alert('Batch Approved and sent to Payroll')">Approve All</button>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>EMPLOYEE</th>
                    <th>REGULAR HRS</th>
                    <th>OVERTIME</th>
                    <th>LEAVE HRS</th>
                    <th>TOTAL HRS</th>
                    <th>STATUS</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody id="tsBody">
                <tr>
                    <td><strong>Alice Dev</strong><br><small style="color:var(--text-muted)">IT Dept</small></td>
                    <td>80.00</td>
                    <td>5.50</td>
                    <td>8.00</td>
                    <td>93.50</td>
                    <td><span class="status-pill status-approved">Approved</span></td>
                    <td><button class="btn btn-outline">Details</button></td>
                </tr>
                <tr>
                    <td><strong>Bob Logistics</strong><br><small style="color:var(--text-muted)">Operations</small></td>
                    <td>72.00</td>
                    <td>0.00</td>
                    <td>16.00</td>
                    <td>88.00</td>
                    <td><span class="status-pill status-pending">Pending HR</span></td>
                    <td>
                        <button class="btn" style="background:var(--warning)" onclick="alert('Opening Manual Correction Mode')">Review</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Logic to fill 30 sample rows
    const tbody = document.getElementById('tsBody');
    for(let i=3; i<=30; i++) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>Staff User ${i}</strong><br><small style="color:var(--text-muted)">General Staff</small></td>
            <td>80.00</td><td>0.00</td><td>0.00</td><td>80.00</td>
            <td><span class="status-pill status-approved">Approved</span></td>
            <td><button class="btn btn-outline">Details</button></td>
        `;
        tbody.appendChild(row);
    }
</script>

</body>
</html>