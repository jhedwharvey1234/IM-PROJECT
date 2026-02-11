<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Application</title>
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
        <h1 class="mb-4">Edit Application</h1>

        <div class="card">
            <div class="card-body">
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger" role="alert">
                    <h5>Validation Errors:</h5>
                    <ul>
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('applications/update/' . $application['id']) ?>" method="post" class="needs-validation">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="app_code" class="form-label">Application Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="app_code" name="app_code" 
                               value="<?= old('app_code', $application['app_code']) ?>" required maxlength="30">
                        <small class="form-text text-muted">Unique identifier for the application</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="app_name" class="form-label">Application Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="app_name" name="app_name" 
                               value="<?= old('app_name', $application['app_name']) ?>" required maxlength="150">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="4"><?= old('description', $application['description']) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" name="department_id">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" 
                                    <?= old('department_id', $application['department_id']) == $dept['id'] ? 'selected' : '' ?>>
                                    <?= esc($dept['department_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="owner_name" class="form-label">Owner Name</label>
                        <input type="text" class="form-control" id="owner_name" name="owner_name" 
                               value="<?= old('owner_name', $application['owner_name']) ?>" maxlength="100">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="business_criticality" class="form-label">Business Criticality</label>
                        <select class="form-select" id="business_criticality" name="business_criticality">
                            <option value="">Select Criticality</option>
                            <option value="High" <?= old('business_criticality', $application['business_criticality']) === 'High' ? 'selected' : '' ?>>High</option>
                            <option value="Medium" <?= old('business_criticality', $application['business_criticality']) === 'Medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="Low" <?= old('business_criticality', $application['business_criticality']) === 'Low' ? 'selected' : '' ?>>Low</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status_id" class="form-label">Status</label>
                        <select class="form-select" id="status_id" name="status_id">
                            <option value="">Select Status</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= $status['id'] ?>" 
                                    <?= old('status_id', $application['status_id']) == $status['id'] ? 'selected' : '' ?>>
                                    <?= esc($status['status_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="repository_url" class="form-label">Repository URL</label>
                        <input type="url" class="form-control" id="repository_url" name="repository_url" 
                               value="<?= old('repository_url', $application['repository_url']) ?>" maxlength="255">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="production_url" class="form-label">Production URL</label>
                        <input type="url" class="form-control" id="production_url" name="production_url" 
                               value="<?= old('production_url', $application['production_url']) ?>" maxlength="255">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="version" class="form-label">Version</label>
                    <input type="text" class="form-control" id="version" name="version" 
                           value="<?= old('version', $application['version']) ?>" maxlength="20" placeholder="e.g., 1.0.0">
                </div>

                <div class="mb-3">
                    <label for="technologies" class="form-label">Technologies Used</label>
                    <div class="row g-3">
                        <?php foreach ($technologies as $tech): ?>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           id="tech_<?= $tech['id'] ?>" 
                                           name="technologies[]" 
                                           value="<?= $tech['id'] ?>"
                                           <?= in_array($tech['id'], array_map(fn($t) => $t['id'], $selectedTechnologies)) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="tech_<?= $tech['id'] ?>">
                                        <?= esc($tech['technology_name']) ?>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Update Application
                    </button>
                    <a href="<?= site_url('applications') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
