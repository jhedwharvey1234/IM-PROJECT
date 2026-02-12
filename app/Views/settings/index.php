<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .breadcrumb-nav { background-color: #e9ecef; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; font-size: 16px; }
        .breadcrumb-nav a { color: #0d6efd; text-decoration: none; display: flex; align-items: center; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .breadcrumb-nav .separator { margin: 0 10px; color: #6c757d; }
        .breadcrumb-nav .current { color: #212529; font-weight: 500; }
        .settings-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
        .settings-card { border: 1px solid #e5e5e5; border-radius: 6px; padding: 16px; background: #fff; }
        .settings-card a { text-decoration: none; font-weight: 600; }
        .settings-card h5 { display: flex; align-items: center; gap: 8px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Settings']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <span class="current">Settings</span>
        </div>

        <div class="mb-4">
            <h3><i class="bi bi-gear"></i> Settings</h3>
            <p class="text-muted mb-0">Manage lookup data used across the system.</p>
        </div>

        <div class="settings-grid">
            <div class="settings-card">
                <h5><i class="bi bi-geo-alt"></i> Locations</h5>
                <p class="text-muted">Branches and office locations.</p>
                <a href="<?= site_url('settings/locations') ?>">Manage Locations</a>
            </div>
            <div class="settings-card">
                <h5><i class="bi bi-pc-display"></i> Workstations</h5>
                <p class="text-muted">Workstation codes and mapping.</p>
                <a href="<?= site_url('settings/workstations') ?>">Manage Workstations</a>
            </div>
            <div class="settings-card">
                <h5><i class="bi bi-mouse"></i> Peripheral Types</h5>
                <p class="text-muted">Hardware type list.</p>
                <a href="<?= site_url('settings/peripheral-types') ?>">Manage Peripheral Types</a>
            </div>
            <div class="settings-card">
                <h5><i class="bi bi-diagram-3"></i> Departments</h5>
                <p class="text-muted">Department list for assignments.</p>
                <a href="<?= site_url('settings/departments') ?>">Manage Departments</a>
            </div>
            <div class="settings-card">
                <h5><i class="bi bi-tag"></i> Asset Categories</h5>
                <p class="text-muted">Asset classification categories.</p>
                <a href="<?= site_url('settings/categories') ?>">Manage Categories</a>
            </div>
            <div class="settings-card">
                <h5><i class="bi bi-code-square"></i> Technologies</h5>
                <p class="text-muted">Application technology stack.</p>
                <a href="<?= site_url('settings/technologies') ?>">Manage Technologies</a>
            </div>
            <div class="settings-card">
                <h5><i class="bi bi-flag"></i> Application Statuses</h5>
                <p class="text-muted">Application lifecycle statuses.</p>
                <a href="<?= site_url('settings/application-status') ?>">Manage Statuses</a>
            </div>
            <div class="settings-card">
                <h5><i class="bi bi-server"></i> Servers</h5>
                <p class="text-muted">Application server infrastructure.</p>
                <a href="<?= site_url('settings/servers') ?>">Manage Servers</a>
            </div>
            <div class="settings-card">
                <h5><i class="bi bi-layers"></i> Environments</h5>
                <p class="text-muted">Deployment environments.</p>
                <a href="<?= site_url('settings/environments') ?>">Manage Environments</a>
            </div>
            <div class="settings-card">
                <h5><i class="bi bi-person-lines-fill"></i> Application Contacts</h5>
                <p class="text-muted">Application owners and contacts.</p>
                <a href="<?= site_url('settings/application-contacts') ?>">Manage Contacts</a>
            </div>
        </div>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
