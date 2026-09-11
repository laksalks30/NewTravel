// Universal Mobile Navbar Toggle
document.addEventListener('DOMContentLoaded', function () {
    const togglers = document.querySelectorAll('.navbar-toggler');

    togglers.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const targetSelector = btn.getAttribute('data-bs-target') || btn.getAttribute('data-target');
            const target = targetSelector ? document.querySelector(targetSelector) : document.querySelector('.navbar-collapse');

            if (target) {
                target.classList.toggle('show');
                const isExpanded = target.classList.contains('show');
                btn.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
            }
        });
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#navbar')) {
            document.querySelectorAll('.navbar-collapse.show').forEach(function (collapse) {
                collapse.classList.remove('show');
            });
            togglers.forEach(function (btn) {
                btn.setAttribute('aria-expanded', 'false');
            });
        }
    });
});
