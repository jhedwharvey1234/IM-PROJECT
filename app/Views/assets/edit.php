<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Asset</title>
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
        .form-section { margin-bottom: 10px; }
        .date-field { display: none; }
        .date-field.active { display: block; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Edit Asset']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('assets') ?>" title="Go to Assets">Assets</a>
            <span class="separator">›</span>
            <span class="current">Edit Asset: <?= !empty($asset['model']) ? esc($asset['model']) : 'Asset #' . $asset['id'] ?></span>
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

        <form action="<?= site_url('assets/update/' . $asset['id']) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
           
           
            <!-- Basic Information Section -->
            <div class="section-card">
                <h5><i class="bi bi-info-circle"></i> Basic Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="asset_tag" class="form-label">Asset Tag</label>
                        <input type="text" class="form-control" id="asset_tag" name="asset_tag" value="<?= old('asset_tag', $asset['asset_tag']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" class="form-control" id="serial_number" name="serial_number" value="<?= old('serial_number', $asset['serial_number'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="model" class="form-label">Model</label>
                        <input type="text" class="form-control" id="model" name="model" value="<?= old('model', $asset['model'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="model_number" class="form-label">Model Number</label>
                        <input type="text" class="form-control" id="model_number" name="model_number" value="<?= old('model_number', $asset['model_number'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="manufacturer" class="form-label">Manufacturer</label>
                        <input type="text" class="form-control" id="manufacturer" name="manufacturer" value="<?= old('manufacturer', $asset['manufacturer'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $id => $name): ?>
                                <option value="<?= $id ?>" <?= old('category', $asset['category'] ?? '') == $id ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="qty" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="qty" name="qty" min="1" value="<?= old('qty', $asset['qty'] ?? '1') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="unit_id" class="form-label">Unit (Optional)</label>
                        <select class="form-select" id="unit_id" name="unit_id">
                            <option value="">Select Unit</option>
                            <?php foreach ($units as $id => $unitName): ?>
                                <option value="<?= $id ?>" <?= old('unit_id', $asset['unit_id'] ?? '') == $id ? 'selected' : '' ?>><?= esc($unitName) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Media & Files Section -->
            <div class="section-card">
                <h5><i class="bi bi-image"></i> Media & Files</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="device_image" class="form-label">Device Image</label>
                        <?php if (!empty($asset['device_image'])): ?>
                            <div class="mb-2">
                                <img src="<?= base_url('uploads/devices/' . $asset['device_image']) ?>" alt="device" style="max-height:120px; max-width:200px;" class="rounded border">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="device_image" name="device_image" accept="image/*">
                        <small class="text-muted d-block mt-1">Upload a device photo</small>
                    </div>
                    <div class="col-md-6">
                        <label for="barcode" class="form-label">Barcode Image</label>
                        <?php if (!empty($asset['barcode'])): ?>
                            <div class="mb-2">
                                <img src="<?= base_url('uploads/barcodes/' . $asset['barcode']) ?>" alt="barcode" style="max-height:120px; max-width:200px;" class="rounded border">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="barcode" name="barcode" accept="image/*">
                        <small class="text-muted d-block mt-1">Upload barcode image</small>
                    </div>
                </div>
            </div>

           
            <!-- Status & Purchase Section -->
            <div class="section-card">
                <h5><i class="bi bi-clipboard-check"></i> Status & Purchase</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pending" <?= old('status', $asset['status']) === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="ready to deploy" <?= old('status', $asset['status']) === 'ready to deploy' ? 'selected' : '' ?>>Ready to Deploy</option>
                            <option value="archived" <?= old('status', $asset['status']) === 'archived' ? 'selected' : '' ?>>Archived</option>
                            <option value="broken - not fixable" <?= old('status', $asset['status']) === 'broken - not fixable' ? 'selected' : '' ?>>Broken - Not Fixable</option>
                            <option value="lost/stolen" <?= old('status', $asset['status']) === 'lost/stolen' ? 'selected' : '' ?>>Lost/Stolen</option>
                            <option value="out for diagnostics" <?= old('status', $asset['status']) === 'out for diagnostics' ? 'selected' : '' ?>>Out for Diagnostics</option>
                            <option value="out for repair" <?= old('status', $asset['status']) === 'out for repair' ? 'selected' : '' ?>>Out for Repair</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?= old('purchase_date', !empty($asset['purchase_date']) ? date('Y-m-d', strtotime($asset['purchase_date'])) : '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="purchase_cost" class="form-label">Purchase Cost</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control" id="purchase_cost" name="purchase_cost" value="<?= old('purchase_cost', $asset['purchase_cost']) ?>" placeholder="0.00">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="order_number" class="form-label">Order Number</label>
                        <input type="text" class="form-control" id="order_number" name="order_number" value="<?= old('order_number', $asset['order_number']) ?>">
                    </div>
                    <div class="col-md-12">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier" name="supplier" value="<?= old('supplier', $asset['supplier']) ?>">
                    </div>
                </div>
            </div>

            <!-- Assignment Section -->
            <div class="section-card">
                <h5><i class="bi bi-person-check"></i> Assignment</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" name="department_id">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $id => $name): ?>
                                <option value="<?= $id ?>" <?= old('department_id', $asset['department_id']) == $id ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="location_id" class="form-label">Location</label>
                        <select class="form-select" id="location_id" name="location_id">
                            <option value="">Select Location</option>
                            <?php foreach ($locations as $id => $name): ?>
                                <option value="<?= $id ?>" <?= old('location_id', $asset['location_id']) == $id ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="workstation_id" class="form-label">Workstation</label>
                        <select class="form-select" id="workstation_id" name="workstation_id">
                            <option value="">Select Workstation</option>
                            <?php foreach ($workstations as $id => $code): ?>
                                <option value="<?= $id ?>" <?= old('workstation_id', $asset['workstation_id']) == $id ? 'selected' : '' ?>><?= $code ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="assigned_to_user_id" class="form-label">Assigned To</label>
                        <select class="form-select" id="assigned_to_user_id" name="assigned_to_user_id">
                            <option value="">Select User</option>
                            <?php foreach ($assignable_users as $id => $full_name): ?>
                                <option value="<?= $id ?>" <?= old('assigned_to_user_id', $asset['assigned_to_user_id']) == $id ? 'selected' : '' ?>><?= $full_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Flags Section -->
            <div class="section-card">
                <h5><i class="bi bi-flag"></i> Flags & Options</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="requestable" name="requestable" value="1" <?= old('requestable', $asset['requestable']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="requestable">
                                <strong>Requestable</strong>
                                <small class="d-block text-muted">Allow users to request this asset</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="byod" name="byod" value="1" <?= old('byod', $asset['byod']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="byod">
                                <strong>BYOD</strong>
                                <small class="d-block text-muted">Bring Your Own Device</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Section -->
            <div class="section-card">
                <h5><i class="bi bi-file-text"></i> Notes & Description</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter additional details about this asset..."><?= old('description', $asset['description']) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions Section -->
            <div class="row g-2 mt-5">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-check-circle"></i> Update Asset
                    </button>
                    <a href="<?= site_url('assets') ?>" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
