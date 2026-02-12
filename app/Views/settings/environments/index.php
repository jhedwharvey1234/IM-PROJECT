<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Environments</title>
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
    </style>    
</head>
<body>
    <?= view('partials/header', ['title' => 'Manage Environments']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('settings') ?>">Settings</a>
            <span class="separator">›</span>
            <span class="current">Environments</span>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-layers"></i> Environments</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                    <i class="bi bi-plus-circle"></i> Add Environment
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($environments)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Environment Name</th>
                                    <th>Application</th>
                                    <th>Server</th>
                                    <th>URL</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($environments as $env): ?>
                                    <tr>
                                        <td><?= $env['id'] ?></td>
                                        <td><?= esc($env['environment_name']) ?></td>
                                        <td><?= esc($env['application_name'] ?? 'N/A') ?></td>
                                        <td><?= esc($env['server_name'] ?? 'N/A') ?></td>
                                        <td>
                                            <?php if ($env['url']): ?>
                                                <a href="<?= esc($env['url']) ?>" target="_blank">
                                                    <i class="bi bi-link-45deg"></i>
                                                </a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $env['id'] ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <a href="<?= site_url('settings/environments/delete/' . $env['id']) ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this environment?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal<?= $env['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="<?= site_url('settings/environments/update/' . $env['id']) ?>" method="post">
                                                    <?= csrf_field() ?>
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Environment</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="environment_name" class="form-label">Environment Name</label>
                                                            <select class="form-select" name="environment_name" required>
                                                                <option value="Development" <?= $env['environment_name'] == 'Development' ? 'selected' : '' ?>>Development</option>
                                                                <option value="Testing" <?= $env['environment_name'] == 'Testing' ? 'selected' : '' ?>>Testing</option>
                                                                <option value="Staging" <?= $env['environment_name'] == 'Staging' ? 'selected' : '' ?>>Staging</option>
                                                                <option value="Production" <?= $env['environment_name'] == 'Production' ? 'selected' : '' ?>>Production</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="application_id" class="form-label">Application</label>
                                                            <select class="form-select" name="application_id">
                                                                <option value="">Select Application</option>
                                                                <?php foreach ($applications as $app): ?>
                                                                    <option value="<?= $app['id'] ?>" <?= $env['application_id'] == $app['id'] ? 'selected' : '' ?>>
                                                                        <?= esc($app['app_name']) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="server_id" class="form-label">Server</label>
                                                            <select class="form-select" name="server_id">
                                                                <option value="">Select Server</option>
                                                                <?php foreach ($servers as $server): ?>
                                                                    <option value="<?= $server['id'] ?>" <?= $env['server_id'] == $server['id'] ? 'selected' : '' ?>>
                                                                        <?= esc($server['server_name']) ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="url" class="form-label">URL</label>
                                                            <input type="url" class="form-control" name="url" 
                                                                   value="<?= esc($env['url']) ?>">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No environments found.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?= site_url('settings/environments/store') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="modal-header">
                            <h5 class="modal-title">Add Environment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="environment_name" class="form-label">Environment Name</label>
                                <select class="form-select" name="environment_name" required>
                                    <option value="">Select Environment</option>
                                    <option value="Development">Development</option>
                                    <option value="Testing">Testing</option>
                                    <option value="Staging">Staging</option>
                                    <option value="Production">Production</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="application_id" class="form-label">Application</label>
                                <select class="form-select" name="application_id">
                                    <option value="">Select Application</option>
                                    <?php foreach ($applications as $app): ?>
                                        <option value="<?= $app['id'] ?>"><?= esc($app['app_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="server_id" class="form-label">Server</label>
                                <select class="form-select" name="server_id">
                                    <option value="">Select Server</option>
                                    <?php foreach ($servers as $server): ?>
                                        <option value="<?= $server['id'] ?>"><?= esc($server['server_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="url" class="form-label">URL</label>
                                <input type="url" class="form-control" name="url" placeholder="https://example.com">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Environment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?= view('partials/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
