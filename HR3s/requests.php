<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DispatchPro | Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --border: #334155;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --primary: #10b981;
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
            overflow: hidden; /* Prevent body scroll, handle inside panels */
        }

        .main-content { flex: 1; max-width: 1600px; display: flex; flex-direction: column; height: calc(100vh - 48px); }

        /* --- INBOX LAYOUT --- */
        .inbox-wrapper {
            display: flex;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            flex: 1;
        }

        /* LEFT PANE: LIST */
        .request-list {
            width: 350px;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            background: rgba(15, 23, 42, 0.3);
        }

        .list-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
        }

        .search-box {
            background: var(--bg-dark);
            border: 1px solid var(--border);
            padding: 10px;
            border-radius: 8px;
            color: white;
            width: 100%;
            box-sizing: border-box;
            outline: none;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .filter-tab {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 6px;
            cursor: pointer;
            color: var(--text-muted);
        }
        .filter-tab.active { background: rgba(255,255,255,0.1); color: white; }

        .list-items {
            flex: 1;
            overflow-y: auto;
        }

        .req-item {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            transition: 0.2s;
            border-left: 3px solid transparent;
        }

        .req-item:hover { background: rgba(255,255,255,0.02); }
        .req-item.active { background: rgba(16, 185, 129, 0.05); border-left-color: var(--primary); }
        .req-item.unread .req-subject { font-weight: 700; color: white; }

        .req-top { display: flex; justify-content: space-between; font-size: 12px; color: var(--text-muted); margin-bottom: 4px; }
        .req-subject { font-size: 14px; color: #e2e8f0; margin-bottom: 4px; }
        .req-preview { font-size: 12px; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* RIGHT PANE: DETAIL */
        .request-detail {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .detail-header {
            border-bottom: 1px solid var(--border);
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .detail-title { font-size: 24px; font-weight: 600; margin-bottom: 8px; }
        
        .meta-row { display: flex; gap: 20px; font-size: 13px; color: var(--text-muted); align-items: center; }
        .user-chip { display: flex; align-items: center; gap: 8px; color: white; background: rgba(255,255,255,0.05); padding: 4px 12px; border-radius: 20px; }
        
        .detail-body { font-size: 14px; line-height: 1.6; color: #cbd5e1; flex: 1; }
        
        .attachment-box {
            background: var(--bg-dark);
            border: 1px solid var(--border);
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            cursor: pointer;
        }

        .action-bar {
            margin-top: 30px;
            border-top: 1px solid var(--border);
            padding-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; }
        .btn-approve { background: var(--primary); color: white; }
        .btn-reject { background: transparent; border: 1px solid #ef4444; color: #ef4444; }
        .btn-reject:hover { background: rgba(239, 68, 68, 0.1); }

        /* Tags */
        .tag { font-size: 10px; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; font-weight: 600; }
        .tag-leave { background: rgba(59, 130, 246, 0.2); color: #60a5fa; }
        .tag-claim { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }

    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <h2 style="margin-top: 0; margin-bottom: 16px;">Requests & Approvals</h2>
        
        <div class="inbox-wrapper">
            
            <!-- LEFT LIST -->
            <div class="request-list">
                <div class="list-header">
                    <input type="text" class="search-box" placeholder="Search requests...">
                    <div class="filter-tabs">
                        <div class="filter-tab active">All</div>
                        <div class="filter-tab">Leave</div>
                        <div class="filter-tab">Claims</div>
                    </div>
                </div>
                <div class="list-items" id="listContainer">
                    <!-- Populated by JS -->
                </div>
            </div>

            <!-- RIGHT DETAIL -->
            <div class="request-detail" id="detailView">
                <div style="display:flex; align-items:center; justify-content:center; height:100%; color:#64748b;">
                    Select a request to view details
                </div>
            </div>

        </div>
    </div>

    <script>
        // MOCK DATA
        const requests = [
            {
                id: 1,
                type: 'leave',
                user: 'Sarah Miller',
                date: 'Oct 24, 2023',
                subject: 'Sick Leave Request',
                preview: 'I am not feeling well and would like to take a day off...',
                body: 'Hi Manager,\n\nI am feeling under the weather today with a high fever. I would like to request a sick leave for tomorrow, Oct 25th. I have already handed over my pending tasks to John.\n\nThanks,\nSarah',
                attachment: 'Medical_Cert.pdf'
            },
            {
                id: 2,
                type: 'claim',
                user: 'John Doe',
                date: 'Oct 23, 2023',
                subject: 'Travel Reimbursement - Client Meeting',
                preview: 'Attached are the receipts for the taxi fare to...',
                body: 'Hello,\n\nPlease find attached the receipts for my travel to the client meeting at Downtown on Oct 22nd.\n\nTotal Amount: $45.50\n\nRegards,\nJohn',
                attachment: 'Receipt_Uber.jpg'
            },
            {
                id: 3,
                type: 'leave',
                user: 'Mike Chen',
                date: 'Oct 20, 2023',
                subject: 'Vacation Leave (December)',
                preview: 'Planning to take a week off for the holidays...',
                body: 'Hi,\n\nI would like to apply for vacation leave from Dec 20 to Dec 27. I will ensure all project deliverables are met before I leave.\n\nBest,\nMike',
                attachment: null
            }
        ];

        // RENDER LIST
        const listContainer = document.getElementById('listContainer');
        const detailView = document.getElementById('detailView');

        function renderList() {
            listContainer.innerHTML = '';
            requests.forEach(req => {
                const tagClass = req.type === 'leave' ? 'tag-leave' : 'tag-claim';
                const tagText = req.type === 'leave' ? 'Leave' : 'Claim';
                
                const el = document.createElement('div');
                el.className = 'req-item unread';
                el.onclick = () => loadDetail(req, el);
                el.innerHTML = `
                    <div class="req-top">
                        <span>${req.user}</span>
                        <span>${req.date}</span>
                    </div>
                    <div class="req-subject">
                        <span class="tag ${tagClass}">${tagText}</span> ${req.subject}
                    </div>
                    <div class="req-preview">${req.preview}</div>
                `;
                listContainer.appendChild(el);
            });
        }

        // LOAD DETAIL
        function loadDetail(req, el) {
            // Active State
            document.querySelectorAll('.req-item').forEach(i => i.classList.remove('active'));
            el.classList.add('active');
            el.classList.remove('unread');

            // Render Detail
            const tagClass = req.type === 'leave' ? 'tag-leave' : 'tag-claim';
            const tagText = req.type === 'leave' ? 'Leave Request' : 'Reimbursement Claim';

            let attachmentHTML = '';
            if(req.attachment) {
                attachmentHTML = `
                    <div class="attachment-box">
                        <i class="bi bi-paperclip"></i> ${req.attachment}
                    </div>
                `;
            }

            detailView.innerHTML = `
                <div class="detail-header">
                    <div style="margin-bottom:10px;"><span class="tag ${tagClass}" style="font-size:12px;">${tagText}</span></div>
                    <div class="detail-title">${req.subject}</div>
                    <div class="meta-row">
                        <div class="user-chip"><i class="bi bi-person-circle"></i> ${req.user}</div>
                        <span><i class="bi bi-calendar3"></i> ${req.date}</span>
                    </div>
                </div>
                <div class="detail-body">
                    ${req.body.replace(/\n/g, '<br>')}
                    <br>
                    ${attachmentHTML}
                </div>
                <div class="action-bar">
                    <button class="btn btn-reject" onclick="alert('Request Rejected')">Reject</button>
                    <button class="btn btn-approve" onclick="alert('Request Approved')">Approve Request</button>
                </div>
            `;
        }

        // Init
        renderList();

    </script>

</body>
</html>