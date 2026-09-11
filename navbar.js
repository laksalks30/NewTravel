// Universal Mobile Navbar Toggle
function toggleNavMenu(e) {
    if (e) {
        if (typeof e.preventDefault === 'function') e.preventDefault();
        if (typeof e.stopPropagation === 'function') e.stopPropagation();
    }
    const btn = (e && e.currentTarget) ? e.currentTarget : document.querySelector('.navbar-toggler');
    const targetSelector = btn ? (btn.getAttribute('data-bs-target') || btn.getAttribute('data-target')) : null;
    const target = targetSelector ? document.querySelector(targetSelector) : (document.getElementById('mynavbar') || document.getElementById('nb') || document.querySelector('.navbar-collapse'));
    
    if (target) {
        target.classList.toggle('show');
        const isShown = target.classList.contains('show');
        if (btn) btn.setAttribute('aria-expanded', isShown ? 'true' : 'false');
    }
}

// Close menu when clicking outside
document.addEventListener('click', function (e) {
    if (!e.target.closest('#navbar')) {
        document.querySelectorAll('.navbar-collapse.show').forEach(function (collapse) {
            collapse.classList.remove('show');
        });
        document.querySelectorAll('.navbar-toggler').forEach(function (btn) {
            btn.setAttribute('aria-expanded', 'false');
        });
    }
});

// Also bind automatically as backup
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.navbar-toggler').forEach(function (btn) {
        if (!btn.getAttribute('onclick')) {
            btn.addEventListener('click', toggleNavMenu);
        }
    });
});
