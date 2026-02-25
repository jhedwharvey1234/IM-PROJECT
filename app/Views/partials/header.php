<link rel="stylesheet" href="/IM/public/css/buttons.css">
<link rel="stylesheet" href="/IM/public/css/responsive-global.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style = "height: 50px;">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <div class="dropdown mobile-sidebar-dropdown me-2">
                <button class="btn btn-header mobile-menu-btn dropdown-toggle" type="button" id="mobileSidebarMenuBtn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Open navigation menu" title="Menu">
                    <i class="bi bi-list"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-start" id="mobileSidebarDropdownMenu" aria-labelledby="mobileSidebarMenuBtn"></ul>
            </div>
            <a class="navbar-brand" href="#"><?= isset($title) ? esc($title) : '' ?></a>
        </div>
        <div class="d-flex align-items-center">
            <?php if (session()->get('usertype') === 'superadmin'): ?>
                <a href="<?= site_url('notifications') ?>" class="btn btn-header notification-btn me-2" title="Notifications">
                    <i class="bi bi-bell"></i>
                    <?php if (!empty($headerUnreadNotifications)): ?>
                        <span class="notification-badge"><?= (int) $headerUnreadNotifications ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            <?php if (session()->get('user_id')): ?>
                <a href="<?= site_url('auth/logout') ?>" class="btn btn-header">Logout</a>
            <?php else: ?>
                <a href="<?= site_url('login') ?>" class="btn btn-header">Login</a>
            <?php endif; ?>
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
        <!-- Manage Users -->
        <div class="sidebar-item">
            <a href="<?= site_url('users') ?>" class="sidebar-link" data-tooltip="Manage Users">
                <i class="bi bi-people"></i>
                <span class="link-text">Manage Users</span>
                <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>
            <div class="sidebar-submenu">
                <a href="<?= site_url('users/create') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-plus-lg"></i>
                    <span>Create User</span>
                </a>
                 <a href="<?= site_url('users') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-gear"></i>
                    <span>Manage Users</span>
                </a>
            </div>
        </div>

        <!-- Manage Units -->
        <div class="sidebar-item">
            <a href="<?= site_url('units') ?>" class="sidebar-link" data-tooltip="Manage Units">
                <i class="bi bi-building"></i>
                <span class="link-text">Manage Units</span>
                <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>
            <div class="sidebar-submenu">
                <a href="<?= site_url('units/create') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-plus-lg"></i>
                    <span>Create Unit</span>
                </a>
                <a href="<?= site_url('units') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-gear"></i>
                    <span>Manage Units</span>       
                </a>
            </div>
        </div>

        <!-- Manage Assets -->
        <div class="sidebar-item">
            <a href="<?= site_url('assets') ?>" class="sidebar-link" data-tooltip="Manage Assets">
                <i class="bi bi-laptop"></i>
                <span class="link-text">Manage Assets</span>
                <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>
            <div class="sidebar-submenu">
                <a href="<?= site_url('assets/create') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-plus-lg"></i>
                    <span>Create Asset</span>
                </a>
                <a href="<?= site_url('assets') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-gear"></i>
                    <span>Manage Assets</span>
                </a>
            </div>
        </div>

        <!-- Manage Peripherals -->
        <div class="sidebar-item">
            <a href="<?= site_url('peripherals') ?>" class="sidebar-link" data-tooltip="Manage Peripherals">
                <i class="bi bi-mouse"></i>
                <span class="link-text">Manage Peripherals</span>
                <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>
            <div class="sidebar-submenu">
                <a href="<?= site_url('peripherals/create') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-plus-lg"></i>
                    <span>Create Peripheral</span>
                </a>
                <a href="<?= site_url('peripherals') ?>" class="sidebar-submenu-link"       >
                    <i class="bi bi-gear"></i>
                    <span>Manage Peripherals</span> 
                </a>
            </div>      
        </div>

        <!-- Application Management -->
        <div class="sidebar-item">
            <a href="<?= site_url('applications') ?>" class="sidebar-link" data-tooltip="Application Management">
                <i class="bi bi-window-stack"></i>
                <span class="link-text">Application Management</span>
                <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>
            <div class="sidebar-submenu">
                <a href="<?= site_url('applications/create') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-plus-lg"></i>
                    <span>Create Application</span>
                </a>
                <a href="<?= site_url('applications') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-gear"></i>
                    <span>Manage Applications</span>    
                </a>
            </div>  
        </div>

        <!-- Document Management -->
        <div class="sidebar-item">
            <a href="<?= site_url('documents') ?>" class="sidebar-link" data-tooltip="Document Management">
                <i class="bi bi-folder2-open"></i>
                <span class="link-text">Document Management</span>
                <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>
            <div class="sidebar-submenu">
                <a href="<?= site_url('documents/create') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-plus-lg"></i>
                    <span>Create Document</span>
                </a>
                <a href="<?= site_url('documents/alerts') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-calendar-event"></i>
                    <span>Document Alerts</span>
                </a>
                <a href="<?= site_url('documents') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-gear"></i>
                    <span>Manage Documents</span>       
                </a>
            </div>
        </div>

        <!-- DCF Management -->
        <div class="sidebar-item">
            <a href="<?= site_url('dcf') ?>" class="sidebar-link" data-tooltip="DCF Management">
                <i class="bi bi-file-earmark-text"></i>
                <span class="link-text">DCF Management</span>
                <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>
            <div class="sidebar-submenu">
                <a href="<?= site_url('dcf/create') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-plus-lg"></i>
                    <span>Create DCF</span>
                </a>
                <a href="<?= site_url('dcf/questions') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-question-circle"></i>
                    <span>Questions</span>
                </a>
                <a href="<?= site_url('dcf/parts') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-collection"></i>
                    <span>Parts</span>
                </a>
                <a href="<?= site_url('dcf') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-gear"></i>
                    <span>Manage DCF</span>
                </a>
            </div>
        </div>

        <!-- Notifications -->
        <a href="<?= site_url('notifications') ?>" class="sidebar-link" data-tooltip="Notifications">
            <i class="bi bi-bell"></i>
            <span class="link-text">Notification</span>
            <?php if (!empty($headerUnreadNotifications)): ?>
                <span class="badge bg-danger ms-auto"><?= (int) $headerUnreadNotifications ?></span>
            <?php endif; ?>
        </a>
        
        <!-- Settings -->
        <div class="sidebar-item">
            <a href="<?= site_url('settings') ?>" class="sidebar-link" data-tooltip="Settings">
                <i class="bi bi-gear"></i>
                <span class="link-text">Settings</span>
                <i class="bi bi-chevron-down dropdown-icon"></i>
            </a>
            <div class="sidebar-submenu" style = "max-height: 200px; overflow-y: auto;">
                <a href="<?= site_url('settings/locations') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-geo-alt"></i>
                    <span>Locations</span>
                </a>
                <a href="<?= site_url('settings/workstations') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-pc-display"></i>
                    <span>Workstations</span>
                </a>
                <a href="<?= site_url('settings/peripheral-types') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-usb-symbol"></i>
                    <span>Peripheral Types</span>
                </a>
                <a href="<?= site_url('settings/departments') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-diagram-3"></i>
                    <span>Departments</span>
                </a>
                <a href="<?= site_url('settings/categories') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-tags"></i>
                    <span>Asset Categories</span>
                </a>
                <a href="<?= site_url('settings/document-categories') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-folder"></i>
                    <span>Document Categories</span>
                </a>
                <a href="<?= site_url('settings/document-types') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Document Types</span>
                </a>
                <a href="<?= site_url('settings/technologies') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-cpu"></i>
                    <span>Technologies</span>
                </a>
                <a href="<?= site_url('settings/statuses') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-activity"></i>
                    <span>Application Statuses</span>
                </a>
                <a href="<?= site_url('settings/servers') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-server"></i>
                    <span>Servers</span>
                </a>
                <a href="<?= site_url('settings/environments') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-cloud"></i>
                    <span>Environments</span>
                </a>
                <a href="<?= site_url('settings/contacts') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-person-badge"></i>
                    <span>Application Contacts</span>
                </a>
                <a href="<?= site_url('settings') ?>" class="sidebar-submenu-link">
                    <i class="bi bi-gear"></i>
                    <span>All Settings</span>
                </a>
            </div>
        </div>
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

    .notification-btn {
        position: relative;
        line-height: 1;
    }

    .mobile-sidebar-dropdown {
        display: none;
    }

    .mobile-menu-btn {
        padding: 0.35rem 0.55rem;
        border-radius: 4px;
    }

    .mobile-menu-btn i {
        font-size: 1.1rem;
    }

    #mobileSidebarDropdownMenu {
        min-width: 260px;
        max-height: 70vh;
        overflow-y: auto;
    }

    #mobileSidebarDropdownMenu .dropdown-header {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    #mobileSidebarDropdownMenu .dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.92rem;
    }

    #mobileSidebarDropdownMenu .dropdown-item i {
        font-size: 1rem;
        min-width: 18px;
    }

    .notification-btn i {
        font-size: 1.1rem;
    }

    .notification-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        min-width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #dc3545;
        color: #fff;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        line-height: 1;
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
        overflow-y: auto;
        overflow-x: visible;
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

    .sidebar-item {
        position: relative;
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
        flex: 1;
    }

    .sidebar-link .dropdown-icon {
        font-size: 0.8rem;
        margin-left: auto;
        margin-right: 0;
        transition: transform 0.3s ease;
        opacity: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-link:hover {
        background-color: #e9ecef;
    }

    .sidebar-link:hover .dropdown-icon {
        opacity: 1;
    }

    /* Submenu styling */
    .sidebar-submenu {
        display: none;
        flex-direction: column;
        background-color: #e9ecef;
        border-left: 3px solid #0d6efd;
    }

    .sidebar-submenu-link {
        display: flex;
        align-items: center;
        padding: 10px 20px 10px 50px;
        text-decoration: none;
        color: #555;
        border-bottom: 1px solid #ddd;
        transition: background-color 0.2s ease;
        font-size: 0.9rem;
    }

    .sidebar-submenu-link i {
        font-size: 1rem;
        margin-right: 10px;
        min-width: 16px;
    }

    .sidebar-submenu-link:hover {
        background-color: #d0d5dd;
        color: #0d6efd;
    }

    /* Show submenu in expanded state (default) */
    body:not(.sidebar-collapsed) .sidebar-item:hover .sidebar-submenu,
    body:not(.sidebar-collapsed) .sidebar-item.active .sidebar-submenu {
        display: flex;
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

    /* Collapsed state styling */
    body.sidebar-collapsed .link-text {
        display: none;
    }

    body.sidebar-collapsed .sidebar-link {
        justify-content: center;
        padding: 12px;
        position: relative;
    }

    body.sidebar-collapsed .sidebar-submenu {
        position: fixed;
        min-width: 200px;
        background-color: #fff;
        border-left: 4px solid #0d6efd;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.2s ease, visibility 0.2s ease;
        pointer-events: none;
        display: flex;
        flex-direction: column;
        z-index: 2000;
        border-radius: 2px;
        max-height: 50vh;
        overflow-y: auto;
    }

    body.sidebar-collapsed .dropdown-icon {
        display: none;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    body.sidebar-collapsed .sidebar-link:hover::after {
        opacity: 1;
    }

    body.sidebar-collapsed .sidebar-submenu-link {
        padding: 10px 20px;
        border-bottom: 1px solid #eee;
        color: #333;
    }

    body.sidebar-collapsed .sidebar-submenu-link:last-child {
        border-bottom: none;
    }

    body.sidebar-collapsed .sidebar-submenu-link:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    body.sidebar-collapsed .sidebar-submenu-link:hover {
        background-color: #f0f0f0;
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

    @media (max-width: 991.98px) {
        .mobile-sidebar-dropdown {
            display: inline-flex;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('.sidebar');
        const toggle = document.getElementById('sidebarToggle');
        const mobileMenuButton = document.getElementById('mobileSidebarMenuBtn');
        const mobileDropdownMenu = document.getElementById('mobileSidebarDropdownMenu');

        function getLinkLabel(link) {
            const linkText = link.querySelector('.link-text');
            if (linkText && linkText.textContent.trim()) {
                return linkText.textContent.trim();
            }

            const plainText = link.textContent.trim();
            return plainText || 'Menu Item';
        }

        function createMobileItem(link, isSubItem = false) {
            const href = link.getAttribute('href');
            if (!href || href === '#') {
                return null;
            }

            const listItem = document.createElement('li');
            const anchor = document.createElement('a');
            anchor.className = `dropdown-item${isSubItem ? ' ps-4' : ''}`;
            anchor.href = href;

            const icon = link.querySelector('i');
            if (icon) {
                anchor.appendChild(icon.cloneNode(true));
            }

            const text = document.createElement('span');
            text.textContent = getLinkLabel(link);
            anchor.appendChild(text);

            listItem.appendChild(anchor);
            return listItem;
        }

        function buildMobileDropdownMenu() {
            if (!mobileDropdownMenu || !sidebar) {
                return;
            }

            mobileDropdownMenu.innerHTML = '';
            let hasItems = false;

            Array.from(sidebar.children).forEach((child) => {
                if (child.id === 'sidebarToggle' || child.classList.contains('sidebar-toggle-btn')) {
                    return;
                }

                if (child.matches('a.sidebar-link')) {
                    const item = createMobileItem(child);
                    if (item) {
                        mobileDropdownMenu.appendChild(item);
                        hasItems = true;
                    }
                    return;
                }

                if (child.classList.contains('sidebar-item')) {
                    const parentLink = child.querySelector(':scope > .sidebar-link');
                    const subLinks = child.querySelectorAll(':scope > .sidebar-submenu .sidebar-submenu-link');

                    if (parentLink) {
                        const header = document.createElement('li');
                        const headerText = document.createElement('h6');
                        headerText.className = 'dropdown-header';
                        headerText.textContent = getLinkLabel(parentLink);
                        header.appendChild(headerText);
                        mobileDropdownMenu.appendChild(header);
                    }

                    subLinks.forEach((subLink) => {
                        const subItem = createMobileItem(subLink, true);
                        if (subItem) {
                            mobileDropdownMenu.appendChild(subItem);
                            hasItems = true;
                        }
                    });

                    if (parentLink && subLinks.length > 0) {
                        const divider = document.createElement('li');
                        divider.innerHTML = '<hr class="dropdown-divider">';
                        mobileDropdownMenu.appendChild(divider);
                    }
                }
            });

            const trailingDivider = mobileDropdownMenu.querySelector('li:last-child .dropdown-divider');
            if (trailingDivider) {
                trailingDivider.closest('li').remove();
            }

            if (!hasItems) {
                const emptyItem = document.createElement('li');
                emptyItem.innerHTML = '<span class="dropdown-item-text text-muted">No navigation items</span>';
                mobileDropdownMenu.appendChild(emptyItem);
            }
        }

        buildMobileDropdownMenu();

        function closeMobileDropdown() {
            if (!mobileMenuButton) {
                return;
            }

            if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                const instance = bootstrap.Dropdown.getOrCreateInstance(mobileMenuButton);
                instance.hide();
            } else {
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                mobileMenuButton.classList.remove('show');
                if (mobileDropdownMenu) {
                    mobileDropdownMenu.classList.remove('show');
                }
            }
        }

        if (mobileDropdownMenu) {
            mobileDropdownMenu.addEventListener('click', function (event) {
                const targetLink = event.target.closest('a.dropdown-item');
                if (targetLink) {
                    closeMobileDropdown();
                }
            });
        }

        window.addEventListener('resize', function () {
            if (window.innerWidth > 991.98) {
                closeMobileDropdown();
            }
        });

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
            
            // Reset all submenus when toggling state
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.classList.remove('active');
                const submenu = item.querySelector('.sidebar-submenu');
                const icon = item.querySelector('.dropdown-icon');
                
                if (submenu) {
                    // Reset inline styles
                    submenu.style.position = '';
                    submenu.style.left = '';
                    submenu.style.top = '';
                    submenu.style.display = '';
                    submenu.style.visibility = '';
                    submenu.style.opacity = '';
                    submenu.style.pointerEvents = '';
                    // Clear any pending timeouts for this submenu
                    if (submenu._hideTimeout) {
                        clearTimeout(submenu._hideTimeout);
                        submenu._hideTimeout = null;
                    }
                }
                
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });

        // Handle submenu toggle

        document.querySelectorAll('.sidebar-item').forEach(item => {
            const link = item.querySelector('.sidebar-link');
            const submenu = item.querySelector('.sidebar-submenu');
            const icon = link.querySelector('.dropdown-icon');

            if (submenu) {
                // Store timeout directly on submenu element to avoid conflicts
                submenu._hideTimeout = null;

                link.addEventListener('click', function (e) {
                    // Only prevent default if not collapsed
                    if (!document.body.classList.contains('sidebar-collapsed')) {
                        e.preventDefault();
                    }

                    // Toggle active state only when expanded
                    if (!document.body.classList.contains('sidebar-collapsed')) {
                        item.classList.toggle('active');
                        
                        // Rotate icon
                        if (icon) {
                            const isActive = item.classList.contains('active');
                            icon.style.transform = isActive ? 'rotate(180deg)' : 'rotate(0deg)';
                        }
                    }
                });

                // Show submenu on hover with proper positioning in collapsed state
                item.addEventListener('mouseenter', function () {
                    // Only handle in collapsed state
                    if (!document.body.classList.contains('sidebar-collapsed')) {
                        return;
                    }

                    if (submenu) {
                        // Clear any pending hide for THIS submenu
                        if (submenu._hideTimeout) {
                            clearTimeout(submenu._hideTimeout);
                            submenu._hideTimeout = null;
                        }
                        
                        // Get position of the sidebar item for fixed positioning
                        const rect = item.getBoundingClientRect();
                        
                        // Position submenu to the right of the sidebar with fixed positioning
                        submenu.style.position = 'fixed';
                        submenu.style.left = (rect.right + 10) + 'px';
                        submenu.style.top = rect.top + 'px';
                        submenu.style.display = 'flex';
                        submenu.style.visibility = 'visible';
                        submenu.style.opacity = '1';
                        submenu.style.pointerEvents = 'auto';
                    }
                });

                item.addEventListener('mouseleave', function () {
                    // Only handle in collapsed state
                    if (!document.body.classList.contains('sidebar-collapsed')) {
                        return;
                    }

                    if (submenu) {
                        // Add delay before hiding to allow cursor to reach submenu
                        submenu._hideTimeout = setTimeout(() => {
                            submenu.style.visibility = 'hidden';
                            submenu.style.opacity = '0';
                            submenu.style.pointerEvents = 'none';
                            submenu._hideTimeout = null;
                        }, 100);
                    }
                });
                
                // Keep submenu visible while hovering over it (only in collapsed state)
                submenu.addEventListener('mouseenter', function () {
                    if (!document.body.classList.contains('sidebar-collapsed')) {
                        return;
                    }

                    // Clear any pending hide for THIS submenu
                    if (submenu._hideTimeout) {
                        clearTimeout(submenu._hideTimeout);
                        submenu._hideTimeout = null;
                    }
                    
                    submenu.style.visibility = 'visible';
                    submenu.style.opacity = '1';
                    submenu.style.pointerEvents = 'auto';
                });
                
                submenu.addEventListener('mouseleave', function () {
                    if (!document.body.classList.contains('sidebar-collapsed')) {
                        return;
                    }

                    // Add delay before hiding
                    submenu._hideTimeout = setTimeout(() => {
                        submenu.style.visibility = 'hidden';
                        submenu.style.opacity = '0';
                        submenu.style.pointerEvents = 'none';
                        submenu._hideTimeout = null;
                    }, 100);
                });
            }
        });
    });
</script>