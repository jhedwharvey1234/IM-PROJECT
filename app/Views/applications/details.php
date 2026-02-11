<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details</title>
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
            <a href="<?= site_url('applications/edit/' . $application['id']) ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="<?= site_url('applications') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

        <h1 class="mb-4">Application Details</h1>

        <div class="row">
            <div class="col-md-8">
            <!-- Application Details Card -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Application Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>App Code:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="badge bg-info"><?= esc($application['app_code']) ?></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>App Name:</strong>
                        </div>
                        <div class="col-md-9">
                            <?= esc($application['app_name']) ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Description:</strong>
                        </div>
                        <div class="col-md-9">
                            <?= !empty($application['description']) ? nl2br(esc($application['description'])) : '<em class="text-muted">No description</em>' ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Department:</strong>
                        </div>
                        <div class="col-md-9">
                            <?= esc($application['department_name'] ?? 'N/A') ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Owner:</strong>
                        </div>
                        <div class="col-md-9">
                            <?= esc($application['owner_name'] ?? 'N/A') ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Criticality:</strong>
                        </div>
                        <div class="col-md-9">
                            <?php if ($application['business_criticality'] === 'High'): ?>
                                <span class="badge bg-danger">High</span>
                            <?php elseif ($application['business_criticality'] === 'Medium'): ?>
                                <span class="badge bg-warning">Medium</span>
                            <?php elseif ($application['business_criticality'] === 'Low'): ?>
                                <span class="badge bg-success">Low</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">N/A</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="badge bg-secondary"><?= esc($application['status_name'] ?? 'N/A') ?></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Version:</strong>
                        </div>
                        <div class="col-md-9">
                            <?= esc($application['version'] ?? 'N/A') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- URLs Card -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">URLs</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($application['repository_url'])): ?>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Repository:</strong>
                            </div>
                            <div class="col-md-9">
                                <a href="<?= esc($application['repository_url']) ?>" target="_blank" class="btn btn-sm btn-link">
                                    <?= esc($application['repository_url']) ?>
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($application['production_url'])): ?>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong>Production:</strong>
                            </div>
                            <div class="col-md-9">
                                <a href="<?= esc($application['production_url']) ?>" target="_blank" class="btn btn-sm btn-link">
                                    <?= esc($application['production_url']) ?>
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (empty($application['repository_url']) && empty($application['production_url'])): ?>
                        <p class="text-muted">No URLs configured</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Technologies Card -->
            <?php if (!empty($technologies)): ?>
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Technologies</h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <?php foreach ($technologies as $tech): ?>
                                <span class="badge bg-light text-dark me-2 mb-2">
                                    <?= esc($tech['technology_name']) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Activity Log -->
            <?php if (!empty($logs)): ?>
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Activity Log</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Performed By</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td><?= esc($log['action']) ?></td>
                                            <td><?= esc($log['performed_by']) ?></td>
                                            <td><?= date('M d, Y H:i', strtotime($log['action_date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

            <!-- Sidebar Info -->
            <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Quick Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Created:</small>
                        <p class="mb-0"><?= date('M d, Y', strtotime($application['date_created'])) ?></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Last Updated:</small>
                        <p class="mb-0"><?= date('M d, Y', strtotime($application['last_updated'])) ?></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Total Logs:</small>
                        <p class="mb-0"><?= count($logs ?? []) ?></p>
                    </div>
            </div>
        </div>
    </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
