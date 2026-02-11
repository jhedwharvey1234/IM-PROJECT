<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peripheral Type Management</title>
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
    <?= view('partials/header', ['title' => 'Peripheral Type Management']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('settings') ?>" title="Go to Settings">Settings</a>
            <span class="separator">›</span>
            <span class="current">Peripheral Type Management</span>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div style="background: white; border-radius: 5px; padding: 20px;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3><i class="bi bi-mouse"></i> Peripheral Types</h3>
                        <a href="<?= site_url('settings/peripheral-types/create') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Type
                        </a>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($types as $type): ?>
                                <tr>
                                    <td><?= $type['id'] ?></td>
                                    <td><?= esc($type['type_name']) ?></td>
                                    <td>
                                        <a href="<?= site_url('settings/peripheral-types/edit/' . $type['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="<?= site_url('settings/peripheral-types/delete/' . $type['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-4">
                <div style="background: white; border-radius: 5px; padding: 20px;">
                    <h5><i class="bi bi-info-circle"></i> Information</h5>
                    <p class="small text-muted">
                        Peripheral types classify devices like keyboards, mice, and monitors for consistent filtering.
                    </p>
                    <div class="mt-4">
                        <h6>Quick Actions</h6>
                        <ul class="small">
                            <li>Add types before creating peripherals</li>
                            <li>Edit names to match vendor naming</li>
                            <li>Remove unused types to reduce clutter</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
