<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DispatchPro | Leave Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/leave.css">
</head>
<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <!-- Theme Toggle -->
        <div style="text-align:right; margin-bottom:10px;">
            <button id="themeToggle" class="btn btn-outline">
                <i class="bi bi-moon-fill"></i> Toggle Dark Mode
            </button>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <div class="tab" onclick="switchTab('approval-queue', this)">Approval Queue</div>
            <div class="tab active" onclick="switchTab('employee-records', this)">Employee Records & Credits</div>
            <div class="tab" onclick="switchTab('my-history', this)">My History</div>
        </div>

        <!-- Employee Records -->
        <div id="employee-records" class="tab-content active">
            <div class="search-area">
                <select id="empSelect" class="search-box" onchange="loadEmployeeData()">
                    <option value="1">Sarah Miller (Warehouse Lead)</option>
                    <option value="2">Mike Chen (Driver)</option>
                    <option value="3">John Doe (Security)</option>
                </select>
                <button class="btn btn-outline" onclick="adjustCredits()"><i class="bi bi-sliders"></i> Manual Adjustment</button>
                <button class="btn btn-outline"><i class="bi bi-download"></i> Export Report</button>
            </div>

            <div class="credit-grid">
                <div class="credit-card">
                    <div class="credit-header" style="color:var(--vacation)">Vacation Leave</div>
                    <div class="credit-body">
                        <div class="big-num" id="vacation-bal">12.0</div>
                        <div class="sub-text">
                            <div>Used: <span id="vacation-used">3.0</span></div>
                            <div>Total: <span id="vacation-total">15.0</span></div>
                        </div>
                    </div>
                    <div class="progress-bar"><div class="progress-fill" id="vacation-bar"></div></div>
                </div>

                <div class="credit-card">
                    <div class="credit-header" style="color:var(--sick)">Sick Leave</div>
                    <div class="credit-body">
                        <div class="big-num" id="sick-bal">9.0</div>
                        <div class="sub-text">
                            <div>Used: <span id="sick-used">1.0</span></div>
                            <div>Total: <span id="sick-total">10.0</span></div>
                        </div>
                    </div>
                    <div class="progress-bar"><div class="progress-fill" id="sick-bar"></div></div>
                </div>

                <div class="credit-card">
                    <div class="credit-header" style="color:var(--emergency)">Emergency</div>
                    <div class="credit-body">
                        <div class="big-num" id="emerg-bal">3.0</div>
                        <div class="sub-text">
                            <div>Used: <span id="emerg-used">0.0</span></div>
                            <div>Total: <span id="emerg-total">3.0</span></div>
                        </div>
                    </div>
                    <div class="progress-bar"><div class="progress-fill" id="emerg-bar"></div></div>
                </div>
            </div>

            <div class="card">
                <h3 style="margin-top:0; font-size:16px;">Leave Usage Archive</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date Filed</th>
                            <th>Leave Dates</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Credit Deduction</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="historyTable"></tbody>
                </table>
            </div>
        </div>

        <!-- Approval Queue -->
        <div id="approval-queue" class="tab-content">
            <div class="card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Duration</th>
                            <th>Dates</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Mike Chen</strong><br><span style="font-size:11px; color:var(--text-muted)">Driver</span></td>
                            <td><span class="badge bg-vacation">Vacation</span></td>
                            <td>5 Days</td>
                            <td>Dec 20 - Dec 25</td>
                            <td>
                                <button class="btn btn-primary" style="padding:6px 12px; font-size:12px;">Review</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- My History -->
        <div id="my-history" class="tab-content">
            <div class="card" style="padding:40px; text-align:center; color:var(--text-muted);">
                <i class="bi bi-person-circle" style="font-size:48px; opacity:0.5;"></i>
                <p>Personal leave history view for the logged-in admin.</p>
            </div>
        </div>

    </div>

    <script src="leave.js"></script>
</body>
</html>
