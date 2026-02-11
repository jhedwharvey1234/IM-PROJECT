<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Peripheral</title>
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
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Create Peripheral']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('peripherals') ?>" title="Go to Peripherals">Peripherals</a>
            <span class="separator">›</span>
            <span class="current">Create New Peripheral</span>
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

        <form action="<?= site_url('peripherals/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <!-- Basic Information Section -->
            <div class="section-card">
                <h5><i class="bi bi-info-circle"></i> Basic Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="peripheral_type_id" class="form-label">Peripheral Type *</label>
                        <select class="form-select" id="peripheral_type_id" name="peripheral_type_id" required>
                            <option value="">Select Peripheral Type</option>
                            <?php foreach ($peripheral_types as $id => $type_name): ?>
                                <option value="<?= $id ?>" <?= old('peripheral_type_id') == $id ? 'selected' : '' ?>><?= $type_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" value="<?= old('brand') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="model" class="form-label">Model</label>
                        <input type="text" class="form-control" id="model" name="model" value="<?= old('model') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="model_number" class="form-label">Model Number</label>
                        <input type="text" class="form-control" id="model_number" name="model_number" value="<?= old('model_number') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" class="form-control" id="serial_number" name="serial_number" value="<?= old('serial_number') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="qty" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="qty" name="qty" min="1" value="<?= old('qty', '1') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="asset_id" class="form-label">Link to Asset (Optional)</label>
                        <select class="form-select" id="asset_id" name="asset_id">
                            <option value="">Select Asset (Optional)</option>
                            <?php foreach ($assets as $id => $asset_info): ?>
                                <option value="<?= $id ?>" <?= old('asset_id') == $id ? 'selected' : '' ?>><?= $asset_info ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="unit_id" class="form-label">Unit (Optional)</label>
                        <select class="form-select" id="unit_id" name="unit_id">
                            <option value="">Select Unit</option>
                            <?php foreach ($units as $id => $unitName): ?>
                                <option value="<?= $id ?>" <?= old('unit_id') == $id ? 'selected' : '' ?>><?= esc($unitName) ?></option>
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
                        <input type="file" class="form-control" id="device_image" name="device_image" accept="image/*">
                        <small class="text-muted d-block mt-1">Upload a device photo</small>
                    </div>
                </div>
            </div>

            <!-- Status & Condition Section -->
            <div class="section-card">
                <h5><i class="bi bi-clipboard-check"></i> Status & Condition</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="available" <?= old('status', 'available') === 'available' ? 'selected' : '' ?>>Available</option>
                            <option value="in_use" <?= old('status') === 'in_use' ? 'selected' : '' ?>>In Use</option>
                            <option value="standby" <?= old('status') === 'standby' ? 'selected' : '' ?>>Standby</option>
                            <option value="under_repair" <?= old('status') === 'under_repair' ? 'selected' : '' ?>>Under Repair</option>
                            <option value="retired" <?= old('status') === 'retired' ? 'selected' : '' ?>>Retired</option>
                            <option value="lost" <?= old('status') === 'lost' ? 'selected' : '' ?>>Lost</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="condition_status" class="form-label">Condition *</label>
                        <select class="form-select" id="condition_status" name="condition_status" required>
                            <option value="new" <?= old('condition_status', 'good') === 'new' ? 'selected' : '' ?>>New</option>
                            <option value="good" <?= old('condition_status', 'good') === 'good' ? 'selected' : '' ?>>Good</option>
                            <option value="fair" <?= old('condition_status') === 'fair' ? 'selected' : '' ?>>Fair</option>
                            <option value="damaged" <?= old('condition_status') === 'damaged' ? 'selected' : '' ?>>Damaged</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="criticality" class="form-label">Criticality *</label>
                        <select class="form-select" id="criticality" name="criticality" required>
                            <option value="low" <?= old('criticality', 'medium') === 'low' ? 'selected' : '' ?>>Low</option>
                            <option value="medium" <?= old('criticality', 'medium') === 'medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="high" <?= old('criticality') === 'high' ? 'selected' : '' ?>>High</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Purchase Information Section -->
            <div class="section-card">
                <h5><i class="bi bi-receipt"></i> Purchase Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?= old('purchase_date') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="warranty_expiry" class="form-label">Warranty Expiry</label>
                        <input type="date" class="form-control" id="warranty_expiry" name="warranty_expiry" value="<?= old('warranty_expiry') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="purchase_cost" class="form-label">Purchase Cost</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control" id="purchase_cost" name="purchase_cost" value="<?= old('purchase_cost') ?>" placeholder="0.00">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="order_number" class="form-label">Order Number</label>
                        <input type="text" class="form-control" id="order_number" name="order_number" value="<?= old('order_number') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier" name="supplier" value="<?= old('supplier') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="vendor" class="form-label">Vendor</label>
                        <input type="text" class="form-control" id="vendor" name="vendor" value="<?= old('vendor') ?>">
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
                            <?php foreach ($departments as $id => $department_name): ?>
                                <option value="<?= $id ?>" <?= old('department_id') == $id ? 'selected' : '' ?>><?= $department_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="location_id" class="form-label">Location</label>
                        <select class="form-select" id="location_id" name="location_id">
                            <option value="">Select Location</option>
                            <?php foreach ($locations as $id => $name): ?>
                                <option value="<?= $id ?>" <?= old('location_id') == $id ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="workstation_id" class="form-label">Workstation</label>
                        <select class="form-select" id="workstation_id" name="workstation_id">
                            <option value="">Select Workstation</option>
                            <?php foreach ($workstations as $id => $code): ?>
                                <option value="<?= $id ?>" <?= old('workstation_id') == $id ? 'selected' : '' ?>><?= $code ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="assigned_to_user_id" class="form-label">Assigned To</label>
                        <select class="form-select" id="assigned_to_user_id" name="assigned_to_user_id">
                            <option value="">Select User</option>
                            <?php foreach ($assignable_users as $id => $full_name): ?>
                                <option value="<?= $id ?>" <?= old('assigned_to_user_id') == $id ? 'selected' : '' ?>><?= $full_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Flags & Options Section -->
            <div class="section-card">
                <h5><i class="bi bi-flag"></i> Flags & Options</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="requestable" name="requestable" value="1" <?= old('requestable') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="requestable">
                                <strong>Requestable</strong>
                                <small class="d-block text-muted">Allow users to request this peripheral</small>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="byod" name="byod" value="1" <?= old('byod') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="byod">
                                <strong>BYOD</strong>
                                <small class="d-block text-muted">Bring Your Own Device</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions Section -->
            <div class="row g-2 mt-5">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle"></i> Create Peripheral
                    </button>
                    <a href="<?= site_url('peripherals') ?>" class="btn btn-secondary btn-lg">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const workstationsData = <?= json_encode($workstations_with_location) ?>;
        const locationSelect = document.getElementById('location_id');
        const workstationSelect = document.getElementById('workstation_id');

        locationSelect.addEventListener('change', function () {
            const selectedLocationId = this.value;
            
            // Clear current options except the placeholder
            workstationSelect.innerHTML = '<option value="">Select Workstation</option>';
            
            if (selectedLocationId) {
                // Filter workstations by location
                const filteredWorkstations = workstationsData.filter(ws => ws.location_id == selectedLocationId);
                
                // Add filtered workstations to dropdown
                filteredWorkstations.forEach(ws => {
                    const option = document.createElement('option');
                    option.value = ws.id;
                    option.textContent = ws.workstation_code;
                    workstationSelect.appendChild(option);
                });
            }
        });

        // Trigger filter on page load if location was previously selected
        if (locationSelect.value) {
            locationSelect.dispatchEvent(new Event('change'));
        }

        // Auto-populate fields when asset is selected
        const assetSelect = document.getElementById('asset_id');
        const departmentSelect = document.getElementById('department_id');
        const locationSelectField = document.getElementById('location_id');
        const workstationSelectField = document.getElementById('workstation_id');
        const assignedUserSelect = document.getElementById('assigned_to_user_id');

        assetSelect.addEventListener('change', function() {
            const assetId = this.value;
            
            if (assetId) {
                // Fetch asset details
                fetch('<?= site_url('peripherals/getAssetDetails/') ?>' + assetId)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            return;
                        }
                        
                        console.log('Asset details loaded:', data);
                        
                        // Auto-populate fields
                        if (data.department_id) {
                            departmentSelect.value = data.department_id;
                        }
                        if (data.location_id) {
                            locationSelectField.value = data.location_id;
                            // Trigger workstation filter
                            locationSelectField.dispatchEvent(new Event('change'));
                            
                            // Set workstation after the filter updates
                            setTimeout(() => {
                                if (data.workstation_id) {
                                    workstationSelectField.value = data.workstation_id;
                                }
                            }, 100);
                        }
                        if (data.assigned_to_user_id) {
                            assignedUserSelect.value = data.assigned_to_user_id;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching asset details:', error);
                    });
            }
        });

        // Trigger on page load if asset was previously selected
        if (assetSelect.value) {
            assetSelect.dispatchEvent(new Event('change'));
        }
    </script>
</body>
</html>
