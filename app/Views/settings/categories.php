<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management</title>
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
        .category-card { background: white; border-radius: 5px; padding: 15px; margin-bottom: 15px; border-left: 4px solid; display: flex; justify-content: space-between; align-items: center; }
        .category-badge { width: 24px; height: 24px; border-radius: 3px; display: inline-block; margin-right: 10px; }
        .color-picker-wrapper { display: flex; align-items: center; gap: 10px; }
        .color-preview { width: 40px; height: 40px; border-radius: 4px; border: 1px solid #ddd; cursor: pointer; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Category Management']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('settings') ?>" title="Go to Settings">Settings</a>
            <span class="separator">›</span>
            <span class="current">Category Management</span>
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

        <?php $errors = session('errors'); ?>
        <?php if (!empty($errors) && is_array($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <div class="fw-semibold mb-2"><i class="bi bi-exclamation-circle"></i> Please fix the following:</div>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div style="background: white; border-radius: 5px; padding: 20px;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3><i class="bi bi-tag"></i> Asset Categories</h3>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="bi bi-plus-circle"></i> Add Category
                        </button>
                    </div>

                    <?php if (!empty($categories)): ?>
                        <div class="categories-list">
                            <?php foreach ($categories as $category): ?>
                                <div class="category-card" style="border-left-color: <?= esc($category['color']) ?>;">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="category-badge" style="background-color: <?= esc($category['color']) ?>;"></div>
                                        <div>
                                            <h5 class="mb-1"><?= esc($category['name']) ?></h5>
                                            <p class="text-muted mb-0 small"><?= esc($category['description'] ?? 'No description') ?></p>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 align-items-center">
                                        <?php if ($category['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?= $category['id'] ?>" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="<?= site_url('settings/delete-category/' . $category['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Edit Category Modal -->
                                <div class="modal fade" id="editCategoryModal<?= $category['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Category</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="<?= site_url('settings/save-category') ?>" method="post">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name_<?= $category['id'] ?>" class="form-label">Category Name *</label>
                                                        <input type="text" class="form-control" id="name_<?= $category['id'] ?>" name="name" value="<?= esc($category['name']) ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="description_<?= $category['id'] ?>" class="form-label">Description</label>
                                                        <textarea class="form-control" id="description_<?= $category['id'] ?>" name="description" rows="3"><?= esc($category['description'] ?? '') ?></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="color_<?= $category['id'] ?>" class="form-label">Color</label>
                                                        <div class="color-picker-wrapper">
                                                            <input type="color" class="form-control form-control-color" id="color_<?= $category['id'] ?>" name="color" value="<?= esc($category['color']) ?>" style="width: 50px; height: 40px;">
                                                            <span class="text-muted"><?= esc($category['color']) ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" id="is_active_<?= $category['id'] ?>" name="is_active" value="1" <?= $category['is_active'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="is_active_<?= $category['id'] ?>">
                                                            Active
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Category</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> No categories found. Click "Add Category" to create one.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div style="background: white; border-radius: 5px; padding: 20px;">
                    <h5><i class="bi bi-info-circle"></i> Information</h5>
                    <p class="small text-muted">
                        Asset categories are used to organize and classify assets in the system. You can create custom categories with different colors for easy identification.
                    </p>
                    <div class="mt-4">
                        <h6>Quick Actions</h6>
                        <ul class="small">
                            <li>Click the <strong>pencil icon</strong> to edit a category</li>
                            <li>Click the <strong>trash icon</strong> to delete a category</li>
                            <li>Use <strong>colors</strong> to distinguish categories visually</li>
                            <li>Toggle <strong>Active</strong> to enable/disable categories</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?= view('partials/footer') ?>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= site_url('settings/save-category') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="categoryName" class="form-label">Category Name *</label>
                            <input type="text" class="form-control" id="categoryName" name="name" required placeholder="e.g., Laptops, Monitors, etc.">
                        </div>
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="categoryDescription" name="description" rows="3" placeholder="Brief description of this category..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="categoryColor" class="form-label">Color</label>
                            <div class="color-picker-wrapper">
                                <input type="color" class="form-control form-control-color" id="categoryColor" name="color" value="#0d6efd" style="width: 50px; height: 40px;">
                                <span class="text-muted" id="colorValue">#0d6efd</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Category</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update color value display
        document.getElementById('categoryColor').addEventListener('change', function() {
            document.getElementById('colorValue').textContent = this.value;
        });
    </script>
</body>
</html>
