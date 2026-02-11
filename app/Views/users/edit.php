<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
        .section-card { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #0d6efd; }
        .section-card h5 { color: #0d6efd; font-weight: 600; margin-bottom: 15px; display: flex; align-items: center; }
        .section-card h5 i { margin-right: 10px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Edit User']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('users') ?>" title="Go to Users">Users</a>
            <span class="separator">›</span>
            <span class="current">Edit User #<?= $user['id'] ?></span>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <h5 class="mb-3"><i class="bi bi-exclamation-circle"></i> Validation Errors</h5>
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('users/update/' . $user['id']) ?>" method="post">
            <?= csrf_field() ?>
            
            <!-- User Information Section -->
            <div class="section-card">
                <h5><i class="bi bi-person-circle"></i> User Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= old('username', $user['username']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                        <small class="text-muted">Leave blank to keep current password</small>
                    </div>
                    <div class="col-md-6">
                        <label for="usertype" class="form-label">Usertype <span class="text-danger">*</span></label>
                        <select class="form-select" id="usertype" name="usertype" required>
                            <option value="">Select Usertype</option>
                            <option value="readonly" <?= old('usertype', $user['usertype']) == 'readonly' ? 'selected' : '' ?>>Readonly</option>
                            <option value="readandwrite" <?= old('usertype', $user['usertype']) == 'readandwrite' ? 'selected' : '' ?>>Read and Write</option>
                            <option value="superadmin" <?= old('usertype', $user['usertype']) == 'superadmin' ? 'selected' : '' ?>>Superadmin</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sync_to_assignable" name="sync_to_assignable" value="1" <?= isset($is_assignable) && $is_assignable ? 'checked' : '' ?>>
                            <label class="form-check-label" for="sync_to_assignable">
                                <i class="bi bi-arrow-left-right"></i> Sync to Assignable Users
                                <small class="text-muted d-block">When checked, this user will be available for asset and peripheral assignments</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update User</button>
                <a href="<?= site_url('users') ?>" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>