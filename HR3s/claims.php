<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DispatchPro | Claims & Reimbursement</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --border: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #3b82f6;
            --gold: #fbbf24;      /* Pending/Money */
            --success: #10b981;   /* Approved */
            --danger: #ef4444;    /* Rejected/Over Budget */
        }

        body {
            margin: 0; padding: 24px; display: flex; gap: 24px;
            background: var(--bg-dark); color: var(--text-main);
            font-family: 'Inter', sans-serif; min-height: 100vh;
        }

        .main-content { flex: 1; max-width: 1600px; display: flex; flex-direction: column; gap: 24px; }

        /* --- DASHBOARD WIDGETS --- */
        .budget-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 20px; }
        
        .stat-card {
            background: var(--card-bg); border: 1px solid var(--border); padding: 24px; border-radius: 16px;
            display: flex; flex-direction: column; justify-content: center; position: relative; overflow: hidden;
        }

        .stat-label { font-size: 13px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 8px; }
        .stat-val { font-size: 32px; font-weight: 700; color: white; letter-spacing: -1px; }
        .stat-sub { font-size: 13px; margin-top: 4px; color: var(--text-muted); }

        /* Progress Bar for Budget */
        .progress-container { height: 8px; background: rgba(255,255,255,0.1); border-radius: 4px; margin-top: 16px; overflow: hidden; display: flex; }
        .prog-spent { background: var(--success); height: 100%; }
        .prog-pending { background: var(--gold); height: 100%; }

        /* --- TABLE STYLES --- */
        .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
        .card-header { padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }

        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th { 
            text-align: left; padding: 16px; font-size: 12px; color: var(--text-muted); 
            text-transform: uppercase; border-bottom: 1px solid var(--border); background: rgba(15, 23, 42, 0.5);
        }
        .data-table td { padding: 16px; border-bottom: 1px solid var(--border); font-size: 14px; vertical-align: middle; }
        .data-table tr:hover { background: rgba(255,255,255,0.02); }

        /* --- COMPONENTS --- */
        .badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .cat-travel { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
        .cat-food { background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.2); }
        .cat-supplies { background: rgba(168, 85, 247, 0.1); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.2); }

        .receipt-link { color: var(--text-muted); text-decoration: none; font-size: 13px; display: flex; align-items: center; gap: 6px; transition: 0.2s; }
        .receipt-link:hover { color: white; text-decoration: underline; }

        .btn { padding: 8px 16px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 13px; transition: 0.2s; }
        .btn-approve { background: rgba(16, 185, 129, 0.1); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.2); }
        .btn-approve:hover { background: var(--success); color: white; }
        
        .btn-reject { background: rgba(239, 68, 68, 0.1); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); }
        .btn-reject:hover { background: var(--danger); color: white; }

        .btn-primary { background: var(--primary); color: white; }
        
        /* Modal */
        .modal-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center; z-index: 100;
        }
        .modal-box { background: var(--card-bg); border: 1px solid var(--border); border-radius: 12px; width: 400px; padding: 24px; text-align: center; }
        .receipt-img { max-width: 100%; border-radius: 8px; border: 1px solid var(--border); margin-top: 10px; }

    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h2 style="margin:0;">Claims & Reimbursement</h2>
                <p style="margin:4px 0 0 0; color:var(--text-muted); font-size:13px;">Manage expenses, validate receipts, and track budget.</p>
            </div>
            <button class="btn btn-primary" onclick="alert('Opens Form WBS 5.1')"><i class="bi bi-plus-lg"></i> New Claim</button>
        </div>

        <div class="budget-grid">
            <div class="stat-card">
                <div style="display:flex; justify-content:space-between;">
                    <span class="stat-label">Monthly Budget (Oct)</span>
                    <span class="stat-label" style="color:white;">P 5,000.00 Limit</span>
                </div>
                <div class="progress-container">
                    <div id="bar-spent" class="prog-spent" style="width: 24%"></div> <div id="bar-pending" class="prog-pending" style="width: 9%"></div> </div>
                <div style="display:flex; justify-content:space-between; margin-top:8px; font-size:12px; color:var(--text-muted);">
                    <span><i class="bi bi-circle-fill" style="color:var(--success); font-size:8px;"></i> Spent: P 1,200</span>
                    <span><i class="bi bi-circle-fill" style="color:var(--gold); font-size:8px;"></i> Pending: <span id="dash-pending-mini">$450</span></span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-label" style="color:var(--gold);">Pending Approval</div>
                <div class="stat-val" style="color:var(--gold);">P <span id="dash-pending">450.00</span></div>
                <div class="stat-sub">3 Requests waiting</div>
            </div>

             <div class="stat-card">
                <div class="stat-label" style="color:var(--success);">Total Approved</div>
                <div class="stat-val" style="color:var(--success);">P <span id="dash-spent">1,200.00</span></div>
                <div class="stat-sub">12 Claims processed</div>
            </div>

        
        </div>

        <div class="card">
            <div class="card-header">
                <h3 style="margin:0; font-size:16px;">Pending Claims</h3>
                <div style="display:flex; gap:10px;">
                    <button class="btn" style="border:1px solid var(--border); color:var(--text-muted);"><i class="bi bi-filter"></i> Filter</button>
                    <button class="btn" style="border:1px solid var(--border); color:var(--text-muted);"><i class="bi bi-download"></i> Export</button>
                </div>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Receipt (Proof)</th>
                        <th>Amount</th>
                        <th style="text-align:right">Action</th>
                    </tr>
                </thead>
                <tbody id="claimsTable">
                    </tbody>
            </table>
        </div>

        <div class="card" style="opacity:0.7;">
            <div class="card-header" style="border-bottom:none; padding-bottom:0;">
                <h3 style="margin:0; font-size:14px; color:var(--text-muted);">Recently Processed</h3>
            </div>
            <table class="data-table">
                <tbody style="color:var(--text-muted);">
                    <tr>
                        <td>Alice M.</td>
                        <td><span class="badge cat-supplies">Supplies</span></td>
                        <td>Printer Ink</td>
                        <td>Oct 20</td>
                        <td><i class="bi bi-check-circle-fill" style="color:var(--success)"></i> Validated</td>
                        <td>P 85.00</td>
                        <td style="text-align:right; font-size:12px;">Approved by Finance</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <div class="modal-overlay" id="receiptModal" onclick="this.style.display='none'">
        <div class="modal-box" onclick="event.stopPropagation()">
            <h3 style="color:white; margin-top:0;">Receipt Preview</h3>
            <img src="https://via.placeholder.com/350x450/1e293b/94a3b8?text=RECEIPT+IMAGE" class="receipt-img">
            <div style="margin-top:20px;">
                <button class="btn btn-primary" onclick="document.getElementById('receiptModal').style.display='none'">Close</button>
            </div>
        </div>
    </div>

    <script>
        // MOCK DATA
        const pendingClaims = [
            { id: 101, user: 'John Doe', role: 'Sales Lead', cat: 'Travel', catClass: 'cat-travel', desc: 'Uber to Client HQ', date: 'Oct 23', amount: 45.50, receipt: 'uber_rec.jpg' },
            { id: 102, user: 'Mike Chen', role: 'Driver', cat: 'Food', catClass: 'cat-food', desc: 'Team Lunch', date: 'Oct 22', amount: 120.00, receipt: 'rest_bill.jpg' },
            { id: 103, user: 'Sarah Miller', role: 'Admin', cat: 'Supplies', catClass: 'cat-supplies', desc: 'Office Stationery', date: 'Oct 21', amount: 284.50, receipt: 'staples.pdf' }
        ];

        // STATE
        let budgetTotal = 5000;
        let spentTotal = 1200;
        let pendingTotal = 450; // Initial matching mock

        function renderTable() {
            const tbody = document.getElementById('claimsTable');
            tbody.innerHTML = '';

            pendingClaims.forEach(claim => {
                tbody.innerHTML += `
                    <tr id="row-${claim.id}">
                        <td>
                            <div style="font-weight:600; color:white;">${claim.user}</div>
                            <div style="font-size:11px; color:var(--text-muted);">${claim.role}</div>
                        </td>
                        <td><span class="badge ${claim.catClass}">${claim.cat}</span></td>
                        <td>${claim.desc}</td>
                        <td>${claim.date}</td>
                        <td>
                            <a href="#" class="receipt-link" onclick="viewReceipt('${claim.receipt}')">
                                <i class="bi bi-paperclip"></i> View Receipt
                            </a>
                        </td>
                        <td style="font-weight:700; color:var(--text-main);">$${claim.amount.toFixed(2)}</td>
                        <td style="text-align:right">
                            <div style="display:flex; gap:8px; justify-content:flex-end;">
                                <button class="btn btn-reject" onclick="processClaim(${claim.id}, ${claim.amount}, 'reject')"><i class="bi bi-x-lg"></i></button>
                                <button class="btn btn-approve" onclick="processClaim(${claim.id}, ${claim.amount}, 'approve')"><i class="bi bi-check-lg"></i> Approve</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        function processClaim(id, amount, action) {
            const row = document.getElementById(`row-${id}`);
            
            // Visual Animation
            row.style.opacity = '0.3';
            row.style.pointerEvents = 'none';

            // Logic Simulation
            if(action === 'approve') {
                spentTotal += amount;
                pendingTotal -= amount;
            } else {
                // If rejected, money goes back to pool (technically pending decreases, spent stays same)
                pendingTotal -= amount; 
            }

            updateDashboard();

            // Remove from list visually after 500ms
            setTimeout(() => {
                row.style.display = 'none';
                if(pendingTotal <= 1) {
                    document.getElementById('claimsTable').innerHTML = '<tr><td colspan="7" style="text-align:center; padding:30px; color:var(--text-muted);">No pending claims. Good job!</td></tr>';
                }
            }, 500);
        }

        function updateDashboard() {
            const remain = budgetTotal - spentTotal;
            
            // Update Numbers
            document.getElementById('dash-spent').innerText = spentTotal.toFixed(2);
            document.getElementById('dash-pending').innerText = pendingTotal.toFixed(2);
            document.getElementById('dash-pending-mini').innerText = pendingTotal.toFixed(2);
            document.getElementById('dash-remain').innerText = remain.toFixed(2);

            // Update Bars
            const spentPct = (spentTotal / budgetTotal) * 100;
            const pendingPct = (pendingTotal / budgetTotal) * 100;
            
            document.getElementById('bar-spent').style.width = spentPct + '%';
            document.getElementById('bar-pending').style.width = pendingPct + '%';
        }

        function viewReceipt(img) {
            document.getElementById('receiptModal').style.display = 'flex';
        }

        // Init
        renderTable();
        updateDashboard();

    </script>
</body>
</html>