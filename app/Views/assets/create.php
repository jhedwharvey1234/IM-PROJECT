<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Asset</title>
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
    <?= view('partials/header', ['title' => 'Create Asset']) ?>

    <div class="main-content">
        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('assets') ?>" title="Go to Assets">Assets</a>
            <span class="separator">›</span>
            <span class="current">Create New Asset</span>
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

        <form action="<?= site_url('assets/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <!-- Basic Information Section -->
            <div class="section-card">
                <h5><i class="bi bi-info-circle"></i> Basic Information</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="asset_tag" class="form-label">Asset Tag</label>
                        <input type="text" class="form-control" id="asset_tag" name="asset_tag" value="<?= old('asset_tag') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" class="form-control" id="serial_number" name="serial_number" value="<?= old('serial_number') ?>">
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
                        <label for="manufacturer" class="form-label">Manufacturer</label>
                        <input type="text" class="form-control" id="manufacturer" name="manufacturer" value="<?= old('manufacturer') ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $id => $name): ?>
                                <option value="<?= $id ?>" <?= old('category') == $id ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="qty" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="qty" name="qty" min="1" value="<?= old('qty', '1') ?>">
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
                    <div class="col-md-6">
                        <label for="barcode" class="form-label">Barcode Image</label>
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
                            <option value="pending" <?= old('status', 'pending') === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="ready to deploy" <?= old('status') === 'ready to deploy' ? 'selected' : '' ?>>Ready to Deploy</option>
                            <option value="archived" <?= old('status') === 'archived' ? 'selected' : '' ?>>Archived</option>
                            <option value="broken - not fixable" <?= old('status') === 'broken - not fixable' ? 'selected' : '' ?>>Broken - Not Fixable</option>
                            <option value="lost/stolen" <?= old('status') === 'lost/stolen' ? 'selected' : '' ?>>Lost/Stolen</option>
                            <option value="out for diagnostics" <?= old('status') === 'out for diagnostics' ? 'selected' : '' ?>>Out for Diagnostics</option>
                            <option value="out for repair" <?= old('status') === 'out for repair' ? 'selected' : '' ?>>Out for Repair</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?= old('purchase_date') ?>">
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
                    <div class="col-md-12">
                        <label for="supplier" class="form-label">Supplier</label>
                        <input type="text" class="form-control" id="supplier" name="supplier" value="<?= old('supplier') ?>">
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
                                <option value="<?= $id ?>" <?= old('department_id') == $id ? 'selected' : '' ?>><?= $name ?></option>
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

            <!-- Flags Section -->
            <div class="section-card">
                <h5><i class="bi bi-flag"></i> Flags & Options</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="requestable" name="requestable" value="1" <?= old('requestable') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="requestable">
                                <strong>Requestable</strong>
                                <small class="d-block text-muted">Allow users to request this asset</small>
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

            <!-- Description Section -->
            <div class="section-card">
                <h5><i class="bi bi-file-text"></i> Notes & Description</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter additional details about this asset..."><?= old('description') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Peripherals Section -->
            <div class="section-card">
                <h5><i class="bi bi-cpu"></i> Peripherals (Optional)</h5>
                <div class="table-responsive">
                    <table class="table table-sm mb-0" id="peripherals_table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 12%;">Type</th>
                                <th style="width: 10%;">Brand</th>
                                <th style="width: 10%;">Model</th>
                                <th style="width: 10%;">Serial #</th>
                                <th style="width: 10%;">Department</th>
                                <th style="width: 10%;">Location</th>
                                <th style="width: 10%;">Workstation</th>
                                <th style="width: 12%;">Assigned To</th>
                                <th style="width: 8%;">Status</th>
                                <th style="width: 5%;"></th>
                            </tr>
                        </thead>
                        <tbody id="peripherals_body">
                            <tr class="peripheral-row">
                                <td>
                                    <select class="form-select form-select-sm" name="peripheral_type_id[]">
                                        <option value="">Select</option>
                                        <?php foreach ($peripheral_types as $id => $name): ?>
                                            <option value="<?= $id ?>"><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control form-control-sm" name="peripheral_brand[]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="peripheral_model[]"></td>
                                <td><input type="text" class="form-control form-control-sm" name="peripheral_serial_number[]"></td>
                                <td>
                                    <select class="form-select form-select-sm" name="peripheral_department_id[]">
                                        <option value="">Select</option>
                                        <?php foreach ($departments as $id => $name): ?>
                                            <option value="<?= $id ?>"><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" name="peripheral_location_id[]">
                                        <option value="">Select</option>
                                        <?php foreach ($locations as $id => $name): ?>
                                            <option value="<?= $id ?>"><?= $name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" name="peripheral_workstation_id[]">
                                        <option value="">Select</option>
                                        <?php foreach ($workstations as $id => $code): ?>
                                            <option value="<?= $id ?>"><?= $code ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" name="peripheral_assigned_to_user_id[]">
                                        <option value="">Select</option>
                                        <?php foreach ($assignable_users as $id => $full_name): ?>
                                            <option value="<?= $id ?>"><?= $full_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm" name="peripheral_status[]">
                                        <option value="available">Available</option>
                                        <option value="in_use">In Use</option>
                                        <option value="standby">Standby</option>
                                    </select>
                                </td>
                                <td><button type="button" class="btn btn-sm btn-outline-danger remove-peripheral" style="display:none;" title="Remove peripheral"><i class="bi bi-trash"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-sm btn-success mt-3" id="add_peripheral">
                    <i class="bi bi-plus-circle"></i> Add Peripheral
                </button>
            </div>

            <!-- Form Actions Section -->
            <div class="row g-2 mt-5">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle"></i> Create Asset
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
    <script>
        const assetDepartment = document.getElementById('department_id');
        const assetLocation = document.getElementById('location_id');
        const assetWorkstation = document.getElementById('workstation_id');
        const assetAssignedTo = document.getElementById('assigned_to_user_id');

        const syncPeripheralRow = (row) => {
            const map = [
                { assetEl: assetDepartment, selector: 'select[name="peripheral_department_id[]"]' },
                { assetEl: assetLocation, selector: 'select[name="peripheral_location_id[]"]' },
                { assetEl: assetWorkstation, selector: 'select[name="peripheral_workstation_id[]"]' },
                { assetEl: assetAssignedTo, selector: 'select[name="peripheral_assigned_to_user_id[]"]' },
            ];
            map.forEach(({ assetEl, selector }) => {
                if (!assetEl) return;
                const target = row.querySelector(selector);
                if (!target) return;
                target.value = assetEl.value || '';
            });
        };

        const syncAllPeripheralRows = () => {
            document.querySelectorAll('.peripheral-row').forEach((row) => syncPeripheralRow(row));
        };

        if (assetDepartment) assetDepartment.addEventListener('change', syncAllPeripheralRows);
        if (assetLocation) assetLocation.addEventListener('change', syncAllPeripheralRows);
        if (assetWorkstation) assetWorkstation.addEventListener('change', syncAllPeripheralRows);
        if (assetAssignedTo) assetAssignedTo.addEventListener('change', syncAllPeripheralRows);

        document.getElementById('add_peripheral').addEventListener('click', function() {
            const tbody = document.getElementById('peripherals_body');
            const firstRow = tbody.querySelector('.peripheral-row');
            const newRow = firstRow.cloneNode(true);
            
            // Clear input values
            newRow.querySelectorAll('input, select').forEach(input => {
                input.value = '';
            });
            
            // Show remove button
            newRow.querySelector('.remove-peripheral').style.display = 'block';
            newRow.querySelector('.remove-peripheral').addEventListener('click', function(e) {
                e.preventDefault();
                newRow.remove();
                updateRemoveButtons();
            });

            syncPeripheralRow(newRow);
            
            tbody.appendChild(newRow);
            updateRemoveButtons();
        });
        
        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.peripheral-row');
            rows.forEach((row) => {
                const removeBtn = row.querySelector('.remove-peripheral');
                removeBtn.style.display = rows.length > 1 ? 'block' : 'none';
            });
        }
        
        // Initialize remove buttons for first row
        updateRemoveButtons();
        syncAllPeripheralRows();
        
        // Add remove button listener to initial row
        const initialRemoveBtn = document.querySelector('.peripheral-row .remove-peripheral');
        if (initialRemoveBtn) {
            initialRemoveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (document.querySelectorAll('.peripheral-row').length > 1) {
                    this.closest('tr').remove();
                    updateRemoveButtons();
                }
            });
        }
    </script>
</body>
</html>
