<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Unit</title>
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
    <?= view('partials/header', ['title' => 'Edit Unit']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('units') ?>" title="Go to Units">Units</a>
            <span class="separator">›</span>
            <span class="current">Edit Unit #<?= $unit['id'] ?></span>
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

        <form action="<?= site_url('units/update/' . $unit['id']) ?>" method="post" class="row g-3">
            <?= csrf_field() ?>

            <div class="section-card">
                <h5><i class="bi bi-collection"></i> Unit Details</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="unit_name" class="form-label">Unit Name *</label>
                        <input type="text" class="form-control" id="unit_name" name="unit_name" value="<?= old('unit_name', $unit['unit_name']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label for="unit_type" class="form-label">Unit Type *</label>
                        <select class="form-select" id="unit_type" name="unit_type" required>
                            <option value="">-- Select Type --</option>
                            <option value="asset" <?= old('unit_type', $unit['unit_type']) === 'asset' ? 'selected' : '' ?>>Asset</option>
                            <option value="peripheral" <?= old('unit_type', $unit['unit_type']) === 'peripheral' ? 'selected' : '' ?>>Peripheral</option>
                            <option value="both" <?= old('unit_type', $unit['unit_type']) === 'both' ? 'selected' : '' ?>>Both</option>
                        </select>
                    </div>

                    <div class="col-12" id="asset_select_container" style="display: none;">
                        <label class="form-label">Assets *</label>
                        <div id="assets_list" class="mb-2">
                            <!-- Asset items will be added here dynamically -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addAssetBtn">
                            <i class="bi bi-plus-circle"></i> Add Asset
                        </button>
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle"></i> Edit/View buttons only appear for assets already in this unit. New assets can only be viewed after saving.
                        </small>
                    </div>

                    <div class="col-12" id="peripheral_select_container" style="display: none;">
                        <label class="form-label">Peripherals *</label>
                        <div id="peripherals_list" class="mb-2">
                            <!-- Peripheral items will be added here dynamically -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addPeripheralBtn">
                            <i class="bi bi-plus-circle"></i> Add Peripheral
                        </button>
                        <small class="text-muted d-block mt-2">
                            <i class="bi bi-info-circle"></i> Edit/View buttons only appear for peripherals already in this unit. New peripherals can only be viewed after saving.
                        </small>
                    </div>
                </div>
            </div>

            <div class="section-card">
                <h5><i class="bi bi-file-text"></i> Notes</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?= old('notes', $unit['notes']) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Unit
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
        const assetContainer = document.getElementById('asset_select_container');
        const peripheralContainer = document.getElementById('peripheral_select_container');
        const assetsList = document.getElementById('assets_list');
        const peripheralsList = document.getElementById('peripherals_list');
        const addAssetBtn = document.getElementById('addAssetBtn');
        const addPeripheralBtn = document.getElementById('addPeripheralBtn');

        // Available assets and peripherals
        const availableAssets = <?= json_encode($assets) ?>;
        const availablePeripherals = <?= json_encode($peripherals) ?>;
        
        // Current unit assets and peripherals (to be loaded from server)
        const currentAssetIds = <?= json_encode(isset($unit_assets) ? array_column($unit_assets, 'asset_id') : ($unit['asset_id'] ? [$unit['asset_id']] : [])) ?>;
        const currentPeripheralIds = <?= json_encode(isset($unit_peripherals) ? array_column($unit_peripherals, 'peripheral_id') : ($unit['peripheral_id'] ? [$unit['peripheral_id']] : [])) ?>;
        
        // Track original items (these can be edited)
        const originalAssetIds = [...currentAssetIds];
        const originalPeripheralIds = [...currentPeripheralIds];
        
        let assetCounter = 0;
        let peripheralCounter = 0;

        function setRequired(element, required) {
            if (!element) return;
            if (required) {
                element.setAttribute('required', 'required');
            } else {
                element.removeAttribute('required');
            }
        }

        function addAssetRow(assetId = '') {
            const index = assetCounter++;
            const isOriginal = assetId && originalAssetIds.includes(parseInt(assetId));
            const div = document.createElement('div');
            div.className = 'input-group mb-2 asset-row';
            div.innerHTML = `
                <select class="form-select" name="asset_ids[]" data-index="${index}" data-original="${isOriginal}" required>
                    <option value="">-- Select Asset --</option>
                    ${Object.entries(availableAssets).map(([id, asset]) => 
                        `<option value="${id}" ${id == assetId ? 'selected' : ''}>${asset.label}</option>`
                    ).join('')}
                </select>
                <a href="#" class="btn btn-outline-secondary asset-edit-btn" title="Edit (already in unit)" style="display: ${isOriginal && assetId ? 'block' : 'none'}" target="_blank">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <a href="#" class="btn btn-outline-info asset-view-btn" title="View (already in unit)" style="display: ${isOriginal && assetId ? 'block' : 'none'}" target="_blank">
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
                const selectedId = parseInt(this.value);
                const isOriginalItem = selectedId && originalAssetIds.includes(selectedId);
                
                if (this.value && isOriginalItem) {
                    // Only show edit/view buttons for assets already in the unit
                    editBtn.href = '<?= site_url('assets/edit/') ?>' + this.value;
                    viewBtn.href = '<?= site_url('assets/details/') ?>' + this.value;
                    editBtn.style.display = 'block';
                    viewBtn.style.display = 'block';
                    editBtn.title = 'Edit (already in unit)';
                    viewBtn.title = 'View (already in unit)';
                } else {
                    editBtn.style.display = 'none';
                    viewBtn.style.display = 'none';
                }
            });
            
            if (assetId && isOriginal) {
                editBtn.href = '<?= site_url('assets/edit/') ?>' + assetId;
                viewBtn.href = '<?= site_url('assets/details/') ?>' + assetId;
            }
            
            assetsList.appendChild(div);
        }

        function addPeripheralRow(peripheralId = '') {
            const index = peripheralCounter++;
            const isOriginal = peripheralId && originalPeripheralIds.includes(parseInt(peripheralId));
            const div = document.createElement('div');
            div.className = 'input-group mb-2 peripheral-row';
            div.innerHTML = `
                <select class="form-select" name="peripheral_ids[]" data-index="${index}" data-original="${isOriginal}" required>
                    <option value="">-- Select Peripheral --</option>
                    ${Object.entries(availablePeripherals).map(([id, label]) => 
                        `<option value="${id}" ${id == peripheralId ? 'selected' : ''}>${label}</option>`
                    ).join('')}
                </select>
                <a href="#" class="btn btn-outline-secondary peripheral-edit-btn" title="Edit (already in unit)" style="display: ${isOriginal && peripheralId ? 'block' : 'none'}" target="_blank">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <a href="#" class="btn btn-outline-info peripheral-view-btn" title="View (already in unit)" style="display: ${isOriginal && peripheralId ? 'block' : 'none'}" target="_blank">
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
                const selectedId = parseInt(this.value);
                const isOriginalItem = selectedId && originalPeripheralIds.includes(selectedId);
                
                if (this.value && isOriginalItem) {
                    // Only show edit/view buttons for peripherals already in the unit
                    editBtn.href = '<?= site_url('peripherals/edit/') ?>' + this.value;
                    viewBtn.href = '<?= site_url('peripherals/details/') ?>' + this.value;
                    editBtn.style.display = 'block';
                    viewBtn.style.display = 'block';
                    editBtn.title = 'Edit (already in unit)';
                    viewBtn.title = 'View (already in unit)';
                } else {
                    editBtn.style.display = 'none';
                    viewBtn.style.display = 'none';
                }
            });
            
            if (peripheralId && isOriginal) {
                editBtn.href = '<?= site_url('peripherals/edit/') ?>' + peripheralId;
                viewBtn.href = '<?= site_url('peripherals/details/') ?>' + peripheralId;
            }
            
            peripheralsList.appendChild(div);
        }

        function toggleUnitTypeFields() {
            const type = unitTypeSelect.value;
            assetContainer.style.display = (type === 'asset' || type === 'both') ? 'block' : 'none';
            peripheralContainer.style.display = (type === 'peripheral' || type === 'both') ? 'block' : 'none';
        }

        addAssetBtn.addEventListener('click', () => addAssetRow());
        addPeripheralBtn.addEventListener('click', () => addPeripheralRow());
        unitTypeSelect.addEventListener('change', toggleUnitTypeFields);
        
        // Initialize on page load - load existing assets and peripherals
        currentAssetIds.forEach(id => addAssetRow(id));
        if (currentAssetIds.length === 0 && (unitTypeSelect.value === 'asset' || unitTypeSelect.value === 'both')) {
            addAssetRow(); // Add at least one empty row
        }
        
        currentPeripheralIds.forEach(id => addPeripheralRow(id));
        if (currentPeripheralIds.length === 0 && (unitTypeSelect.value === 'peripheral' || unitTypeSelect.value === 'both')) {
            addPeripheralRow(); // Add at least one empty row
        }
        
        toggleUnitTypeFields();
    </script>
</body>
</html>
