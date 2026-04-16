// Theme Toggle Logic
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const root = document.documentElement;
    
    if (themeToggle) {
        if (localStorage.getItem('theme') === 'dark') {
            themeToggle.checked = true;
        }

        themeToggle.addEventListener('change', function() {
            if (this.checked) {
                root.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                root.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
            }
        });
    }

    // Dropdown Toggle Logic
    const userProfileBtn = document.getElementById('userProfileBtn');
    const userProfileMenu = document.getElementById('userProfileMenu');

    if (userProfileBtn && userProfileMenu) {
        userProfileBtn.addEventListener('click', function(e) {
            // Prevent close if clicking on the toggle switch
            if(e.target.closest('.theme-switch')) return; 
            userProfileMenu.classList.toggle('active');
        });

        document.addEventListener('click', function(e) {
            if (!userProfileBtn.contains(e.target)) {
                userProfileMenu.classList.remove('active');
            }
        });
    }
});
