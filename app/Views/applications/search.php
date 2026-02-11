<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Applications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('dashboard') ?>">Inventory Management</a>
            <div class="ms-auto">
                <a href="<?= site_url('auth/logout') ?>" class="btn btn-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="<?= site_url('dashboard') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <?php if (session()->get('usertype') === 'superadmin'): ?>
            <a href="<?= site_url('users') ?>"><i class="bi bi-people"></i> Manage Users</a>
            <a href="<?= site_url('units') ?>"><i class="bi bi-building"></i> Manage Units</a>
            <a href="<?= site_url('assets') ?>"><i class="bi bi-laptop"></i> Manage Assets</a>
            <a href="<?= site_url('peripherals') ?>"><i class="bi bi-mouse"></i> Manage Peripherals</a>
            <a href="<?= site_url('applications') ?>" style="background-color: #e9ecef;"><i class="bi bi-window-stack"></i> Application Management</a>
            <a href="<?= site_url('settings') ?>"><i class="bi bi-gear"></i> Settings</a>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2><?= esc($title) ?></h2>
            </div>
            <div class="col-md-4 text-end">
                <a href="<?= site_url('applications') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Applications
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="get" action="<?= site_url('applications/search') ?>" class="mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" placeholder="Search applications..." 
                               value="<?= esc($keyword) ?>" minlength="3" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                    <small class="form-text text-muted">Search in application name, code, or owner</small>
                </form>
            </div>
        </div>

        <?php if (!empty($keyword) && !empty($applications)): ?>
            <div class="mt-4">
                <h5>Search Results for "<?= esc($keyword) ?>" (<?= count($applications) ?> found)</h5>
                <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>App Code</th>
                            <th>App Name</th>
                            <th>Department</th>
                            <th>Owner</th>
                            <th>Criticality</th>
                            <th>Status</th>
                            <th>Version</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-info"><?= esc($app['app_code']) ?></span>
                                </td>
                                <td><?= esc($app['app_name']) ?></td>
                                <td><?= esc($app['department_name'] ?? 'N/A') ?></td>
                                <td><?= esc($app['owner_name'] ?? 'N/A') ?></td>
                                <td>
                                    <?php if ($app['business_criticality'] === 'High'): ?>
                                        <span class="badge bg-danger">High</span>
                                    <?php elseif ($app['business_criticality'] === 'Medium'): ?>
                                        <span class="badge bg-warning">Medium</span>
                                    <?php elseif ($app['business_criticality'] === 'Low'): ?>
                                        <span class="badge bg-success">Low</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?= esc($app['status_name'] ?? 'N/A') ?>
                                    </span>
                                </td>
                                <td><?= esc($app['version'] ?? 'N/A') ?></td>
                                <td>
                                    <a href="<?= site_url('applications/details/' . $app['id']) ?>" 
                                       class="btn btn-info btn-sm" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= site_url('applications/edit/' . $app['id']) ?>" 
                                       class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= site_url('applications/delete/' . $app['id']) ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to delete this application?')" 
                                       title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            </div>
        <?php elseif (!empty($keyword)): ?>
            <div class="alert alert-info mt-3" role="alert">
                <i class="bi bi-info-circle"></i> No applications found matching "<?= esc($keyword) ?>".
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-3" role="alert">
                <i class="bi bi-info-circle"></i> Enter a search term with at least 3 characters to search.
            </div>
    <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
