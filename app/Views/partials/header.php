<link rel="stylesheet" href="<?= base_url('css/buttons.css') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style = "height: 50px;">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <a class="navbar-brand" href="#"><?= isset($title) ? esc($title) : '' ?></a>
        </div>
        <div class="d-flex">
            <a href="<?= site_url('auth/logout') ?>" class="btn btn-header">Logout</a>
        </div>
    </div>
</nav>
<div class="sidebar">
    <button id="sidebarToggle" class="sidebar-toggle-btn" type="button" aria-label="Toggle sidebar" title="Toggle sidebar">
        <i class="bi bi-chevron-left"></i>
    </button>
  
    <a href="<?= site_url('dashboard') ?>" class="sidebar-link" data-tooltip="Dashboard">
        <i class="bi bi-speedometer2"></i>
        <span class="link-text">Dashboard</span>
    </a>
    <?php if (session()->get('usertype') === 'superadmin'): ?>
        <a href="<?= site_url('users') ?>" class="sidebar-link" data-tooltip="Manage Users">
            <i class="bi bi-people"></i>
            <span class="link-text">Manage Users</span>
        </a>
        <a href="<?= site_url('units') ?>" class="sidebar-link" data-tooltip="Manage Units">
            <i class="bi bi-building"></i>
            <span class="link-text">Manage Units</span>
        </a>
        <a href="<?= site_url('assets') ?>" class="sidebar-link" data-tooltip="Manage Assets">
            <i class="bi bi-laptop"></i>
            <span class="link-text">Manage Assets</span>
        </a>
        <a href="<?= site_url('peripherals') ?>" class="sidebar-link" data-tooltip="Manage Peripherals">
            <i class="bi bi-mouse"></i>
            <span class="link-text">Manage Peripherals</span>
        </a>
        <a href="<?= site_url('applications') ?>" class="sidebar-link" data-tooltip="Application Management">
            <i class="bi bi-window-stack"></i>
            <span class="link-text">Application Management</span>
        </a>
        
        <a href="<?= site_url('settings') ?>" class="sidebar-link" data-tooltip="Settings">
            <i class="bi bi-gear"></i>
            <span class="link-text">Settings</span>
        </a>
    <?php endif; ?>
</div>

<style>
    :root { 
        --sidebar-width: 250px; 
        --sidebar-collapsed-width: 60px;
        --sidebar-transition-duration: 250ms; 
        --sidebar-transition-easing: cubic-bezier(0.4, 0, 0.2, 1); 
    }

    /* Custom header button styling */
    .btn-header {
        color: #fff;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        border-radius: 0rem;
        transition: all 0.15s ease-in-out;
    }
    
    .btn-header:hover {
        color: #000;
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }
    
    .btn-header:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(248, 249, 250, 0.5);
    }

    /* Base sidebar styles */
    .sidebar {
        width: var(--sidebar-width);
        background-color: #f8f9fa;
        padding: 20px 0;
        position: fixed;
        height: 100%;
        top: 30px;
        left: 0;
        overflow-x: hidden;
        overflow-y: auto;
        transition: width var(--sidebar-transition-duration) var(--sidebar-transition-easing);
        will-change: width;
        z-index: 1000;
    }

    .sidebar-toggle-btn {
        width: 100%;
        padding: 10px;
        background-color: transparent;
        border: none;
        border-bottom: 1px solid #ddd;
        color: #333;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        transition: background-color 0.2s ease;
        font-size: 1.2rem;
    }

    .sidebar-toggle-btn:hover {
        background-color: #e9ecef;
    }

    .sidebar-toggle-btn i {
        transition: transform var(--sidebar-transition-duration) var(--sidebar-transition-easing);
    }

    body.sidebar-collapsed .sidebar-toggle-btn {
        justify-content: center;
    }

    body.sidebar-collapsed .sidebar-toggle-btn i {
        transform: rotate(180deg);
    }

    .sidebar-title {
        padding: 0 20px;
        margin-bottom: 15px;
        margin-top: 10px;
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        white-space: nowrap;
        opacity: 1;
        transition: opacity var(--sidebar-transition-duration) var(--sidebar-transition-easing);
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        text-decoration: none;
        color: #333;
        border-bottom: 1px solid #ddd;
        transition: background-color 0.2s ease;
        position: relative;
        white-space: nowrap;
    }

    .sidebar-link i {
        font-size: 1.2rem;
        min-width: 20px;
        margin-right: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-link .link-text {
        opacity: 1;
        transition: opacity var(--sidebar-transition-duration) var(--sidebar-transition-easing);
    }

    .sidebar-link:hover {
        background-color: #e9ecef;
    }

    /* Tooltip for collapsed state */
    .sidebar-link::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background-color: #333;
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.875rem;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        margin-left: 10px;
        transition: opacity 0.2s ease;
        z-index: 1001;
    }

    /* Main content shifts when sidebar visible */
    .main-content {
        transition: margin-left var(--sidebar-transition-duration) var(--sidebar-transition-easing);
        margin-left: var(--sidebar-width);
    }

    /* Collapsed state - shrink sidebar and show only icons */
    body.sidebar-collapsed .sidebar {
        width: var(--sidebar-collapsed-width);
        padding: 20px 0;
    }

    body.sidebar-collapsed .sidebar-title {
        opacity: 0;
        pointer-events: none;
    }

    body.sidebar-collapsed .sidebar-link .link-text {
        opacity: 0;
        pointer-events: none;
    }

    body.sidebar-collapsed .sidebar-link {
        padding: 12px 0;
        justify-content: center;
        text-align: center;
    }

    body.sidebar-collapsed .sidebar-link i {
        margin-right: 0;
        margin-left: 0;
        width: 100%;
        text-align: center;
        
        justify-content: center;
    }

    body.sidebar-collapsed .sidebar-link:hover::after {
        opacity: 1;
    }

    body.sidebar-collapsed .main-content {
        margin-left: var(--sidebar-collapsed-width);
    }

    /* Prevent animation during initial state restore */
    body.no-sidebar-transition .sidebar,
    body.no-sidebar-transition .main-content,
    body.no-sidebar-transition .sidebar-title,
    body.no-sidebar-transition .sidebar-link .link-text {
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