<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .status-badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; text-transform: uppercase; font-weight: bold; }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-in_transit { background-color: #17a2b8; color: #fff; }
        .status-completed { background-color: #28a745; color: #fff; }
        .status-rejected { background-color: #dc3545; color: #fff; }
        .status-available { background-color: #28a745; color: white; }
        .status-in_use { background-color: #007bff; color: white; }
        .status-standby { background-color: #ffc107; color: #000; }
        .status-under_repair { background-color: #fd7e14; color: white; }
        .status-retired { background-color: #6c757d; color: white; }
        .status-lost { background-color: #dc3545; color: white; }
        .detail-card { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .detail-row { margin-bottom: 15px; }
        .detail-label { font-weight: 600; color: #333; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Asset Details']) ?>

    <div class="main-content">
        <h1>Asset #<?= $asset['id'] ?> Details</h1>
        
        <div class="mb-3">
            <a href="<?= site_url('assets') ?>" class="btn btn-secondary">Back to Assets</a>
            <a href="<?= site_url('assets/edit/' . $asset['id']) ?>" class="btn btn-warning">Edit Asset</a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Asset Details Card -->
        <div class="detail-card">
            <h3 class="mb-4">Asset Information</h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Tracking Number:</span>
                        <span><?= $asset['tracking_number'] ?? '-' ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Box Number:</span>
                        <span><?= $asset['box_number'] ?? '-' ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Barcode:</span>
                        <span>
                            <?php if (!empty($asset['barcode'])): ?>
                                <img src="<?= base_url('uploads/barcodes/' . $asset['barcode']) ?>" alt="barcode" style="max-height:120px; max-width:300px; display:block;">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Sender:</span>
                        <span><?= $asset['sender'] ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Recipient:</span>
                        <span><?= $asset['recipient'] ?></span>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="status-badge status-<?= $asset['status'] ?>">
                            <?= ucfirst($asset['status']) ?>
                        </span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date Sent:</span>
                        <span><?= date('M d, Y H:i', strtotime($asset['date_sent'])) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date In Transit:</span>
                        <span><?= !empty($asset['date_in_transit']) ? date('M d, Y H:i', strtotime($asset['date_in_transit'])) : '-' ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date Received:</span>
                        <span><?= !empty($asset['date_received']) ? date('M d, Y H:i', strtotime($asset['date_received'])) : '-' ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date Rejected:</span>
                        <span><?= !empty($asset['date_rejected']) ? date('M d, Y H:i', strtotime($asset['date_rejected'])) : '-' ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Created At:</span>
                        <span><?= date('M d, Y H:i', strtotime($asset['created_at'])) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Updated At:</span>
                        <span><?= date('M d, Y H:i', strtotime($asset['updated_at'])) ?></span>
                    </div>
                </div>
            </div>

            <div class="detail-row mt-3">
                <span class="detail-label">Address:</span>
                <p><?= nl2br($asset['address']) ?></p>
            </div>

            <?php if ($asset['description']): ?>
                <div class="detail-row">
                    <span class="detail-label">Description:</span>
                    <p><?= nl2br($asset['description']) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Add Peripheral Form -->
        <div class="detail-card">
            <h3 class="mb-4">Add New Peripheral to this Asset</h3>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('assets/peripheral/store') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="asset_id" value="<?= $asset['id'] ?>">
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="asset_tag" class="form-label">Asset Tag <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="asset_tag" name="asset_tag" required value="<?= old('asset_tag') ?>">
                    </div>

                    <div class="col-md-4">
                        <label for="peripheral_type_id" class="form-label">Peripheral Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="peripheral_type_id" name="peripheral_type_id" required>
                            <option value="">Select Type</option>
                            <?php foreach ($peripheral_types as $id => $name): ?>
                                <option value="<?= $id ?>" <?= old('peripheral_type_id') == $id ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" value="<?= old('brand') ?>">
                    </div>

                    <div class="col-md-4">
                        <label for="model" class="form-label">Model</label>
                        <input type="text" class="form-control" id="model" name="model" value="<?= old('model') ?>">
                    </div>

                    <div class="col-md-4">
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" class="form-control" id="serial_number" name="serial_number" value="<?= old('serial_number') ?>">
                    </div>

                    <div class="col-md-4">
                        <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $id => $name): ?>
                                <option value="<?= $id ?>" <?= old('department_id') == $id ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="location_id" class="form-label">Location <span class="text-danger">*</span></label>
                        <select class="form-select" id="location_id" name="location_id" required>
                            <option value="">Select Location</option>
                            <?php foreach ($locations as $id => $name): ?>
                                <option value="<?= $id ?>" <?= old('location_id') == $id ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="workstation_id" class="form-label">Workstation</label>
                        <select class="form-select" id="workstation_id" name="workstation_id">
                            <option value="">Select Workstation</option>
                            <?php foreach ($workstations as $id => $code): ?>
                                <option value="<?= $id ?>" <?= old('workstation_id') == $id ? 'selected' : '' ?>><?= $code ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="assigned_to_user_id" class="form-label">Assigned To</label>
                        <select class="form-select" id="assigned_to_user_id" name="assigned_to_user_id">
                            <option value="">Select User</option>
                            <?php foreach ($assignable_users as $id => $name): ?>
                                <option value="<?= $id ?>" <?= old('assigned_to_user_id') == $id ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="available" <?= old('status') == 'available' ? 'selected' : '' ?>>Available</option>
                            <option value="in_use" <?= old('status') == 'in_use' ? 'selected' : '' ?>>In Use</option>
                            <option value="standby" <?= old('status') == 'standby' ? 'selected' : '' ?>>Standby</option>
                            <option value="under_repair" <?= old('status') == 'under_repair' ? 'selected' : '' ?>>Under Repair</option>
                            <option value="retired" <?= old('status') == 'retired' ? 'selected' : '' ?>>Retired</option>
                            <option value="lost" <?= old('status') == 'lost' ? 'selected' : '' ?>>Lost</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="condition_status" class="form-label">Condition <span class="text-danger">*</span></label>
                        <select class="form-select" id="condition_status" name="condition_status" required>
                            <option value="new" <?= old('condition_status') == 'new' ? 'selected' : '' ?>>New</option>
                            <option value="good" <?= old('condition_status') == 'good' ? 'selected' : '' ?>>Good</option>
                            <option value="fair" <?= old('condition_status') == 'fair' ? 'selected' : '' ?>>Fair</option>
                            <option value="damaged" <?= old('condition_status') == 'damaged' ? 'selected' : '' ?>>Damaged</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="criticality" class="form-label">Criticality <span class="text-danger">*</span></label>
                        <select class="form-select" id="criticality" name="criticality" required>
                            <option value="low" <?= old('criticality') == 'low' ? 'selected' : '' ?>>Low</option>
                            <option value="medium" <?= old('criticality') == 'medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="high" <?= old('criticality') == 'high' ? 'selected' : '' ?>>High</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?= old('purchase_date') ?>">
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"><?= old('notes') ?></textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-success">Add Peripheral</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Peripherals Section -->
        <div class="detail-card">
            <h3 class="mb-4">Peripherals in this Asset</h3>
            
            <?php if (empty($peripherals)): ?>
                <div class="alert alert-info">No peripherals found in this asset.</div>
            <?php else: ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Asset Tag</th>
                            <th>Type</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Serial #</th>
                            <th>Status</th>
                            <th>Condition</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peripherals as $peripheral): ?>
                            <tr>
                                <td><?= $peripheral['id'] ?></td>
                                <td><?= $peripheral['asset_tag'] ?></td>
                                <td><?= $peripheral_types[$peripheral['peripheral_type_id']] ?? 'Unknown' ?></td>
                                <td><?= $peripheral['brand'] ?? '-' ?></td>
                                <td><?= $peripheral['model'] ?? '-' ?></td>
                                <td><?= $peripheral['serial_number'] ?? '-' ?></td>
                                <td>
                                    <span class="status-badge status-<?= $peripheral['status'] ?>">
                                        <?= ucfirst(str_replace('_', ' ', $peripheral['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge" style="background-color: #6c757d; color: white;">
                                        <?= ucfirst($peripheral['condition_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= site_url('peripherals/details/' . $peripheral['id']) ?>" class="btn btn-sm btn-info">Details</a>
                                    <a href="<?= site_url('assets/peripheral/edit/' . $peripheral['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= site_url('assets/peripheral/delete/' . $peripheral['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
