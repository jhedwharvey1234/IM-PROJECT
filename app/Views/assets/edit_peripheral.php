<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peripheral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .detail-card { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Edit Peripheral']) ?>

    <div class="main-content">
        <h1>Edit Peripheral #<?= $peripheral['id'] ?></h1>
        
        <div class="mb-3">
            <a href="<?= site_url('assets/details/' . $asset['id']) ?>" class="btn btn-secondary">Back to Asset Details</a>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="detail-card">
            <h4 class="mb-4">Asset Information</h4>
            <p><strong>ID:</strong> <?= $asset['id'] ?></p>
            <p><strong>Asset Tag:</strong> <?= $asset['asset_tag'] ?? '-' ?></p>
            <p><strong>Sender:</strong> <?= $asset['sender'] ?></p>
            <p><strong>Recipient:</strong> <?= $asset['recipient'] ?></p>
        </div>

        <form action="<?= site_url('assets/peripheral/update/' . $peripheral['id']) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="peripheral_type_id" class="form-label">Peripheral Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="peripheral_type_id" name="peripheral_type_id" required>
                        <option value="">Select Type</option>
                        <?php foreach ($peripheral_types as $id => $name): ?>
                            <option value="<?= $id ?>" <?= old('peripheral_type_id', $peripheral['peripheral_type_id']) == $id ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" id="brand" name="brand" value="<?= old('brand', $peripheral['brand']) ?>">
                </div>

                <div class="col-md-4">
                    <label for="model" class="form-label">Model</label>
                    <input type="text" class="form-control" id="model" name="model" value="<?= old('model', $peripheral['model']) ?>">
                </div>

                <div class="col-md-4">
                    <label for="model_number" class="form-label">Model Number</label>
                    <input type="text" class="form-control" id="model_number" name="model_number" value="<?= old('model_number', $peripheral['model_number'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label for="serial_number" class="form-label">Serial Number</label>
                    <input type="text" class="form-control" id="serial_number" name="serial_number" value="<?= old('serial_number', $peripheral['serial_number']) ?>">
                </div>

                <div class="col-md-4">
                    <label for="department_id" class="form-label">Department</label>
                    <select class="form-select" id="department_id" name="department_id">
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $id => $name): ?>
                            <option value="<?= $id ?>" <?= old('department_id', $peripheral['department_id']) == $id ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="location_id" class="form-label">Location</label>
                    <select class="form-select" id="location_id" name="location_id">
                        <option value="">Select Location</option>
                        <?php foreach ($locations as $id => $name): ?>
                            <option value="<?= $id ?>" <?= old('location_id', $peripheral['location_id']) == $id ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="workstation_id" class="form-label">Workstation</label>
                    <select class="form-select" id="workstation_id" name="workstation_id">
                        <option value="">Select Workstation</option>
                        <?php foreach ($workstations as $id => $code): ?>
                            <option value="<?= $id ?>" <?= old('workstation_id', $peripheral['workstation_id']) == $id ? 'selected' : '' ?>><?= $code ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="assigned_to_user_id" class="form-label">Assigned To</label>
                    <select class="form-select" id="assigned_to_user_id" name="assigned_to_user_id">
                        <option value="">Select User</option>
                        <?php foreach ($assignable_users as $id => $name): ?>
                            <option value="<?= $id ?>" <?= old('assigned_to_user_id', $peripheral['assigned_to_user_id']) == $id ? 'selected' : '' ?>><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="available" <?= old('status', $peripheral['status']) == 'available' ? 'selected' : '' ?>>Available</option>
                        <option value="in_use" <?= old('status', $peripheral['status']) == 'in_use' ? 'selected' : '' ?>>In Use</option>
                        <option value="standby" <?= old('status', $peripheral['status']) == 'standby' ? 'selected' : '' ?>>Standby</option>
                        <option value="under_repair" <?= old('status', $peripheral['status']) == 'under_repair' ? 'selected' : '' ?>>Under Repair</option>
                        <option value="retired" <?= old('status', $peripheral['status']) == 'retired' ? 'selected' : '' ?>>Retired</option>
                        <option value="lost" <?= old('status', $peripheral['status']) == 'lost' ? 'selected' : '' ?>>Lost</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="condition_status" class="form-label">Condition <span class="text-danger">*</span></label>
                    <select class="form-select" id="condition_status" name="condition_status" required>
                        <option value="new" <?= old('condition_status', $peripheral['condition_status']) == 'new' ? 'selected' : '' ?>>New</option>
                        <option value="good" <?= old('condition_status', $peripheral['condition_status']) == 'good' ? 'selected' : '' ?>>Good</option>
                        <option value="fair" <?= old('condition_status', $peripheral['condition_status']) == 'fair' ? 'selected' : '' ?>>Fair</option>
                        <option value="damaged" <?= old('condition_status', $peripheral['condition_status']) == 'damaged' ? 'selected' : '' ?>>Damaged</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="criticality" class="form-label">Criticality <span class="text-danger">*</span></label>
                    <select class="form-select" id="criticality" name="criticality" required>
                        <option value="low" <?= old('criticality', $peripheral['criticality']) == 'low' ? 'selected' : '' ?>>Low</option>
                        <option value="medium" <?= old('criticality', $peripheral['criticality']) == 'medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="high" <?= old('criticality', $peripheral['criticality']) == 'high' ? 'selected' : '' ?>>High</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="purchase_date" class="form-label">Purchase Date</label>
                    <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?= old('purchase_date', $peripheral['purchase_date']) ?>">
                </div>

                <div class="col-md-3">
                    <label for="purchase_cost" class="form-label">Purchase Cost</label>
                    <input type="number" step="0.01" class="form-control" id="purchase_cost" name="purchase_cost" value="<?= old('purchase_cost', $peripheral['purchase_cost'] ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label for="order_number" class="form-label">Order Number</label>
                    <input type="text" class="form-control" id="order_number" name="order_number" value="<?= old('order_number', $peripheral['order_number'] ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label for="supplier" class="form-label">Supplier</label>
                    <input type="text" class="form-control" id="supplier" name="supplier" value="<?= old('supplier', $peripheral['supplier'] ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <label for="qty" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="qty" name="qty" value="<?= old('qty', $peripheral['qty'] ?? 1) ?>">
                </div>

                <div class="col-md-6">
                    <label for="vendor" class="form-label">Vendor</label>
                    <input type="text" class="form-control" id="vendor" name="vendor" value="<?= old('vendor', $peripheral['vendor']) ?>">
                </div>

                <div class="col-md-6">
                    <label for="warranty_expiry" class="form-label">Warranty Expiry</label>
                    <input type="date" class="form-control" id="warranty_expiry" name="warranty_expiry" value="<?= old('warranty_expiry', $peripheral['warranty_expiry']) ?>">
                </div>

                <div class="col-md-6">
                    <label for="device_image" class="form-label">Device Image</label>
                    <input type="file" class="form-control" id="device_image" name="device_image" accept="image/*">
                    <?php if (!empty($peripheral['device_image'])): ?>
                        <small class="text-muted">Current: <?= esc($peripheral['device_image']) ?></small>
                    <?php endif; ?>
                </div>

                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="requestable" name="requestable" value="1" <?= old('requestable', $peripheral['requestable'] ?? 0) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="requestable">
                            Requestable
                        </label>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="byod" name="byod" value="1" <?= old('byod', $peripheral['byod'] ?? 0) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="byod">
                            BYOD
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Update Peripheral</button>
                    <a href="<?= site_url('assets/details/' . $asset['id']) ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
