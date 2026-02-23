<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* SIDEBAR STYLES */
    .sidebar {
        width: 260px;
        background: #1e293b;
        background: var(--card-bg, #1e293b);
        border-radius: 16px;
        padding: 24px;
        border: 1px solid #334155;
        border: 1px solid var(--border, #334155);
        height: calc(100vh - 48px);
        position: sticky;
        top: 24px;
        display: flex;
        flex-direction: column;
        z-index: 100;
    }

    .logo { 
        font-size: 20px; 
        color: #10b981; 
        font-weight: 700; 
        margin-bottom: 32px; 
        cursor: default;
    }

    .nav-item {
        margin-bottom: 4px;
        padding: 10px 14px;
        border-radius: 8px;
        cursor: pointer;
        color: #94a3b8;
        color: var(--text-muted, #94a3b8);
        transition: all 0.2s ease;
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .nav-item i { font-size: 16px; }

    .nav-item:hover { 
        background: rgba(255,255,255,0.05); 
        color: #f8fafc;
        color: var(--text-main, #f8fafc);
    }

    .nav-item.active { 
        background: #10b981; 
        color: #fff; 
    }
    
    .nav-badge {
        background: #ef4444;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: auto;
    }
</style>

<div class="sidebar">
    <div class="logo">🏢 DispatchPro</div>
    
    <div class="nav-item" onclick="window.location.href='shift&schedule.php'">
        <i class="bi bi-calendar-event"></i> Shift & Schedule
    </div>

    <div class="nav-item" onclick="window.location.href='timesheets.php'">
        <i class="bi bi-file-earmark-spreadsheet"></i> Timesheets
    </div>
    
    <div class="nav-item" onclick="window.location.href='claims.php'">
        <i class="bi bi-cash-coin"></i> Claims & Reimb.
    </div>

    <div class="nav-item" onclick="window.location.href='leave.php'">
        <i class="bi bi-calendar-minus"></i> Leave Request
    </div>
    
    <div class="nav-item" onclick="window.location.href='employees.php'">
        <i class="bi bi-people-fill"></i> Employees
    </div>

    <div class="nav-item" onclick="window.location.href='time&attendance.php'">
        <i class="bi bi-clock-history"></i> Time & Attendance
    </div>
    
    <div class="nav-item" onclick="window.location.href='settings.php'">
        <i class="bi bi-gear-fill"></i> Settings
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let currentPage = decodeURIComponent(window.location.pathname.split("/").pop());
        if (currentPage === "") currentPage = "index.php";

        const navItems = document.querySelectorAll('.nav-item');

        navItems.forEach(item => {
            item.classList.remove('active');
            const onclickAttr = item.getAttribute('onclick');
            if (onclickAttr && onclickAttr.includes(currentPage)) {
                item.classList.add('active');
            }
        });
    });
</script>