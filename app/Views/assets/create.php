<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Asset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .date-field { display: none; }
        .date-field.active { display: block; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Create Asset']) ?>

    <div class="main-content">
        <h1>Create Asset</h1>
        
        <a href="<?= site_url('assets') ?>" class="btn btn-secondary mb-3">Back to Assets</a>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('assets/store') ?>" method="post" class="row g-3" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label for="tracking_number" class="form-label">Tracking Number</label>
                <input type="text" class="form-control" id="tracking_number" name="tracking_number" value="<?= old('tracking_number') ?>">
            </div>

            <div class="col-md-6">
                <label for="box_number" class="form-label">Box Number</label>
                <input type="text" class="form-control" id="box_number" name="box_number" value="<?= old('box_number') ?>">
            </div>

            <div class="col-md-6">
                <label for="barcode" class="form-label">Barcode Image</label>
                <input type="file" class="form-control" id="barcode" name="barcode" accept="image/*">
                <small class="text-muted">Optional - upload an image for the barcode</small>
            </div>

            <div class="col-md-6">
                <label for="sender" class="form-label">Sender *</label>
                <input type="text" class="form-control" id="sender" name="sender" value="<?= old('sender') ?>" required>
            </div>

            <div class="col-md-6">
                <label for="recipient" class="form-label">Recipient *</label>
                <input type="text" class="form-control" id="recipient" name="recipient" value="<?= old('recipient') ?>" required>
            </div>

            <div class="col-12">
                <label for="address" class="form-label">Address *</label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?= old('address') ?></textarea>
            </div>

            <div class="col-md-6">
                <label for="status" class="form-label">Status *</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="pending" <?= old('status') === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="in_transit" <?= old('status') === 'in_transit' ? 'selected' : '' ?>>In Transit</option>
                    <option value="completed" <?= old('status') === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="rejected" <?= old('status') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>

            <div class="col-md-6 date-field" id="date_sent_container">
                <label for="date_sent" class="form-label">Date Sent *</label>
                <input type="datetime-local" class="form-control" id="date_sent" name="date_sent" value="<?= old('date_sent', date('Y-m-d\TH:i')) ?>" required>
            </div>

            <div class="col-md-6 date-field" id="date_in_transit_container">
                <label for="date_in_transit" class="form-label">Date In Transit *</label>
                <input type="datetime-local" class="form-control" id="date_in_transit" name="date_in_transit" value="<?= old('date_in_transit') ?>">
            </div>

            <div class="col-md-6 date-field" id="date_received_container">
                <label for="date_received" class="form-label">Date Received *</label>
                <input type="datetime-local" class="form-control" id="date_received" name="date_received" value="<?= old('date_received') ?>">
            </div>

            <div class="col-md-6 date-field" id="date_rejected_container">
                <label for="date_rejected" class="form-label">Date Rejected *</label>
                <input type="datetime-local" class="form-control" id="date_rejected" name="date_rejected" value="<?= old('date_rejected') ?>">
            </div>

            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= old('description') ?></textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Create Asset</button>
                <a href="<?= site_url('assets') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const statusSelect = document.getElementById('status');
        const dateSentContainer = document.getElementById('date_sent_container');
        const dateInTransitContainer = document.getElementById('date_in_transit_container');
        const dateReceivedContainer = document.getElementById('date_received_container');
        const dateRejectedContainer = document.getElementById('date_rejected_container');

        function toggleDateFields(status) {
            dateSentContainer.classList.remove('active');
            dateInTransitContainer.classList.remove('active');
            dateReceivedContainer.classList.remove('active');
            dateRejectedContainer.classList.remove('active');

            if (status === 'pending') {
                dateSentContainer.classList.add('active');
            } else if (status === 'in_transit') {
                dateInTransitContainer.classList.add('active');
            } else if (status === 'completed') {
                dateReceivedContainer.classList.add('active');
            } else if (status === 'rejected') {
                dateRejectedContainer.classList.add('active');
            }
        }

        toggleDateFields(statusSelect.value);
        statusSelect.addEventListener('change', function() {
            toggleDateFields(this.value);
        });
    </script>
</body>
</html>
