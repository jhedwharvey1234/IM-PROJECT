<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Asset Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/IM/public/css/responsive-global.css">
    <style>
        body { background: #f5f7fa; }
        .public-wrap { max-width: 980px; margin: 20px auto; padding: 0 12px; }
        .topbar { background: #212529; color: #fff; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; gap: 10px; }
        .topbar .brand { font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
        .topbar a { color: #fff; text-decoration: none; font-size: 13px; }
        .card-public { border: 1px solid #e3e8ee; box-shadow: 0 1px 3px rgba(16,24,40,.06); border-radius: 10px; }
        .kv-label { color: #6c757d; font-size: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: .04em; }
        .status-chip { display: inline-block; font-size: 12px; padding: 4px 10px; border-radius: 999px; background: #d1e7dd; color: #0f5132; font-weight: 600; }
        .thumb { background: #fff; border: 1px dashed #d0d7de; border-radius: 8px; padding: 14px; text-align: center; }
        .thumb img { max-width: 100%; max-height: 220px; object-fit: contain; }
    </style>
</head>
<body>
    <div class="public-wrap">
        <div class="topbar">
            <div class="brand"><i class="bi bi-laptop"></i> Public Asset Details</div>
            <a href="<?= site_url('login') ?>"><i class="bi bi-box-arrow-in-right"></i> Login</a>
        </div>

        <div class="card card-public mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="thumb h-100 d-flex align-items-center justify-content-center">
                            <?php if (!empty($asset['device_image'])): ?>
                                <img src="<?= base_url('uploads/devices/' . $asset['device_image']) ?>" alt="Asset image">
                            <?php else: ?>
                                <div class="text-muted"><i class="bi bi-image" style="font-size:40px"></i><div>No image</div></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <h4 class="mb-2"><?= esc($asset['model'] ?: 'Asset') ?></h4>
                        <p class="text-muted mb-3">Limited public view via share link/QR.</p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="kv-label">Asset Tag</div>
                                <div><?= esc($asset['asset_tag'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="kv-label">Serial Number</div>
                                <div><?= esc($asset['serial_number'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="kv-label">Model Number</div>
                                <div><?= esc($asset['model_number'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="kv-label">Manufacturer</div>
                                <div><?= esc($asset['manufacturer'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="kv-label">Category</div>
                                <div><?= esc($asset['category'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="kv-label">Status</div>
                                <div><span class="status-chip"><?= esc(ucfirst((string) ($asset['status'] ?? 'unknown'))) ?></span></div>
                            </div>
                            <div class="col-md-6">
                                <div class="kv-label">Department</div>
                                <div><?= esc(!empty($asset['department_id']) && isset($departments[$asset['department_id']]) ? $departments[$asset['department_id']] : '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="kv-label">Location</div>
                                <div><?= esc(!empty($asset['location_id']) && isset($locations[$asset['location_id']]) ? $locations[$asset['location_id']] : '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="kv-label">Workstation</div>
                                <div><?= esc(!empty($asset['workstation_id']) && isset($workstations[$asset['workstation_id']]) ? $workstations[$asset['workstation_id']] : '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="kv-label">Purchase Date</div>
                                <div><?= !empty($asset['purchase_date']) ? esc(date('M d, Y', strtotime($asset['purchase_date']))) : '-' ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-public">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-cpu"></i> Linked Peripherals</h6>
                <?php if (empty($peripherals)): ?>
                    <div class="text-muted">No peripherals linked.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>Serial</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($peripherals as $peripheral): ?>
                                    <tr>
                                        <td><?= esc($peripheralTypes[$peripheral['peripheral_type_id']] ?? 'Unknown') ?></td>
                                        <td><?= esc($peripheral['brand'] ?? '-') ?></td>
                                        <td><?= esc($peripheral['model'] ?? '-') ?></td>
                                        <td><?= esc($peripheral['serial_number'] ?? '-') ?></td>
                                        <td><?= esc($peripheral['status'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
