// theme.js
function applyTheme() {
    const isDark = localStorage.getItem('darkMode') === 'true';
    if (isDark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

function toggleDarkMode() {
    const isDark = document.documentElement.classList.contains('dark');
    if (isDark) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('darkMode', 'false');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('darkMode', 'true');
    }
    updateThemeIcon();
}

function updateThemeIcon() {
    const themeIcon = document.getElementById('theme-icon');
    if (!themeIcon) return;
    const isDark = document.documentElement.classList.contains('dark');
    if (isDark) {
        themeIcon.classList.remove('fa-moon');
        themeIcon.classList.add('fa-sun');
    } else {
        themeIcon.classList.remove('fa-sun');
        themeIcon.classList.add('fa-moon');
    }
}

// รันทันทีเมื่อโหลดไฟล์สคริปต์นี้เพื่อป้องกันแฟลช
applyTheme();
document.addEventListener('DOMContentLoaded', updateThemeIcon);
