<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supervisor Roster | HR3</title>
    <link rel="stylesheet" href="css/scheduling.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<?php 
    $current_page = 'schedule'; 
    include('sidebar.php'); 
?>

<div class="main">
    <div class="header" style="margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-weight: 800; font-size: 1.8rem;">Shift Master Console</h1>
            <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
                <span style="background: rgba(44,160,120,0.1); color: var(--brand-green); padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700;">LOGISTICS DEPT</span>
                <p style="color: var(--text-muted); font-size: 0.85rem;">Week 08: Feb 23 — Mar 01, 2026</p>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px; align-items:center;">
            <div class="search-container" style="position: relative;">
                <i class="bi bi-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" id="empSearch" placeholder="Search employee..." onkeyup="filterRoster()" 
                       style="padding: 10px 15px 10px 35px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--card-bg); color: var(--text-main); outline: none; width: 250px;">
            </div>
            <button class="btn btn-outline" style="width: auto; padding: 0.7rem 1.2rem;" onclick="location.reload()">
                <i class="bi bi-trash"></i> Reset Week
            </button>
            <button class="btn btn-primary" style="width: auto; padding: 0.7rem 1.2rem;" onclick="publishRoster()">
                <i class="bi bi-cloud-arrow-up"></i> Publish
            </button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card" style="padding: 1.2rem; display: flex; align-items: center; gap: 15px;">
            <div style="background: rgba(44,160,120,0.1); color: var(--brand-green); width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;"><i class="bi bi-people"></i></div>
            <div><p style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Active Personnel</p><p style="font-weight: 800; font-size: 1.2rem;">24</p></div>
        </div>
        <div class="card" style="padding: 1.2rem; display: flex; align-items: center; gap: 15px;">
            <div style="background: rgba(59,130,246,0.1); color: #3b82f6; width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;"><i class="bi bi-pie-chart"></i></div>
            <div><p style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Target Coverage</p><p style="font-weight: 800; font-size: 1.2rem;">100%</p></div>
        </div>
        <div class="card" style="padding: 1.2rem; display: flex; align-items: center; gap: 15px;">
            <div style="background: rgba(239,68,68,0.1); color: var(--danger); width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;"><i class="bi bi-clock-history"></i></div>
            <div><p style="font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Pending Changes</p><p style="font-weight: 800; font-size: 1.2rem;" id="unsavedCount">0</p></div>
        </div>
    </div>

    <div class="schedule-container">
        <div class="card" style="height: fit-content;">
            <div class="card-title">Select Shift</div>
            <div id="blueprintList">
                <div class="template-item" onclick="selectTemplate('morning', this)">
                    <div style="display:flex; align-items:center;">
                        <span style="width:10px; height:10px; border-radius:3px; background:var(--brand-green); margin-right:12px;"></span>
                        <div><div style="font-weight:700; font-size:13px;">Morning (AM)</div><div style="font-size:11px; color:var(--text-muted);">06:00 — 14:00</div></div>
                    </div>
                </div>
                <div class="template-item" onclick="selectTemplate('mid', this)">
                    <div style="display:flex; align-items:center;">
                        <span style="width:10px; height:10px; border-radius:3px; background:#3b82f6; margin-right:12px;"></span>
                        <div><div style="font-weight:700; font-size:13px;">Mid-Day (MD)</div><div style="font-size:11px; color:var(--text-muted);">14:00 — 22:00</div></div>
                    </div>
                </div>
                <div class="template-item" onclick="selectTemplate('night', this)">
                    <div style="display:flex; align-items:center;">
                        <span style="width:10px; height:10px; border-radius:3px; background:#8b5cf6; margin-right:12px;"></span>
                        <div><div style="font-weight:700; font-size:13px;">Graveyard (GY)</div><div style="font-size:11px; color:var(--text-muted);">22:00 — 06:00</div></div>
                    </div>
                </div>
                <div class="template-item" onclick="selectTemplate('off', this)">
                    <div style="display:flex; align-items:center;">
                        <span style="width:10px; height:10px; border-radius:3px; background:var(--danger); margin-right:12px;"></span>
                        <div><div style="font-weight:700; font-size:13px;">Day Off</div><div style="font-size:11px; color:var(--text-muted);">Unavailable</div></div>
                    </div>
                </div>
                <hr style="border:0; border-top:1px solid var(--border-color); margin:1rem 0;">
                <div class="template-item is-selected" onclick="selectTemplate(null, this)" id="eraserTool">
                    <div style="display:flex; align-items:center;">
                        <i class="bi bi-eraser-fill" style="margin-right:12px; color:var(--text-muted);"></i>
                        <div style="font-weight:700; font-size:13px; color:var(--text-muted);">Eraser Tool</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="overflow-x: auto;">
                <table class="roster-table">
                    <thead>
                        <tr>
                            <th class="emp-column">Staff Member</th>
                            <th>MON <br><span style="font-size: 10px; opacity: 0.5;">23 FEB</span></th>
                            <th>TUE <br><span style="font-size: 10px; opacity: 0.5;">24 FEB</span></th>
                            <th>WED <br><span style="font-size: 10px; opacity: 0.5;">25 FEB</span></th>
                            <th>THU <br><span style="font-size: 10px; opacity: 0.5;">26 FEB</span></th>
                            <th>FRI <br><span style="font-size: 10px; opacity: 0.5;">27 FEB</span></th>
                            <th>SAT <br><span style="font-size: 10px; opacity: 0.5;">28 FEB</span></th>
                            <th>SUN <br><span style="font-size: 10px; opacity: 0.5;">01 MAR</span></th>
                        </tr>
                    </thead>
                    <tbody id="rosterBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const employees = [
        { id: 101, name: "Arnel Reyes", role: "Warehouse Supervisor", code: "AR" },
        { id: 102, name: "Beatrice Gomez", role: "Senior Dispatcher", code: "BG" },
        { id: 103, name: "Carlos Diaz", role: "Fleet Lead", code: "CD" },
        { id: 104, name: "David Santos", role: "Logistics Clerk", code: "DS" },
        { id: 105, name: "Elena Cruz", role: "Inventory Analyst", code: "EC" },
        { id: 106, name: "Franklin Yu", role: "Fleet Support", code: "FY" }
    ];

    let selectedShift = null;
    let unsavedChanges = 0;

    const shiftData = {
        'morning': { name: 'AM', class: 'shift-morning', time: '06-14' },
        'mid': { name: 'MD', class: 'shift-mid', time: '14-22' },
        'night': { name: 'GY', class: 'shift-night', time: '22-06' },
        'off': { name: 'OFF', class: 'shift-off', time: 'REST' }
    };

    function renderRoster() {
        const tbody = document.getElementById('rosterBody');
        tbody.innerHTML = employees.map(emp => `
            <tr data-name="${emp.name.toUpperCase()}">
                <td class="emp-column">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--brand-dark); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; border: 1px solid var(--border-color);">${emp.code}</div>
                        <div>
                            <div style="font-weight: 700; font-size: 13px;">${emp.name}</div>
                            <div style="font-size: 10px; color: var(--text-muted);">${emp.role}</div>
                        </div>
                    </div>
                </td>
                ${[...Array(7)].map((_, i) => `
                    <td onclick="applyShift(this)">
                        <div class="shift-slot"><i class="bi bi-plus" style="opacity:0.1;"></i></div>
                    </td>
                `).join('')}
            </tr>
        `).join('');
    }

    function selectTemplate(type, el) {
        selectedShift = type;
        document.querySelectorAll('#blueprintList .template-item').forEach(item => {
            item.classList.remove('is-selected');
        });
        el.classList.add('is-selected');
    }

    function applyShift(td) {
        const slot = td.querySelector('.shift-slot');
        if (!selectedShift) {
            slot.className = 'shift-slot';
            slot.innerHTML = '<i class="bi bi-plus" style="opacity:0.1;"></i>';
        } else {
            const data = shiftData[selectedShift];
            slot.className = `shift-slot assigned ${data.class}`;
            slot.innerHTML = `<div style="font-size: 11px; line-height: 1;">${data.name}</div><div style="font-size: 8px; opacity: 0.7; margin-top: 2px;">${data.time}</div>`;
        }
        
        unsavedChanges++;
        document.getElementById('unsavedCount').innerText = unsavedChanges;
    }

    function filterRoster() {
        const q = document.getElementById('empSearch').value.toUpperCase();
        document.querySelectorAll('#rosterBody tr').forEach(tr => {
            tr.style.display = tr.dataset.name.includes(q) ? "" : "none";
        });
    }

    function publishRoster() { 
        alert("Roster Published Successfully!"); 
        unsavedChanges = 0;
        document.getElementById('unsavedCount').innerText = "0";
    }

    renderRoster();
</script>
</body>
</html>