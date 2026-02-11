<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
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

        .detail-card { background-color: #fff; padding: 25px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e9ecef; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .detail-card h3 { color: #212529; font-weight: 600; margin-bottom: 20px; font-size: 18px; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; display: flex; align-items: center; }
        .detail-card h3 i { margin-right: 10px; color: #0d6efd; }

        .detail-row { margin-bottom: 18px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0; }
        .detail-row:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
        .detail-label { font-weight: 600; color: #495057; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 6px; }
        .detail-value { color: #212529; font-size: 15px; word-break: break-word; }

        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; display: inline-block; }
        .status-superadmin { background-color: #dc3545; color: #fff; }
        .status-readandwrite { background-color: #fd7e14; color: #fff; }
        .status-readonly { background-color: #28a745; color: #fff; }

        .nav-tabs { border-bottom: 2px solid #e9ecef; }
        .nav-tabs .nav-link { color: #495057; border: none; border-bottom: 3px solid transparent; padding: 12px 16px; font-weight: 500; }
        .nav-tabs .nav-link:hover { color: #0d6efd; border-bottom-color: #0d6efd; background-color: transparent; }
        .nav-tabs .nav-link.active { color: #0d6efd; border-bottom-color: #0d6efd; background-color: transparent; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }

        .info-section { background: #f8f9fa; padding: 16px; border-radius: 6px; border-left: 4px solid #0d6efd; }
        .info-section h5 { color: #0d6efd; font-weight: 600; margin-bottom: 15px; font-size: 13px; text-transform: uppercase; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'User Details']) ?>

    <div class="main-content">
        <div class="breadcrumb-nav">
            <a href="<?= site_url('dashboard') ?>" title="Go to Dashboard"><i class="bi bi-house-door-fill"></i></a>
            <span class="separator">›</span>
            <a href="<?= site_url('users') ?>" title="Go to Users">Users</a>
            <span class="separator">›</span>
            <span class="current"><?= esc($profile['display_name']) ?></span>
        </div>

        <ul class="nav nav-tabs" id="userDetailsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-profile-btn" data-bs-toggle="tab" data-bs-target="#tab-profile" type="button" role="tab" aria-controls="tab-profile" aria-selected="true"><i class="bi bi-person"></i> Profile</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-assets-btn" data-bs-toggle="tab" data-bs-target="#tab-assets" type="button" role="tab" aria-controls="tab-assets" aria-selected="false"><i class="bi bi-box-seam"></i> Assets</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-history-btn" data-bs-toggle="tab" data-bs-target="#tab-history" type="button" role="tab" aria-controls="tab-history" aria-selected="false"><i class="bi bi-clock-history"></i> History</button>
            </li>
        </ul>

        <div class="tab-content mt-4" id="userDetailsTabsContent">
            <div class="tab-pane fade show active" id="tab-profile" role="tabpanel" aria-labelledby="tab-profile-btn">
                <div class="detail-card">
                    <h3><i class="bi bi-person-badge"></i> User Profile</h3>
                    <div class="info-grid">
                        <div>
                            <div class="detail-row">
                                <span class="detail-label"><i class="bi bi-person"></i> Name</span>
                                <span class="detail-value"><?= esc($profile['display_name']) ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label"><i class="bi bi-person-check"></i> Type</span>
                                <span class="detail-value">
                                    <?php if ($profile['is_system_user']): ?>
                                        <span class="badge bg-primary">System User</span>
                                    <?php else: ?>
                                        <span class="badge bg-info">Assignable Only</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <?php if (!empty($profile['assignable_id'])): ?>
                                <div class="detail-row">
                                    <span class="detail-label"><i class="bi bi-link-45deg"></i> Assignable ID</span>
                                    <span class="detail-value">A-<?= esc($profile['assignable_id']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if (!empty($profile['username'])): ?>
                                <div class="detail-row">
                                    <span class="detail-label"><i class="bi bi-at"></i> Username</span>
                                    <span class="detail-value"><?= esc($profile['username']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($profile['email'])): ?>
                                <div class="detail-row">
                                    <span class="detail-label"><i class="bi bi-envelope"></i> Email</span>
                                    <span class="detail-value"><?= esc($profile['email']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($profile['usertype'])): ?>
                                <div class="detail-row">
                                    <span class="detail-label"><i class="bi bi-shield-lock"></i> Usertype</span>
                                    <span class="detail-value">
                                        <span class="status-badge status-<?= esc($profile['usertype']) ?>">
                                            <?= esc(ucfirst($profile['usertype'])) ?>
                                        </span>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div class="detail-row">
                                <span class="detail-label"><i class="bi bi-calendar-event"></i> Created</span>
                                <span class="detail-value">
                                    <?= !empty($profile['created_at']) ? date('M d, Y h:i A', strtotime($profile['created_at'])) : '<span class="text-muted">-</span>' ?>
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label"><i class="bi bi-calendar-check"></i> Updated</span>
                                <span class="detail-value">
                                    <?= !empty($profile['updated_at']) ? date('M d, Y h:i A', strtotime($profile['updated_at'])) : '<span class="text-muted">-</span>' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-assets" role="tabpanel" aria-labelledby="tab-assets-btn">
                <div class="detail-card">
                    <h3><i class="bi bi-box"></i> Assigned Assets</h3>
                    <?php if (!empty($assets)): ?>
                        <div class="mb-3" style="max-width: 420px;">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" id="assetsSearch" class="form-control" placeholder="Search assets by tag, model, status...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 120px;"><i class="bi bi-tag"></i> Asset Tag</th>
                                        <th><i class="bi bi-laptop"></i> Model</th>
                                        <th style="width: 140px;"><i class="bi bi-flag"></i> Status</th>
                                        <th style="width: 120px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="assetsTableBody">
                                    <?php foreach ($assets as $asset): ?>
                                        <?php
                                            $assetTag = $asset['asset_tag'] ?? '';
                                            $assetModel = $asset['model'] ?? '';
                                            $assetStatus = $asset['status'] ?? '';
                                            $searchValue = trim(strtolower($assetTag . ' ' . $assetModel . ' ' . $assetStatus));
                                        ?>
                                        <tr data-search="<?= esc($searchValue) ?>">
                                            <td><?= !empty($asset['asset_tag']) ? esc($asset['asset_tag']) : '<span class="text-muted">-</span>' ?></td>
                                            <td><?= !empty($asset['model']) ? esc($asset['model']) : '<span class="text-muted">-</span>' ?></td>
                                            <td>
                                                <?php if (!empty($asset['status'])): ?>
                                                    <span class="badge bg-secondary">
                                                        <?= esc(ucfirst($asset['status'])) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= site_url('assets/details/' . $asset['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr id="assetsEmptyRow" style="display: none;">
                                        <td colspan="4" class="text-center text-muted">No matching assets found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info"><i class="bi bi-info-circle"></i> No assets assigned to this user.</div>
                    <?php endif; ?>
                </div>

                <div class="detail-card">
                    <h3><i class="bi bi-cpu"></i> Assigned Peripherals</h3>
                    <?php if (!empty($peripherals)): ?>
                        <div class="mb-3" style="max-width: 420px;">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" id="peripheralsSearch" class="form-control" placeholder="Search peripherals by asset tag, brand, model, serial, status...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 120px;"><i class="bi bi-tag"></i> Asset Tag</th>
                                        <th style="width: 120px;"><i class="bi bi-hdd"></i> Brand</th>
                                        <th><i class="bi bi-cpu"></i> Model</th>
                                        <th style="width: 160px;"><i class="bi bi-hash"></i> Serial</th>
                                        <th style="width: 140px;"><i class="bi bi-flag"></i> Status</th>
                                        <th style="width: 120px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="peripheralsTableBody">
                                    <?php foreach ($peripherals as $peripheral): ?>
                                        <?php
                                            $peripheralAssetTag = $peripheral['asset_tag'] ?? '';
                                            $peripheralBrand = $peripheral['brand'] ?? '';
                                            $peripheralModel = $peripheral['model'] ?? '';
                                            $peripheralSerial = $peripheral['serial_number'] ?? '';
                                            $peripheralStatus = $peripheral['status'] ?? '';
                                            $peripheralSearch = trim(strtolower($peripheralAssetTag . ' ' . $peripheralBrand . ' ' . $peripheralModel . ' ' . $peripheralSerial . ' ' . $peripheralStatus));
                                        ?>
                                        <tr data-search="<?= esc($peripheralSearch) ?>">
                                            <td><?= !empty($peripheral['asset_tag']) ? esc($peripheral['asset_tag']) : '<span class="text-muted">-</span>' ?></td>
                                            <td><?= !empty($peripheral['brand']) ? esc($peripheral['brand']) : '<span class="text-muted">-</span>' ?></td>
                                            <td><?= !empty($peripheral['model']) ? esc($peripheral['model']) : '<span class="text-muted">-</span>' ?></td>
                                            <td><?= !empty($peripheral['serial_number']) ? esc($peripheral['serial_number']) : '<span class="text-muted">-</span>' ?></td>
                                            <td>
                                                <?php if (!empty($peripheral['status'])): ?>
                                                    <span class="badge bg-secondary">
                                                        <?= esc(ucfirst(str_replace('_', ' ', $peripheral['status']))) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= site_url('peripherals/details/' . $peripheral['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr id="peripheralsEmptyRow" style="display: none;">
                                        <td colspan="6" class="text-center text-muted">No matching peripherals found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info"><i class="bi bi-info-circle"></i> No peripherals assigned to this user.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="tab-history" role="tabpanel" aria-labelledby="tab-history-btn">
                <div class="detail-card">
                    <h3><i class="bi bi-clock-history"></i> User Activity</h3>
                    <?php if (!empty($history)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 150px;"><i class="bi bi-calendar-event"></i> Date & Time</th>
                                        <th style="width: 140px;"><i class="bi bi-box"></i> Asset</th>
                                        <th style="width: 120px;"><i class="bi bi-lightning-fill"></i> Action</th>
                                        <th style="width: 140px;"><i class="bi bi-tag"></i> Field</th>
                                        <th><i class="bi bi-text-left"></i> Changes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($history as $entry): ?>
                                        <tr>
                                            <td>
                                                <small><strong><?= date('M d, Y', strtotime($entry['created_at'])) ?></strong><br>
                                                <span class="text-muted"><?= date('h:i A', strtotime($entry['created_at'])) ?></span></small>
                                            </td>
                                            <td>
                                                <div><strong><?= esc($entry['asset_tag'] ?? 'Asset') ?></strong></div>
                                                <?php if (!empty($entry['model'])): ?>
                                                    <small class="text-muted"><?= esc($entry['model']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= esc(ucfirst(str_replace('_', ' ', $entry['action']))) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (!empty($entry['field_name'])): ?>
                                                    <code style="background: #f0f0f0; padding: 2px 6px; border-radius: 3px;"><?= esc($entry['field_name']) ?></code>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($entry['description'])): ?>
                                                    <div class="mb-1"><?= esc($entry['description']) ?></div>
                                                <?php endif; ?>
                                                <?php if (!empty($entry['old_value']) || !empty($entry['new_value'])): ?>
                                                    <div class="small">
                                                        <?php if (!empty($entry['old_value'])): ?>
                                                            <span class="text-danger"><i class="bi bi-dash-circle"></i> <del><?= esc($entry['old_value']) ?></del></span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($entry['new_value'])): ?>
                                                            <?php if (!empty($entry['old_value'])): ?>
                                                                <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                                            <?php endif; ?>
                                                            <span class="text-success"><i class="bi bi-check-circle"></i> <strong><?= esc($entry['new_value']) ?></strong></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info"><i class="bi bi-info-circle"></i> No activity records found for this user.</div>
                    <?php endif; ?>
                </div>

                <div class="detail-card">
                    <h3><i class="bi bi-person-check"></i> Assignment History</h3>
                    <?php if (!empty($assignment_history)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 150px;"><i class="bi bi-calendar-event"></i> Date & Time</th>
                                        <th style="width: 140px;"><i class="bi bi-box"></i> Asset</th>
                                        <th><i class="bi bi-text-left"></i> Changes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($assignment_history as $entry): ?>
                                        <tr>
                                            <td>
                                                <small><strong><?= date('M d, Y', strtotime($entry['created_at'])) ?></strong><br>
                                                <span class="text-muted"><?= date('h:i A', strtotime($entry['created_at'])) ?></span></small>
                                            </td>
                                            <td>
                                                <div><strong><?= esc($entry['asset_tag'] ?? 'Asset') ?></strong></div>
                                                <?php if (!empty($entry['model'])): ?>
                                                    <small class="text-muted"><?= esc($entry['model']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($entry['description'])): ?>
                                                    <div class="mb-1"><?= esc($entry['description']) ?></div>
                                                <?php endif; ?>
                                                <?php if (!empty($entry['old_value']) || !empty($entry['new_value'])): ?>
                                                    <div class="small">
                                                        <?php if (!empty($entry['old_value'])): ?>
                                                            <span class="text-danger"><i class="bi bi-dash-circle"></i> <del><?= esc($entry['old_value']) ?></del></span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($entry['new_value'])): ?>
                                                            <?php if (!empty($entry['old_value'])): ?>
                                                                <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                                            <?php endif; ?>
                                                            <span class="text-success"><i class="bi bi-check-circle"></i> <strong><?= esc($entry['new_value']) ?></strong></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info"><i class="bi bi-info-circle"></i> No assignment history found for this user.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?= view('partials/footer') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const assetsSearchInput = document.getElementById('assetsSearch');
            const assetsTableBody = document.getElementById('assetsTableBody');
            const assetsEmptyRow = document.getElementById('assetsEmptyRow');
            const peripheralsSearchInput = document.getElementById('peripheralsSearch');
            const peripheralsTableBody = document.getElementById('peripheralsTableBody');
            const peripheralsEmptyRow = document.getElementById('peripheralsEmptyRow');

            if (!assetsSearchInput || !assetsTableBody) {
                // Assets search not available for this user.
            }

            const assetRows = assetsTableBody
                ? Array.from(assetsTableBody.querySelectorAll('tr')).filter(row => row.id !== 'assetsEmptyRow')
                : [];

            function filterAssets() {
                if (!assetsSearchInput || !assetsTableBody) {
                    return;
                }

                const query = assetsSearchInput.value.trim().toLowerCase();
                let visibleCount = 0;

                assetRows.forEach(row => {
                    const haystack = row.getAttribute('data-search') || '';
                    const isMatch = haystack.includes(query);
                    row.style.display = isMatch ? '' : 'none';
                    if (isMatch) {
                        visibleCount += 1;
                    }
                });

                if (assetsEmptyRow) {
                    assetsEmptyRow.style.display = visibleCount === 0 ? '' : 'none';
                }
            }

            if (assetsSearchInput) {
                assetsSearchInput.addEventListener('input', filterAssets);
            }

            const peripheralRows = peripheralsTableBody
                ? Array.from(peripheralsTableBody.querySelectorAll('tr')).filter(row => row.id !== 'peripheralsEmptyRow')
                : [];

            function filterPeripherals() {
                if (!peripheralsSearchInput || !peripheralsTableBody) {
                    return;
                }

                const query = peripheralsSearchInput.value.trim().toLowerCase();
                let visibleCount = 0;

                peripheralRows.forEach(row => {
                    const haystack = row.getAttribute('data-search') || '';
                    const isMatch = haystack.includes(query);
                    row.style.display = isMatch ? '' : 'none';
                    if (isMatch) {
                        visibleCount += 1;
                    }
                });

                if (peripheralsEmptyRow) {
                    peripheralsEmptyRow.style.display = visibleCount === 0 ? '' : 'none';
                }
            }

            if (peripheralsSearchInput) {
                peripheralsSearchInput.addEventListener('input', filterPeripherals);
            }
        });
    </script>
</body>
</html>
