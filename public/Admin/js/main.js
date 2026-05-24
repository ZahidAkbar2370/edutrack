/**
 * EduTrack Admin - Bootstrap + minimal custom JS
 */

document.addEventListener('DOMContentLoaded', function () {

    var MOBILE_BREAKPOINT = 992;
    var sidebar = document.getElementById('admin-sidebar');
    var overlay = document.getElementById('sidebar-overlay');
    var toggleBtn = document.getElementById('sidebar-toggle');
    var closeBtn = document.getElementById('sidebar-close');

    function isMobile() {
        return window.innerWidth < MOBILE_BREAKPOINT;
    }

    function setSidebarOpen(open) {
        if (!sidebar || !toggleBtn) return;

        var iconOpen = toggleBtn.querySelector('.sidebar-icon-open');
        var iconClose = toggleBtn.querySelector('.sidebar-icon-close');

        if (open && isMobile()) {
            sidebar.classList.add('sidebar-open');
            overlay?.classList.add('active');
            document.body.classList.add('sidebar-mobile-open');
            iconOpen?.classList.add('d-none');
            iconClose?.classList.remove('d-none');
            toggleBtn.setAttribute('aria-label', 'Close menu');
        } else {
            sidebar.classList.remove('sidebar-open');
            overlay?.classList.remove('active');
            document.body.classList.remove('sidebar-mobile-open');
            iconOpen?.classList.remove('d-none');
            iconClose?.classList.add('d-none');
            toggleBtn.setAttribute('aria-label', 'Open menu');
        }
    }

    toggleBtn?.addEventListener('click', function () {
        if (!isMobile()) return;
        setSidebarOpen(!sidebar.classList.contains('sidebar-open'));
    });

    closeBtn?.addEventListener('click', function () {
        setSidebarOpen(false);
    });

    overlay?.addEventListener('click', function () {
        setSidebarOpen(false);
    });

    document.querySelectorAll('.admin-sidebar .submenu .nav-link, .admin-sidebar > nav > .nav-link:not([data-bs-toggle])').forEach(function (link) {
        link.addEventListener('click', function () {
            if (isMobile()) setSidebarOpen(false);
        });
    });

    window.addEventListener('resize', function () {
        if (!isMobile()) setSidebarOpen(false);
    });

    // Status switches in tables
    document.querySelectorAll('.status-switch').forEach(function (input) {
        var label = input.closest('td')?.querySelector('.status-switch-label');
        if (!label) return;
        function update() {
            label.textContent = input.checked ? 'Active' : 'Inactive';
            label.className = 'status-switch-label small ' + (input.checked ? 'text-success' : 'text-muted');
        }
        update();
        input.addEventListener('change', update);
    });

    document.querySelectorAll('.action-btn.delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (confirm('Delete this record? (demo only)')) {}
        });
    });

    // Table scroll hint visibility
    document.querySelectorAll('.table-scroll-wrap').forEach(function (wrap) {
        var scrollEl = wrap.querySelector('.table-responsive');
        if (!scrollEl) return;
        function update() {
            wrap.classList.toggle('no-scroll-needed', scrollEl.scrollWidth <= scrollEl.clientWidth + 2);
        }
        scrollEl.addEventListener('scroll', update);
        window.addEventListener('resize', update);
        update();
    });

});
