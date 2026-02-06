<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .settings-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
        .settings-card { border: 1px solid #ddd; border-radius: 6px; padding: 16px; background: #fff; }
        .settings-card a { text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Settings']) ?>

    <div class="main-content">
        <h1>Settings</h1>
        <p class="text-muted">Manage lookup data used across peripherals.</p>

        <div class="settings-grid">
            <div class="settings-card">
                <h5>Locations</h5>
                <p class="text-muted">Branches and office locations.</p>
                <a href="<?= site_url('settings/locations') ?>">Manage Locations</a>
            </div>
            <div class="settings-card">
                <h5>Workstations</h5>
                <p class="text-muted">Workstation codes and mapping.</p>
                <a href="<?= site_url('settings/workstations') ?>">Manage Workstations</a>
            </div>
            <div class="settings-card">
                <h5>Peripheral Types</h5>
                <p class="text-muted">Hardware type list.</p>
                <a href="<?= site_url('settings/peripheral-types') ?>">Manage Peripheral Types</a>
            </div>
            <div class="settings-card">
                <h5>Departments</h5>
                <p class="text-muted">Department list for assignments.</p>
                <a href="<?= site_url('settings/departments') ?>">Manage Departments</a>
            </div>
            <div class="settings-card">
                <h5>Assignable Users</h5>
                <p class="text-muted">Users for peripheral assignment.</p>
                <a href="<?= site_url('settings/assigned-users') ?>">Manage Assignable Users</a>
            </div>
        </div>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
