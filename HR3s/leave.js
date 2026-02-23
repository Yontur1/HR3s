// ================= THEME TOGGLE =================
const themeToggle = document.getElementById("themeToggle");
const body = document.body;
const icon = themeToggle.querySelector("i");

// Load saved theme on page load
const savedTheme = localStorage.getItem("theme");
if (savedTheme === "dark") body.classList.add("dark-mode");

updateIcon();
function updateIcon() {
    if (body.classList.contains("dark-mode")) {
        icon.classList.replace("bi-moon-fill", "bi-sun-fill");
    } else {
        icon.classList.replace("bi-sun-fill", "bi-moon-fill");
    }
}

themeToggle.addEventListener("click", () => {
    body.classList.toggle("dark-mode");
    localStorage.setItem("theme", body.classList.contains("dark-mode") ? "dark" : "light");
    updateIcon();
});

// ================= MOCK DATA =================
const empData = {
    "1": { vacation: { total: 15, used: 3 }, sick: { total: 10, used:1 }, emerg: { total:3, used:0 },
           history:[{ date:'Oct 05, 2023', period:'Oct 20 - Oct 22', type:'Vacation', reason:'Family Trip', deduction:'-3.0 Days', status:'Approved'},
                    { date:'Sep 12, 2023', period:'Sep 12', type:'Sick', reason:'Migraine', deduction:'-1.0 Day', status:'Approved'}] },
    "2": { vacation: { total:15, used:10 }, sick:{total:10, used:5}, emerg:{total:3, used:1},
           history:[{date:'Aug 01, 2023', period:'Aug 10 - Aug 20', type:'Vacation', reason:'Summer Break', deduction:'-10.0 Days', status:'Approved'},
                    {date:'Jul 15, 2023', period:'Jul 16', type:'Emergency', reason:'Car Breakdown', deduction:'-1.0 Day', status:'Approved'}]},
    "3": { vacation:{total:15,used:0}, sick:{total:10,used:0}, emerg:{total:3, used:0}, history:[] }
};

// ================= TABS =================
function switchTab(tabId, navEl) {
    document.querySelectorAll('.tab-content').forEach(el=>el.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(el=>el.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    navEl.classList.add('active');
}

// ================= LOAD EMPLOYEE DATA =================
function loadEmployeeData() {
    const id = document.getElementById('empSelect').value;
    const data = empData[id]; if(!data) return;

    // Update Cards
    updateCard('vacation', data.vacation, 'vacation-bar');
    updateCard('sick', data.sick, 'sick-bar');
    updateCard('emerg', data.emerg, 'emerg-bar');

    // Update Table
    const tbody = document.getElementById('historyTable'); tbody.innerHTML='';
    if(data.history.length===0) {
        tbody.innerHTML='<tr><td colspan="6" style="text-align:center; color:var(--text-muted);">No leave history found for this employee.</td></tr>';
    } else {
        data.history.forEach(row=>{
            let badgeClass = row.type==='Vacation'?'bg-vacation':row.type==='Sick'?'bg-sick':'bg-emergency';
            tbody.innerHTML+=`
            <tr>
                <td>${row.date}</td>
                <td>${row.period}</td>
                <td><span class="badge ${badgeClass}">${row.type}</span></td>
                <td>${row.reason}</td>
                <td style="color:#ef4444; font-weight:700;">${row.deduction}</td>
                <td style="color:#10b981; font-weight:600;">${row.status}</td>
            </tr>`;
        });
    }
}

// ================= UPDATE CARD =================
function updateCard(type, obj, barId) {
    const bal = obj.total - obj.used;
    document.getElementById(`${type}-bal`).innerText = bal.toFixed(1);
    document.getElementById(`${type}-used`).innerText = obj.used.toFixed(1);
    document.getElementById(`${type}-total`).innerText = obj.total.toFixed(1);
    const pct = (bal/obj.total)*100;
    document.getElementById(barId).style.width = pct+'%';
    document.getElementById(barId).style.backgroundColor = `var(--${type})`;
}

// ================= MANUAL ADJUST =================
function adjustCredits() {
    alert("Opens a modal to manually Add/Deduct credits (e.g., 'Carry Over' or 'Correction').");
}

// ================= INIT =================
loadEmployeeData();
