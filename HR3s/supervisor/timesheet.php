<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$current_page = 'timesheets'; 
include('sidebar.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR4 Payroll Timesheet | HR3 System</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/timesheet.css">
    
</head>
<body class="<?php echo (isset($_SESSION['theme']) && $_SESSION['theme'] == 'dark') ? 'dark-mode' : ''; ?>">

<div class="main-content">
    <div class="page-header">
        <div>
            <h1>Department Timesheets</h1>
            <p class="subtitle">Period: Feb 01 - Feb 28, 2026</p>
        </div>
        
        <div class="dept-selector">
            <label for="deptFilter" style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;">View Department:</label>
            <select id="deptFilter" class="form-select" onchange="filterTimesheet()">
                <option value="all">All Departments</option>
                <option value="logistics" selected>Logistics</option>
                <option value="it">Information Technology</option>
                <option value="finance">Finance</option>
            </select>
        </div>
    </div>

    <div class="kpi-row">
        <div class="kpi-card">
            <div class="kpi-icon icon-blue"><i class="fas fa-users"></i></div>
            <div>
                <span class="kpi-val" id="totalEmployees">0</span>
                <span class="kpi-lab">Filtered Employees</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon icon-green"><i class="fas fa-stopwatch"></i></div>
            <div>
                <span class="kpi-val" id="totalHours">0.00</span>
                <span class="kpi-lab">Filtered Payable Hours</span>
            </div>
        </div>
    </div>

    <div class="toolbar">
        <div class="search-box-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchInput" class="search-box" placeholder="Search employee..." onkeyup="filterTimesheet()">
        </div>
        <div class="action-buttons">
            
            <div class="filter-container">
                <button class="btn btn-outline" onclick="toggleFilterMenu()">
                    <i class="fas fa-filter"></i> Columns <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-left: 5px;"></i>
                </button>
                
                <div class="filter-dropdown" id="filterMenu">
                    <h3 style="margin: 0 0 10px 0; font-size: 0.85rem; font-weight: 700; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">Displayed Data</h3>
                    
                    <label class="filter-checkbox"><input type="checkbox" data-col="col-role" checked> Role</label>
                    <label class="filter-checkbox"><input type="checkbox" data-col="col-reg" checked> Regular Hours</label>
                    <label class="filter-checkbox"><input type="checkbox" data-col="col-ot" checked> Overtime</label>
                    <label class="filter-checkbox"><input type="checkbox" data-col="col-abs" checked> Absences</label>
                    
                    <h4 style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; margin: 12px 0 8px;">Leave Tracking</h4>
                    <label class="filter-checkbox"><input type="checkbox" data-col="col-lc" checked> Leave Credit</label>
                    <label class="filter-checkbox"><input type="checkbox" data-col="col-pl" checked> Paid Leave Usage</label>
                    <label class="filter-checkbox"><input type="checkbox" data-col="col-el" checked> Excess Leave (Unpaid)</label>
                    
                    <h4 style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; margin: 12px 0 8px;">Final Computation</h4>
                    <label class="filter-checkbox"><input type="checkbox" data-col="col-ded" checked> Deductions</label>
                    <label class="filter-checkbox"><input type="checkbox" checked disabled> Final Hours (Required)</label>
                    <label class="filter-checkbox"><input type="checkbox" checked disabled> Status (Required)</label>

                    <button class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 15px; padding: 8px;" onclick="applyFilters()">
                        Apply View
                    </button>
                </div>
            </div>

            <button class="btn btn-outline" onclick="exportTable()">
                <i class="fas fa-file-excel"></i> Convert to Excel
            </button>
            <button class="btn btn-primary" onclick="archiveSelected()">
                <i class="fas fa-archive"></i> Archive Selected
            </button>
        </div>
    </div>

    <div class="table-card">
        <table id="timesheetTable">
            <thead>
                <tr>
                    <th class="col-emp">Employee</th>
                    <th class="col-role">Role</th>
                    <th class="col-reg">Regular</th>
                    <th class="col-ot">Overtime</th>
                    <th class="col-abs">Absences</th>
                    <th class="col-lc">Leave Credit</th>
                    <th class="col-pl">Paid Leave</th>
                    <th class="col-el">Excess Leave</th>
                    <th class="col-ded">Deductions</th>
                    <th class="col-fh">Final Hours</th>
                    <th class="col-stat">Status</th>
                </tr>
            </thead>
            <tbody id="tsBody">
                <tr data-department="logistics" onclick="toggleRowSelection(this)">
                    <td class="col-emp"><strong>Bob Logistics</strong></td>
                    <td class="col-role">Warehouse Supervisor</td>
                    <td class="col-reg">72.00</td>
                    <td class="col-ot">0.00</td>
                    <td class="col-abs">12.00</td>
                    <td class="col-lc">8.00</td>
                    <td class="col-pl"><span class="badge-yes">Yes (8h)</span></td>
                    <td class="col-el"><strong style="color: #e74c3c;">4.00</strong></td>
                    <td class="col-ded">4.00</td>
                    <td class="col-fh"><strong>76.00</strong></td>
                    <td class="col-stat"><span class="status-badge bg-pending">Pending</span></td>
                </tr>
                <tr data-department="it" onclick="toggleRowSelection(this)">
                    <td class="col-emp"><strong>Alice Dev</strong></td>
                    <td class="col-role">Software Engineer</td>
                    <td class="col-reg">80.00</td>
                    <td class="col-ot">5.00</td>
                    <td class="col-abs">0.00</td>
                    <td class="col-lc">16.00</td>
                    <td class="col-pl"><span class="badge-no">No</span></td>
                    <td class="col-el">0.00</td>
                    <td class="col-ded">0.00</td>
                    <td class="col-fh"><strong class="text-green">85.00</strong></td>
                    <td class="col-stat"><span class="status-badge bg-approved">Approved</span></td>
                </tr>
            </tbody>
        </table>
        
        <div class="table-controls">
            <button class="btn btn-outline btn-sm" onclick="selectAllVisible()">Select All</button>
            <button class="btn btn-outline btn-sm" onclick="resetSelection()">Reset</button>
        </div>
    </div>
</div>

<script>
    const tbody = document.getElementById('tsBody');

    // Populate extra demo rows dynamically
    const departments = ['logistics', 'it', 'finance'];
    for(let i=3; i<=12; i++){
        const dept = departments[i % 3];
        const regular = 80;
        
        // Simulating random leave data for demo purposes
        const abs = (i % 4 === 0) ? 16 : 0; 
        const lc = 8.00;
        const paidLve = (abs > 0 && abs <= lc) ? abs : (abs > lc ? lc : 0);
        const excess = (abs > lc) ? (abs - lc) : 0;
        const ded = excess; // Deduct only the excess
        const finalHours = regular - ded;

        const row = document.createElement('tr');
        row.setAttribute('data-department', dept);
        row.setAttribute('onclick', 'toggleRowSelection(this)');
        row.innerHTML = `
            <td class="col-emp"><strong>Employee ${i}</strong></td>
            <td class="col-role">${dept.charAt(0).toUpperCase() + dept.slice(1)} Staff</td>
            <td class="col-reg">${regular.toFixed(2)}</td>
            <td class="col-ot">0.00</td>
            <td class="col-abs">${abs.toFixed(2)}</td>
            <td class="col-lc">${lc.toFixed(2)}</td>
            <td class="col-pl">${paidLve > 0 ? `<span class="badge-yes">Yes (${paidLve}h)</span>` : `<span class="badge-no">No</span>`}</td>
            <td class="col-el"><strong style="color: ${excess > 0 ? '#e74c3c' : 'inherit'}">${excess.toFixed(2)}</strong></td>
            <td class="col-ded">${ded.toFixed(2)}</td>
            <td class="col-fh"><strong>${finalHours.toFixed(2)}</strong></td>
            <td class="col-stat"><span class="status-badge bg-approved">Approved</span></td>
        `;
        tbody.appendChild(row);
    }

    // --- Interactive Functions ---
    
    function toggleRowSelection(row) {
        row.classList.toggle('row-selected');
    }

    function selectAllVisible() {
        const rows = tbody.getElementsByTagName('tr');
        for(let row of rows) {
            if (row.style.display !== 'none') {
                row.classList.add('row-selected');
            }
        }
    }

    function resetSelection() {
        const rows = tbody.getElementsByTagName('tr');
        for(let row of rows) {
            row.classList.remove('row-selected');
        }
    }

    // --- Dropdown Filter Logic ---
    
    function toggleFilterMenu() {
        document.getElementById('filterMenu').classList.toggle('show');
    }

    // Close dropdown if clicked outside
    window.onclick = function(event) {
        if (!event.target.closest('.filter-container')) {
            const dropdowns = document.getElementsByClassName("filter-dropdown");
            for (let i = 0; i < dropdowns.length; i++) {
                let openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

    function toggleColumn(className, isVisible) {
        const cells = document.querySelectorAll('.' + className);
        cells.forEach(cell => {
            cell.style.display = isVisible ? '' : 'none';
        });
    }

    function applyFilters() {
        const checkboxes = document.querySelectorAll('.filter-dropdown input[type="checkbox"][data-col]');
        checkboxes.forEach(cb => {
            toggleColumn(cb.getAttribute('data-col'), cb.checked);
        });
        document.getElementById('filterMenu').classList.remove('show');
    }

    // --- Core Features ---

    function filterTimesheet() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const deptFilter = document.getElementById('deptFilter').value;
        const rows = tbody.getElementsByTagName('tr');

        for(let row of rows) {
            const text = row.innerText.toLowerCase();
            const dept = row.getAttribute('data-department');
            const matchesSearch = text.includes(searchInput);
            const matchesDept = (deptFilter === 'all' || dept === deptFilter);

            if (matchesSearch && matchesDept) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
                row.classList.remove('row-selected');
            }
        }
        calculateTotals();
    }

    function calculateTotals(){
        let total = 0;
        let visibleCount = 0;
        for(let row of tbody.rows){
            if (row.style.display !== 'none') {
                const finalHourText = row.querySelector('.col-fh').innerText;
                total += parseFloat(finalHourText); 
                visibleCount++;
            }
        }
        document.getElementById('totalEmployees').innerText = visibleCount;
        document.getElementById('totalHours').innerText = total.toLocaleString(undefined, {minimumFractionDigits: 2});
    }

    filterTimesheet();

    function exportTable(){
        let tableClone = document.getElementById('timesheetTable').cloneNode(true);
        
        let rows = tableClone.getElementsByTagName('tr');
        for(let row of rows) { row.classList.remove('row-selected'); }
        
        let cells = tableClone.querySelectorAll('th, td');
        cells.forEach(cell => { cell.style.display = ''; });
        
        let tableHTML = tableClone.outerHTML;
        let a = document.createElement('a');
        a.href = 'data:application/vnd.ms-excel,' + encodeURIComponent(tableHTML);
        a.download = 'hr4_department_timesheet_converted.xls';
        a.click();
    }

    function archiveSelected(){
        const selectedRows = document.querySelectorAll('.row-selected');
        if(selectedRows.length === 0) return alert("Please select records to archive.");
        selectedRows.forEach(row => row.remove());
        calculateTotals();
    }
</script>

</body>
</html>