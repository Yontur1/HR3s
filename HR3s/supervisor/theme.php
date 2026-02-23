<style>
    
/* ================== TOP RIGHT TOGGLE POSITIONING ================== */
.theme-top-right {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999; /* Ensures it is above everything */
}

.theme-toggle {
    background: var(--card-bg); /* Match card color so it's visible */
    border: 1px solid var(--brand-green);
    color: var(--brand-green);
    width: 42px;
    height: 42px;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

</style>

<div class="theme-top-right">
    <button class="theme-toggle" id="themeToggle" title="Toggle Theme">
        <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"></circle>
            <line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
            <line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>
        <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>
    </button>
</div>

<script>
(function() {
    const html = document.documentElement;
    // Default to dark if no preference exists
    const savedTheme = localStorage.getItem('theme') || 'dark';
    
    if (savedTheme === 'dark') {
        html.classList.add('dark-mode');
    } else {
        html.classList.remove('dark-mode');
    }

    window.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('themeToggle');
        const sun = btn.querySelector('.sun-icon');
        const moon = btn.querySelector('.moon-icon');

        const updateUI = (isDark) => {
            sun.style.display = isDark ? 'none' : 'block';
            moon.style.display = isDark ? 'block' : 'none';
        };

        updateUI(html.classList.contains('dark-mode'));

        btn.addEventListener('click', () => {
            const isDark = html.classList.toggle('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateUI(isDark);
            // Optional: Trigger event to update chart colors
            window.dispatchEvent(new Event('themeChanged'));
        });
    });
})();
</script>