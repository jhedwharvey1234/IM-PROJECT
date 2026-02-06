<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Peripheral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Create Peripheral']) ?>

    <div class="main-content">
        <h1>Create Peripheral</h1>
        
        <a href="<?= site_url('peripherals') ?>" class="btn btn-secondary mb-3">Back to Peripherals</a>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('peripherals/store') ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label for="asset_tag" class="form-label">Asset Tag *</label>
                <input type="text" class="form-control" id="asset_tag" name="asset_tag" value="<?= old('asset_tag') ?>" required>
            </div>

            <div class="col-md-6">
                <label for="peripheral_type_id" class="form-label">Peripheral Type *</label>
                <select class="form-control" id="peripheral_type_id" name="peripheral_type_id" required>
                    <option value="">-- Select Peripheral Type --</option>
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
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" class="form-control" id="serial_number" name="serial_number" value="<?= old('serial_number') ?>">
            </div>

            <div class="col-md-6">
                <label for="department_id" class="form-label">Department *</label>
                <select class="form-control" id="department_id" name="department_id" required>
                    <option value="">-- Select Department --</option>
                    <?php foreach ($departments as $id => $department_name): ?>
                        <option value="<?= $id ?>" <?= old('department_id') == $id ? 'selected' : '' ?>><?= $department_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="location_id" class="form-label">Location *</label>
                <select class="form-control" id="location_id" name="location_id" required>
                    <option value="">-- Select Location --</option>
                    <?php foreach ($locations as $id => $name): ?>
                        <option value="<?= $id ?>" <?= old('location_id') == $id ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="assigned_to_user_id" class="form-label">Assigned to User</label>
                <select class="form-control" id="assigned_to_user_id" name="assigned_to_user_id">
                    <option value="">-- Select User --</option>
                    <?php foreach ($assignable_users as $id => $full_name): ?>
                        <option value="<?= $id ?>" <?= old('assigned_to_user_id') == $id ? 'selected' : '' ?>><?= $full_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="workstation_id" class="form-label">Workstation ID</label>
                <select class="form-control" id="workstation_id" name="workstation_id">
                    <option value="">-- Select Workstation --</option>
                    <?php foreach ($workstations as $id => $code): ?>
                        <option value="<?= $id ?>" <?= old('workstation_id') == $id ? 'selected' : '' ?>><?= $code ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="status" class="form-label">Status *</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="available" <?= old('status') === 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="in_use" <?= old('status') === 'in_use' ? 'selected' : '' ?>>In Use</option>
                    <option value="standby" <?= old('status') === 'standby' ? 'selected' : '' ?>>Standby</option>
                    <option value="under_repair" <?= old('status') === 'under_repair' ? 'selected' : '' ?>>Under Repair</option>
                    <option value="retired" <?= old('status') === 'retired' ? 'selected' : '' ?>>Retired</option>
                    <option value="lost" <?= old('status') === 'lost' ? 'selected' : '' ?>>Lost</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="condition_status" class="form-label">Condition *</label>
                <select class="form-control" id="condition_status" name="condition_status" required>
                    <option value="new" <?= old('condition_status') === 'new' ? 'selected' : '' ?>>New</option>
                    <option value="good" <?= old('condition_status') === 'good' ? 'selected' : '' ?>>Good</option>
                    <option value="fair" <?= old('condition_status') === 'fair' ? 'selected' : '' ?>>Fair</option>
                    <option value="damaged" <?= old('condition_status') === 'damaged' ? 'selected' : '' ?>>Damaged</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="criticality" class="form-label">Criticality *</label>
                <select class="form-control" id="criticality" name="criticality" required>
                    <option value="low" <?= old('criticality') === 'low' ? 'selected' : '' ?>>Low</option>
                    <option value="medium" <?= old('criticality') === 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="high" <?= old('criticality') === 'high' ? 'selected' : '' ?>>High</option>
                </select>
            </div>

            <div class="col-md-6">
                <label for="vendor" class="form-label">Vendor</label>
                <input type="text" class="form-control" id="vendor" name="vendor" value="<?= old('vendor') ?>">
            </div>

            <div class="col-md-6">
                <label for="purchase_date" class="form-label">Purchase Date</label>
                <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?= old('purchase_date') ?>">
            </div>

            <div class="col-md-6">
                <label for="warranty_expiry" class="form-label">Warranty Expiry</label>
                <input type="date" class="form-control" id="warranty_expiry" name="warranty_expiry" value="<?= old('warranty_expiry') ?>">
            </div>

            <div class="col-md-6">
                <label for="last_maintenance_date" class="form-label">Last Maintenance Date</label>
                <input type="date" class="form-control" id="last_maintenance_date" name="last_maintenance_date" value="<?= old('last_maintenance_date') ?>">
            </div>

            <div class="col-md-6">
                <label for="next_maintenance_due" class="form-label">Next Maintenance Due</label>
                <input type="date" class="form-control" id="next_maintenance_due" name="next_maintenance_due" value="<?= old('next_maintenance_due') ?>">
            </div>

            <div class="col-12">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"><?= old('notes') ?></textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Create Peripheral</button>
                <a href="<?= site_url('peripherals') ?>" class="btn btn-secondary">Cancel</a>
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
            workstationSelect.innerHTML = '<option value="">-- Select Workstation --</option>';
            
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
    </script>
</body>
</html>
