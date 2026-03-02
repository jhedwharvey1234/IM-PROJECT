<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; background-color: #eeeeee; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .breadcrumb-nav { background-color: #e9ecef; padding: 12px 20px; border-radius: 5px; margin-bottom: 20px; display: flex; align-items: center; font-size: 16px; }
        .breadcrumb-nav a { color: #0d6efd; text-decoration: none; display: flex; align-items: center; }
        .breadcrumb-nav a:hover { text-decoration: underline; }
        .breadcrumb-nav .separator { margin: 0 10px; color: #6c757d; }
        .breadcrumb-nav .current { color: #212529; font-weight: 500; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Edit User Role']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('settings') ?>" title="Go to Settings">Settings</a>
            <span class="separator">›</span>
            <a href="<?= site_url('settings/user-roles') ?>" title="Go to User Roles">User Roles</a>
            <span class="separator">›</span>
            <span class="current">Edit</span>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div style="background: white; border-radius: 5px; padding: 20px; max-width: 720px;">
            <h4 class="mb-3"><i class="bi bi-person-badge"></i> Edit User Role</h4>
            <form action="<?= site_url('settings/user-roles/update/' . $role['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Role Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="role_name" value="<?= old('role_name', $role['role_name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role Key <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="role_key" value="<?= old('role_key', $role['role_key']) ?>" required pattern="[A-Za-z0-9_-]+" title="Use letters, numbers, underscores, or dashes.">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3"><?= old('description', $role['description']) ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Changes</button>
                <a href="<?= site_url('settings/user-roles') ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
