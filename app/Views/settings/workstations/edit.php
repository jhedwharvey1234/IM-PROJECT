<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Workstation</title>
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
    <?= view('partials/header', ['title' => 'Edit Workstation']) ?>

    <div class="main-content">
        <h1>Edit Workstation</h1>
        <a href="<?= site_url('settings/workstations') ?>" class="btn btn-secondary mb-3">Back to Workstations</a>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('settings/workstations/update/' . $workstation['id']) ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <label for="workstation_code" class="form-label">Workstation Code *</label>
                <input type="text" class="form-control" id="workstation_code" name="workstation_code" value="<?= old('workstation_code', $workstation['workstation_code']) ?>" required>
            </div>
            <div class="col-md-6">
                <label for="location_id" class="form-label">Location</label>
                <select class="form-control" id="location_id" name="location_id">
                    <option value="">-- None --</option>
                    <?php foreach ($locations as $id => $name): ?>
                        <option value="<?= $id ?>" <?= old('location_id', $workstation['location_id']) == $id ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Update Workstation</button>
                <a href="<?= site_url('settings/workstations') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
