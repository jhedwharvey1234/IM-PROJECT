<link rel="stylesheet" href="<?= base_url('css/buttons.css') ?>">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button id="sidebarToggle" class="btn btn-outline-light me-2" type="button" aria-label="Toggle sidebar" title="Toggle sidebar">☰</button>
            <a class="navbar-brand" href="#"><?= isset($title) ? esc($title) : '' ?></a>
        </div>
        <div class="d-flex">
            <a href="<?= site_url('auth/logout') ?>" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>
<div class="sidebar">
    <h5>Navigation</h5>
    <a href="<?= site_url('dashboard') ?>">Dashboard</a>
    <?php if (session()->get('usertype') === 'superadmin'): ?>
        <a href="<?= site_url('users') ?>">Manage Users</a>
    <?php endif; ?>
</div>

<style>
    :root { --sidebar-width: 250px; --sidebar-transition-duration: 250ms; --sidebar-transition-easing: cubic-bezier(0.4, 0, 0.2, 1); }

    /* Base sidebar styles */
    .sidebar {
        width: var(--sidebar-width);
        transition: transform var(--sidebar-transition-duration) var(--sidebar-transition-easing);
        will-change: transform;
    }

    /* Main content shifts when sidebar visible */
    .main-content {
        transition: margin-left var(--sidebar-transition-duration) var(--sidebar-transition-easing);
        margin-left: var(--sidebar-width);
    }

    /* Collapsed state - slide sidebar out and remove left margin */
    body.sidebar-collapsed .sidebar {
        transform: translateX(-100%);
    }

    body.sidebar-collapsed .main-content {
        margin-left: 0;
    }

    /* Prevent animation during initial state restore */
    body.no-sidebar-transition .sidebar,
    body.no-sidebar-transition .main-content {
        transition: none !important;
    }

    /* Small adjustment for navbar alignment when collapsed */
    body.sidebar-collapsed .navbar .navbar-brand { margin-left: 0; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('sidebarToggle');
        if (!toggle) return;

        // Apply saved state without animation
        try {
            const collapsed = localStorage.getItem('sidebarCollapsed');
            if (collapsed === '1') {
                document.body.classList.add('no-sidebar-transition');
                document.body.classList.add('sidebar-collapsed');
                toggle.setAttribute('aria-expanded', 'false');
                // remove no-transition after a short tick so future toggles animate
                setTimeout(() => document.body.classList.remove('no-sidebar-transition'), 50);
            } else {
                toggle.setAttribute('aria-expanded', 'true');
            }
        } catch (e) {}

        toggle.addEventListener('click', function () {
            const collapsed = document.body.classList.toggle('sidebar-collapsed');
            try {
                localStorage.setItem('sidebarCollapsed', collapsed ? '1' : '0');
            } catch (e) {}
            toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        });
    });
</script>