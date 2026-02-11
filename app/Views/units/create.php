<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Unit</title>
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
    <?= view('partials/header', ['title' => 'Create Unit']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('units') ?>" title="Go to Units">Units</a>
            <span class="separator">›</span>
            <span class="current">Create Unit</span>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <h5 class="mb-3"><i class="bi bi-exclamation-circle"></i> Validation Errors</h5>
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('units/store') ?>" method="post" class="row g-3">
            <?= csrf_field() ?>

            <div class="section-card">
                <h5><i class="bi bi-collection"></i> Unit Details</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="unit_name" class="form-label">Unit Name *</label>
                        <input type="text" class="form-control" id="unit_name" name="unit_name" value="<?= old('unit_name') ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="unit_type" class="form-label">Unit Type *</label>
                        <select class="form-select" id="unit_type" name="unit_type" required>
                            <option value="">-- Select Type --</option>
                            <option value="asset" <?= old('unit_type') === 'asset' ? 'selected' : '' ?>>Asset</option>
                            <option value="peripheral" <?= old('unit_type') === 'peripheral' ? 'selected' : '' ?>>Peripheral</option>
                            <option value="both" <?= old('unit_type') === 'both' ? 'selected' : '' ?>>Both</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="section-card" id="asset_section" style="display: none;">
                <h5><i class="bi bi-laptop"></i> Assets</h5>
                <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="asset_mode" id="asset_mode_existing" value="existing" checked>
                        <label class="form-check-label" for="asset_mode_existing">Link Existing</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="asset_mode" id="asset_mode_new" value="new">
                        <label class="form-check-label" for="asset_mode_new">Create New</label>
                    </div>
                </div>

                <div id="asset_existing_container">
                    <div id="assets_existing_list" class="mb-2">
                        <!-- Asset selection rows will be added here dynamically -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addExistingAssetBtn">
                        <i class="bi bi-plus-circle"></i> Add Asset
                    </button>
                    <small class="text-muted d-block mt-2">Click \"Add Asset\" to link multiple existing assets</small>
                </div>

                <div class="row g-3" id="asset_new_container" style="display: none;">
                    <div class="col-12">
                        <p class="text-info"><i class="bi bi-info-circle"></i> Note: Only one new asset can be created at a time. To add multiple assets, create the unit first, then edit it to add more assets.</p>
                    </div>
                        <div class="col-md-6">
                            <label for="asset_asset_tag" class="form-label">Asset Tag</label>
                            <input type="text" class="form-control" id="asset_asset_tag" name="asset_asset_tag" value="<?= old('asset_asset_tag') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="asset_serial_number" name="asset_serial_number" value="<?= old('asset_serial_number') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_model" class="form-label">Model</label>
                            <input type="text" class="form-control" id="asset_model" name="asset_model" value="<?= old('asset_model') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_model_number" class="form-label">Model Number</label>
                            <input type="text" class="form-control" id="asset_model_number" name="asset_model_number" value="<?= old('asset_model_number') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_manufacturer" class="form-label">Manufacturer</label>
                            <input type="text" class="form-control" id="asset_manufacturer" name="asset_manufacturer" value="<?= old('asset_manufacturer') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_category" class="form-label">Category</label>
                            <input type="text" class="form-control" id="asset_category" name="asset_category" value="<?= old('asset_category') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_qty" class="form-label">Qty</label>
                            <input type="number" class="form-control" id="asset_qty" name="asset_qty" min="0" value="<?= old('asset_qty') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_box_number" class="form-label">Box Number</label>
                            <input type="text" class="form-control" id="asset_box_number" name="asset_box_number" value="<?= old('asset_box_number') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_status" class="form-label">Status *</label>
                            <select class="form-select" id="asset_status" name="asset_status">
                                <option value="pending" <?= old('asset_status', 'pending') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="ready to deploy" <?= old('asset_status') === 'ready to deploy' ? 'selected' : '' ?>>Ready to Deploy</option>
                                <option value="archived" <?= old('asset_status') === 'archived' ? 'selected' : '' ?>>Archived</option>
                                <option value="broken - not fixable" <?= old('asset_status') === 'broken - not fixable' ? 'selected' : '' ?>>Broken - Not Fixable</option>
                                <option value="lost/stolen" <?= old('asset_status') === 'lost/stolen' ? 'selected' : '' ?>>Lost/Stolen</option>
                                <option value="out for diagnostics" <?= old('asset_status') === 'out for diagnostics' ? 'selected' : '' ?>>Out for Diagnostics</option>
                                <option value="out for repair" <?= old('asset_status') === 'out for repair' ? 'selected' : '' ?>>Out for Repair</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="asset_purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control" id="asset_purchase_date" name="asset_purchase_date" value="<?= old('asset_purchase_date') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_purchase_cost" class="form-label">Purchase Cost</label>
                            <input type="number" step="0.01" class="form-control" id="asset_purchase_cost" name="asset_purchase_cost" value="<?= old('asset_purchase_cost') ?>" placeholder="0.00">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_order_number" class="form-label">Order Number</label>
                            <input type="text" class="form-control" id="asset_order_number" name="asset_order_number" value="<?= old('asset_order_number') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_supplier" class="form-label">Supplier</label>
                            <input type="text" class="form-control" id="asset_supplier" name="asset_supplier" value="<?= old('asset_supplier') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="asset_department_id" class="form-label">Department</label>
                            <select class="form-select" id="asset_department_id" name="asset_department_id">
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $id => $name): ?>
                                    <option value="<?= $id ?>" <?= old('asset_department_id') == $id ? 'selected' : '' ?>><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="asset_location_id" class="form-label">Location</label>
                            <select class="form-select" id="asset_location_id" name="asset_location_id">
                                <option value="">Select Location</option>
                                <?php foreach ($locations as $id => $name): ?>
                                    <option value="<?= $id ?>" <?= old('asset_location_id') == $id ? 'selected' : '' ?>><?= $name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="asset_workstation_id" class="form-label">Workstation</label>
                            <select class="form-select" id="asset_workstation_id" name="asset_workstation_id">
                                <option value="">Select Workstation</option>
                                <?php foreach ($workstations as $id => $code): ?>
                                    <option value="<?= $id ?>" <?= old('asset_workstation_id') == $id ? 'selected' : '' ?>><?= $code ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="asset_assigned_to_user_id" class="form-label">Assigned To</label>
                            <select class="form-select" id="asset_assigned_to_user_id" name="asset_assigned_to_user_id">
                                <option value="">Select User</option>
                                <?php foreach ($assignable_users as $id => $full_name): ?>
                                    <option value="<?= $id ?>" <?= old('asset_assigned_to_user_id') == $id ? 'selected' : '' ?>><?= $full_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="asset_requestable" name="asset_requestable" value="1" <?= old('asset_requestable') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="asset_requestable">Requestable</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="asset_byod" name="asset_byod" value="1" <?= old('asset_byod') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="asset_byod">BYOD (Bring Your Own Device)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="asset_description" class="form-label">Description</label>
                            <textarea class="form-control" id="asset_description" name="asset_description" rows="2"><?= old('asset_description') ?></textarea>
                        </div>
                    </div>
            </div>

            <div class="section-card" id="peripheral_section" style="display: none;">
                <h5><i class="bi bi-cpu"></i> Peripherals</h5>
                <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="peripheral_mode" id="peripheral_mode_existing" value="existing" checked>
                        <label class="form-check-label" for="peripheral_mode_existing">Link Existing</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="peripheral_mode" id="peripheral_mode_new" value="new">
                        <label class="form-check-label" for="peripheral_mode_new">Create New</label>
                    </div>
                </div>

                <div id="peripheral_existing_container">
                    <div id="peripherals_existing_list" class="mb-2">
                        <!-- Peripheral selection rows will be added here dynamically -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addExistingPeripheralBtn">
                        <i class="bi bi-plus-circle"></i> Add Peripheral
                    </button>
                    <small class="text-muted d-block mt-2">Click "Add Peripheral" to link multiple existing peripherals</small>
                </div>

                <div class="row g-3" id="peripheral_new_container" style="display: none;">
                    <div class="col-12">
                        <p class="text-info"><i class="bi bi-info-circle"></i> Note: Only one new peripheral can be created at a time. To add multiple peripherals, create the unit first, then edit it to add more peripherals.</p>
                    </div>
                        <div class="col-md-6">
                            <label for="peripheral_type_id" class="form-label">Peripheral Type *</label>
                            <select class="form-select" id="peripheral_type_id" name="peripheral_type_id">
                                <option value="">-- Select Peripheral Type --</option>
                                <?php foreach ($peripheral_types as $id => $type_name): ?>
                                    <option value="<?= $id ?>" <?= old('peripheral_type_id') == $id ? 'selected' : '' ?>><?= esc($type_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="peripheral_brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="peripheral_brand" name="peripheral_brand" value="<?= old('peripheral_brand') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="peripheral_model" class="form-label">Model</label>
                            <input type="text" class="form-control" id="peripheral_model" name="peripheral_model" value="<?= old('peripheral_model') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="peripheral_serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="peripheral_serial_number" name="peripheral_serial_number" value="<?= old('peripheral_serial_number') ?>">
                        </div>
                        <div class="col-md-6" id="peripheral_asset_link_container">
                            <label for="peripheral_asset_id" class="form-label">Linked Asset <span id="asset_link_required">*</span></label>
                            <select class="form-select" id="peripheral_asset_id" name="peripheral_asset_id">
                                <option value="">-- Select Asset --</option>
                                <?php foreach ($assets as $id => $asset): ?>
                                    <option value="<?= $id ?>"
                                        data-department-id="<?= esc($asset['department_id'] ?? '') ?>"
                                        data-location-id="<?= esc($asset['location_id'] ?? '') ?>"
                                        data-workstation-id="<?= esc($asset['workstation_id'] ?? '') ?>"
                                        data-assigned-to-user-id="<?= esc($asset['assigned_to_user_id'] ?? '') ?>"
                                        <?= old('peripheral_asset_id') == $id ? 'selected' : '' ?>
                                    ><?= esc($asset['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted" id="asset_link_note"></small>
                        </div>
                        <div class="col-md-6">
                            <label for="peripheral_department_id" class="form-label">Department *</label>
                            <select class="form-select" id="peripheral_department_id" name="peripheral_department_id">
                                <option value="">-- Select Department --</option>
                                <?php foreach ($departments as $id => $department_name): ?>
                                    <option value="<?= $id ?>" <?= old('peripheral_department_id') == $id ? 'selected' : '' ?>><?= esc($department_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="peripheral_location_id" class="form-label">Location *</label>
                            <select class="form-select" id="peripheral_location_id" name="peripheral_location_id">
                                <option value="">-- Select Location --</option>
                                <?php foreach ($locations as $id => $name): ?>
                                    <option value="<?= $id ?>" <?= old('peripheral_location_id') == $id ? 'selected' : '' ?>><?= esc($name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="peripheral_assigned_to_user_id" class="form-label">Assigned to User</label>
                            <select class="form-select" id="peripheral_assigned_to_user_id" name="peripheral_assigned_to_user_id">
                                <option value="">-- Select User --</option>
                                <?php foreach ($assignable_users as $id => $full_name): ?>
                                    <option value="<?= $id ?>" <?= old('peripheral_assigned_to_user_id') == $id ? 'selected' : '' ?>><?= esc($full_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="peripheral_workstation_id" class="form-label">Workstation</label>
                            <select class="form-select" id="peripheral_workstation_id" name="peripheral_workstation_id">
                                <option value="">-- Select Workstation --</option>
                                <?php foreach ($workstations as $id => $code): ?>
                                    <option value="<?= $id ?>" <?= old('peripheral_workstation_id') == $id ? 'selected' : '' ?>><?= esc($code) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="peripheral_status" class="form-label">Status *</label>
                            <select class="form-select" id="peripheral_status" name="peripheral_status">
                                <option value="available" <?= old('peripheral_status') === 'available' ? 'selected' : '' ?>>Available</option>
                                <option value="in_use" <?= old('peripheral_status') === 'in_use' ? 'selected' : '' ?>>In Use</option>
                                <option value="standby" <?= old('peripheral_status') === 'standby' ? 'selected' : '' ?>>Standby</option>
                                <option value="under_repair" <?= old('peripheral_status') === 'under_repair' ? 'selected' : '' ?>>Under Repair</option>
                                <option value="retired" <?= old('peripheral_status') === 'retired' ? 'selected' : '' ?>>Retired</option>
                                <option value="lost" <?= old('peripheral_status') === 'lost' ? 'selected' : '' ?>>Lost</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="peripheral_condition_status" class="form-label">Condition *</label>
                            <select class="form-select" id="peripheral_condition_status" name="peripheral_condition_status">
                                <option value="new" <?= old('peripheral_condition_status') === 'new' ? 'selected' : '' ?>>New</option>
                                <option value="good" <?= old('peripheral_condition_status') === 'good' ? 'selected' : '' ?>>Good</option>
                                <option value="fair" <?= old('peripheral_condition_status') === 'fair' ? 'selected' : '' ?>>Fair</option>
                                <option value="damaged" <?= old('peripheral_condition_status') === 'damaged' ? 'selected' : '' ?>>Damaged</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="peripheral_criticality" class="form-label">Criticality *</label>
                            <select class="form-select" id="peripheral_criticality" name="peripheral_criticality">
                                <option value="low" <?= old('peripheral_criticality') === 'low' ? 'selected' : '' ?>>Low</option>
                                <option value="medium" <?= old('peripheral_criticality') === 'medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="high" <?= old('peripheral_criticality') === 'high' ? 'selected' : '' ?>>High</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="peripheral_purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control" id="peripheral_purchase_date" name="peripheral_purchase_date" value="<?= old('peripheral_purchase_date') ?>">
                        </div>
                        <div class="col-12">
                            <label for="peripheral_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="peripheral_notes" name="peripheral_notes" rows="2"><?= old('peripheral_notes') ?></textarea>
                        </div>
                    </div>
            </div>

            <div class="section-card">
                <h5><i class="bi bi-file-text"></i> Notes</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?= old('notes') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Create Unit
                </button>
                <a href="<?= site_url('units') ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const unitTypeSelect = document.getElementById('unit_type');
        const assetSection = document.getElementById('asset_section');
        const peripheralSection = document.getElementById('peripheral_section');
        const assetModeExisting = document.getElementById('asset_mode_existing');
        const assetModeNew = document.getElementById('asset_mode_new');
        const assetExistingContainer = document.getElementById('asset_existing_container');
        const assetNewContainer = document.getElementById('asset_new_container');
        const peripheralModeExisting = document.getElementById('peripheral_mode_existing');
        const peripheralModeNew = document.getElementById('peripheral_mode_new');
        const peripheralExistingContainer = document.getElementById('peripheral_existing_container');
        const peripheralNewContainer = document.getElementById('peripheral_new_container');
        const peripheralAssetLinkContainer = document.getElementById('peripheral_asset_link_container');
        const assetsExistingList = document.getElementById('assets_existing_list');
        const peripheralsExistingList = document.getElementById('peripherals_existing_list');
        const addExistingAssetBtn = document.getElementById('addExistingAssetBtn');
        const addExistingPeripheralBtn = document.getElementById('addExistingPeripheralBtn');
        const assetDepartmentSelect = document.getElementById('asset_department_id');
        const assetLocationSelect = document.getElementById('asset_location_id');
        const assetWorkstationSelect = document.getElementById('asset_workstation_id');
        const assetAssignedSelect = document.getElementById('asset_assigned_to_user_id');
        const peripheralAssetSelect = document.getElementById('peripheral_asset_id');
        const peripheralDepartmentSelect = document.getElementById('peripheral_department_id');
        const peripheralLocationSelect = document.getElementById('peripheral_location_id');
        const peripheralWorkstationSelect = document.getElementById('peripheral_workstation_id');
        const peripheralAssignedSelect = document.getElementById('peripheral_assigned_to_user_id');
        
        // Available assets and peripherals
        const availableAssets = <?= json_encode($assets) ?>;
        const availablePeripherals = <?= json_encode($peripherals) ?>;
        
        let assetCounter = 0;
        let peripheralCounter = 0;
        
        // Function to add asset row
        function addExistingAssetRow(assetId = '') {
            const index = assetCounter++;
            const div = document.createElement('div');
            div.className = 'input-group mb-2 asset-row';
            div.innerHTML = `
                <select class="form-select" name="asset_ids[]" data-index="${index}" required>
                    <option value="">-- Select Asset --</option>
                    ${Object.entries(availableAssets).map(([id, asset]) => 
                        `<option value="${id}" 
                            data-department-id="${asset.department_id || ''}"
                            data-location-id="${asset.location_id || ''}"
                            data-workstation-id="${asset.workstation_id || ''}"
                            data-assigned-to-user-id="${asset.assigned_to_user_id || ''}"
                            ${id == assetId ? 'selected' : ''}>${asset.label}</option>`
                    ).join('')}
                </select>
                <a href="#" class="btn btn-outline-secondary asset-edit-btn" title="Edit" style="display: ${assetId ? 'block' : 'none'}" target="_blank">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <a href="#" class="btn btn-outline-info asset-view-btn" title="View" style="display: ${assetId ? 'block' : 'none'}" target="_blank">
                    <i class="bi bi-eye"></i>
                </a>
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            
            const select = div.querySelector('select');
            const editBtn = div.querySelector('.asset-edit-btn');
            const viewBtn = div.querySelector('.asset-view-btn');
            
            select.addEventListener('change', function() {
                if (this.value) {
                    editBtn.href = '<?= site_url('assets/edit/') ?>' + this.value;
                    viewBtn.href = '<?= site_url('assets/details/') ?>' + this.value;
                    editBtn.style.display = 'block';
                    viewBtn.style.display = 'block';
                } else {
                    editBtn.style.display = 'none';
                    viewBtn.style.display = 'none';
                }
            });
            
            if (assetId) {
                editBtn.href = '<?= site_url('assets/edit/') ?>' + assetId;
                viewBtn.href = '<?= site_url('assets/details/') ?>' + assetId;
            }
            
            assetsExistingList.appendChild(div);
        }
        
        // Function to add peripheral row
        function addExistingPeripheralRow(peripheralId = '') {
            const index = peripheralCounter++;
            const div = document.createElement('div');
            div.className = 'input-group mb-2 peripheral-row';
            div.innerHTML = `
                <select class="form-select" name="peripheral_ids[]" data-index="${index}" required>
                    <option value="">-- Select Peripheral --</option>
                    ${Object.entries(availablePeripherals).map(([id, label]) => 
                        `<option value="${id}" ${id == peripheralId ? 'selected' : ''}>${label}</option>`
                    ).join('')}
                </select>
                <a href="#" class="btn btn-outline-secondary peripheral-edit-btn" title="Edit" style="display: ${peripheralId ? 'block' : 'none'}" target="_blank">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <a href="#" class="btn btn-outline-info peripheral-view-btn" title="View" style="display: ${peripheralId ? 'block' : 'none'}" target="_blank">
                    <i class="bi bi-eye"></i>
                </a>
                <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            
            const select = div.querySelector('select');
            const editBtn = div.querySelector('.peripheral-edit-btn');
            const viewBtn = div.querySelector('.peripheral-view-btn');
            
            select.addEventListener('change', function() {
                if (this.value) {
                    editBtn.href = '<?= site_url('peripherals/edit/') ?>' + this.value;
                    viewBtn.href = '<?= site_url('peripherals/details/') ?>' + this.value;
                    editBtn.style.display = 'block';
                    viewBtn.style.display = 'block';
                } else {
                    editBtn.style.display = 'none';
                    viewBtn.style.display = 'none';
                }
            });
            
            if (peripheralId) {
                editBtn.href = '<?= site_url('peripherals/edit/') ?>' + peripheralId;
                viewBtn.href = '<?= site_url('peripherals/details/') ?>' + peripheralId;
            }
            
            peripheralsExistingList.appendChild(div);
        }

        function toggleUnitTypeFields() {
            const type = unitTypeSelect.value;
            const showAsset = (type === 'asset' || type === 'both');
            const showPeripheral = (type === 'peripheral' || type === 'both');
            assetSection.style.display = showAsset ? 'block' : 'none';
            peripheralSection.style.display = showPeripheral ? 'block' : 'none';
            toggleAssetMode();
            togglePeripheralMode();
        }

        function setRequired(element, required) {
            if (!element) return;
            if (required) {
                element.setAttribute('required', 'required');
            } else {
                element.removeAttribute('required');
            }
        }

        function toggleAssetMode() {
            const isNew = assetModeNew && assetModeNew.checked;
            if (assetExistingContainer) assetExistingContainer.style.display = isNew ? 'none' : 'block';
            if (assetNewContainer) assetNewContainer.style.display = isNew ? 'block' : 'none';
            syncPeripheralFieldsFromAsset();
        }

        function togglePeripheralMode() {
            const isNew = peripheralModeNew && peripheralModeNew.checked;
            if (peripheralExistingContainer) peripheralExistingContainer.style.display = isNew ? 'none' : 'block';
            if (peripheralNewContainer) peripheralNewContainer.style.display = isNew ? 'block' : 'none';
            setRequired(document.getElementById('peripheral_type_id'), isNew);
            setRequired(document.getElementById('peripheral_department_id'), isNew);
            setRequired(document.getElementById('peripheral_location_id'), isNew);
            syncPeripheralAsset();
            syncPeripheralFieldsFromAsset();
        }

        function getSelectedAssetMeta(select) {
            if (!select || !select.options || select.selectedIndex < 0) return null;
            const option = select.options[select.selectedIndex];
            if (!option || !option.value) return null;

            return {
                departmentId: option.dataset.departmentId || '',
                locationId: option.dataset.locationId || '',
                workstationId: option.dataset.workstationId || '',
                assignedToUserId: option.dataset.assignedToUserId || ''
            };
        }

        function getAssetMetaFromInputs() {
            return {
                departmentId: assetDepartmentSelect ? assetDepartmentSelect.value : '',
                locationId: assetLocationSelect ? assetLocationSelect.value : '',
                workstationId: assetWorkstationSelect ? assetWorkstationSelect.value : '',
                assignedToUserId: assetAssignedSelect ? assetAssignedSelect.value : ''
            };
        }

        function applyAssetMetaToPeripheral(meta) {
            if (!meta) {
                return;
            }
            if (peripheralDepartmentSelect) peripheralDepartmentSelect.value = meta.departmentId || '';
            if (peripheralLocationSelect) peripheralLocationSelect.value = meta.locationId || '';
            if (peripheralWorkstationSelect) peripheralWorkstationSelect.value = meta.workstationId || '';
            if (peripheralAssignedSelect) peripheralAssignedSelect.value = meta.assignedToUserId || '';
        }

        function syncPeripheralFieldsFromAsset() {
            const unitType = unitTypeSelect.value;
            const includesAsset = (unitType === 'asset' || unitType === 'both');
            const includesPeripheral = (unitType === 'peripheral' || unitType === 'both');
            const isPeripheralNew = peripheralModeNew && peripheralModeNew.checked;

            if (!includesPeripheral || !isPeripheralNew) {
                return;
            }

            let meta = null;
            if (includesAsset) {
                const isAssetNew = assetModeNew && assetModeNew.checked;
                if (isAssetNew) {
                    meta = getAssetMetaFromInputs();
                } else {
                    // Get from first asset in list
                    const firstAssetSelect = assetsExistingList ? assetsExistingList.querySelector('select') : null;
                    meta = getSelectedAssetMeta(firstAssetSelect);
                }
            } else {
                meta = getSelectedAssetMeta(peripheralAssetSelect);
            }

            if (meta) {
                applyAssetMetaToPeripheral(meta);
            }
        }

        function syncPeripheralAsset() {
            if (!peripheralAssetSelect || !peripheralAssetLinkContainer) return;
            const type = unitTypeSelect.value;
            const assetRequired = document.getElementById('asset_link_required');
            const assetNote = document.getElementById('asset_link_note');
            const includesAsset = (type === 'asset' || type === 'both');
            const isAssetNew = includesAsset && assetModeNew && assetModeNew.checked;
            const firstAssetSelect = assetsExistingList ? assetsExistingList.querySelector('select') : null;
            const isAssetExisting = includesAsset && assetModeExisting && assetModeExisting.checked && firstAssetSelect && firstAssetSelect.value;

            if (isAssetNew) {
                // Creating new asset - peripheral will auto-link to it
                peripheralAssetLinkContainer.style.display = 'block';
                peripheralAssetSelect.disabled = true;
                peripheralAssetSelect.value = '';
                if (assetRequired) assetRequired.style.display = 'none';
                if (assetNote) assetNote.textContent = '(Will automatically link to the newly created asset above)';
                setRequired(peripheralAssetSelect, false);
            } else if (isAssetExisting) {
                // Existing asset selected - pre-fill and hide
                peripheralAssetSelect.value = firstAssetSelect.value;
                peripheralAssetSelect.disabled = true;
                peripheralAssetLinkContainer.style.display = 'block';
                if (assetRequired) assetRequired.style.display = 'none';
                if (assetNote) assetNote.textContent = '(Automatically linked to first selected asset above)';
                setRequired(peripheralAssetSelect, false);
            } else {
                // No asset context - show dropdown as required
                peripheralAssetSelect.disabled = false;
                peripheralAssetLinkContainer.style.display = 'block';
                if (assetRequired) assetRequired.style.display = 'inline';
                if (assetNote) assetNote.textContent = '';
                setRequired(peripheralAssetSelect, true);
            }
        }

        if (assetModeExisting) assetModeExisting.addEventListener('change', toggleAssetMode);
        if (assetModeNew) assetModeNew.addEventListener('change', toggleAssetMode);
        if (peripheralModeExisting) peripheralModeExisting.addEventListener('change', togglePeripheralMode);
        if (peripheralModeNew) peripheralModeNew.addEventListener('change', togglePeripheralMode);
        if (peripheralAssetSelect) peripheralAssetSelect.addEventListener('change', syncPeripheralFieldsFromAsset);
        if (assetDepartmentSelect) assetDepartmentSelect.addEventListener('change', syncPeripheralFieldsFromAsset);
        if (assetLocationSelect) assetLocationSelect.addEventListener('change', syncPeripheralFieldsFromAsset);
        if (assetWorkstationSelect) assetWorkstationSelect.addEventListener('change', syncPeripheralFieldsFromAsset);
        if (assetAssignedSelect) assetAssignedSelect.addEventListener('change', syncPeripheralFieldsFromAsset);
        unitTypeSelect.addEventListener('change', toggleUnitTypeFields);
        
        // Add event listeners for add buttons
        if (addExistingAssetBtn) {
            addExistingAssetBtn.addEventListener('click', () => addExistingAssetRow());
        }
        
        if (addExistingPeripheralBtn) {
            addExistingPeripheralBtn.addEventListener('click', () => addExistingPeripheralRow());
        }
        
        // Initialize with one row each if in existing mode
        if (unitTypeSelect.value === 'asset' || unitTypeSelect.value === 'both') {
            addExistingAssetRow();
        }
        if (unitTypeSelect.value === 'peripheral' || unitTypeSelect.value === 'both') {
            addExistingPeripheralRow();
        }

        toggleUnitTypeFields();
    </script>
</body>
</html>
