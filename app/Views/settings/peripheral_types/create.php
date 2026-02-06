<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Peripheral Type</title>
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
    <?= view('partials/header', ['title' => 'Create Peripheral Type']) ?>

    <div class="main-content">
        <h1>Create Peripheral Type</h1>
        <a href="<?= site_url('settings/peripheral-types') ?>" class="btn btn-secondary mb-3">Back to Types</a>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('settings/peripheral-types/store') ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            <div class="col-md-6">
                <label for="type_name" class="form-label">Type Name *</label>
                <input type="text" class="form-control" id="type_name" name="type_name" value="<?= old('type_name') ?>" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Create Type</button>
                <a href="<?= site_url('settings/peripheral-types') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
