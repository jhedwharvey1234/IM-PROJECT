<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Asset</title>
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
    <?= view('partials/header', ['title' => 'Edit Asset']) ?>

    <div class="main-content">
        <h1>Edit Asset #<?= $asset['id'] ?></h1>
        
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

        <form action="<?= site_url('assets/update/' . $asset['id']) ?>" method="post" class="row g-3" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label for="tracking_number" class="form-label">Tracking Number</label>
                <input type="text" class="form-control" id="tracking_number" name="tracking_number" value="<?= old('tracking_number', $asset['tracking_number']) ?>">
            </div>

            <div class="col-md-6">
                <label for="box_number" class="form-label">Box Number</label>
                <input type="text" class="form-control" id="box_number" name="box_number" value="<?= old('box_number', $asset['box_number']) ?>">
            </div>

            <div class="col-md-6">
                <label for="barcode" class="form-label">Barcode Image</label>
                <?php if (!empty($asset['barcode'])): ?>
                    <div class="mb-2">
                        <img src="<?= base_url('uploads/barcodes/' . $asset['barcode']) ?>" alt="barcode" style="max-height:80px; max-width:160px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="barcode" name="barcode" accept="image/*">
                <small class="text-muted">Upload a new image to replace existing barcode</small>
            </div>

            <div class="col-md-6">
                <label for="sender" class="form-label">Sender *</label>
                <input type="text" class="form-control" id="sender" name="sender" value="<?= old('sender', $asset['sender']) ?>" required>
            </div>

            <div class="col-md-6">
                <label for="recipient" class="form-label">Recipient *</label>
                <input type="text" class="form-control" id="recipient" name="recipient" value="<?= old('recipient', $asset['recipient']) ?>" required>
            </div>

            <div class="col-12">
                <label for="address" class="form-label">Address *</label>
                <textarea class="form-control" id="address" name="address" rows="3" required><?= old('address', $asset['address']) ?></textarea>
            </div>

            <div class="col-md-6">
                <label for="status" class="form-label">Status *</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="pending" <?= old('status', $asset['status']) === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="in_transit" <?= old('status', $asset['status']) === 'in_transit' ? 'selected' : '' ?>>In Transit</option>
                    <option value="completed" <?= old('status', $asset['status']) === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="rejected" <?= old('status', $asset['status']) === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Date Sent</label>
                <div class="form-control" style="background-color: #f8f9fa;">
                    <?= date('M d, Y H:i', strtotime($asset['date_sent'])) ?>
                </div>
            </div>

            <div class="col-md-6 date-field" id="date_in_transit_container">
                <label for="date_in_transit" class="form-label">Date In Transit *</label>
                <input type="datetime-local" class="form-control" id="date_in_transit" name="date_in_transit" value="<?= old('date_in_transit', !empty($asset['date_in_transit']) ? date('Y-m-d\\TH:i', strtotime($asset['date_in_transit'])) : '') ?>">
            </div>

            <div class="col-md-6 date-field" id="date_received_container">
                <label for="date_received" class="form-label">Date Received *</label>
                <input type="datetime-local" class="form-control" id="date_received" name="date_received" value="<?= old('date_received', !empty($asset['date_received']) ? date('Y-m-d\\TH:i', strtotime($asset['date_received'])) : '') ?>">
            </div>

            <div class="col-md-6 date-field" id="date_rejected_container">
                <label for="date_rejected" class="form-label">Date Rejected *</label>
                <input type="datetime-local" class="form-control" id="date_rejected" name="date_rejected" value="<?= old('date_rejected', !empty($asset['date_rejected']) ? date('Y-m-d\\TH:i', strtotime($asset['date_rejected'])) : '') ?>">
            </div>

            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', $asset['description']) ?></textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Update Asset</button>
                <a href="<?= site_url('assets') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const statusSelect = document.getElementById('status');
        const dateInTransitField = document.getElementById('date_in_transit');
        const dateReceivedField = document.getElementById('date_received');
        const dateRejectedField = document.getElementById('date_rejected');
        const dateInTransitContainer = document.getElementById('date_in_transit_container');
        const dateReceivedContainer = document.getElementById('date_received_container');
        const dateRejectedContainer = document.getElementById('date_rejected_container');
        const currentStatus = '<?= $asset['status'] ?>';
        
        function setDefaultDateTime(field) {
            if (!field.value) {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                field.value = `${year}-${month}-${day}T${hours}:${minutes}`;
            }
        }
        
        function toggleDateFields(status) {
            // Hide all date fields
            dateInTransitContainer.classList.remove('active');
            dateReceivedContainer.classList.remove('active');
            dateRejectedContainer.classList.remove('active');
            
            // Show only the relevant field based on status
            if (status === 'in_transit') {
                dateInTransitContainer.classList.add('active');
            } else if (status === 'completed') {
                dateReceivedContainer.classList.add('active');
            } else if (status === 'rejected') {
                dateRejectedContainer.classList.add('active');
            }
        }
        
        // Initialize visibility on page load
        toggleDateFields(statusSelect.value);
        
        statusSelect.addEventListener('change', function() {
            toggleDateFields(this.value);
            
            if (this.value === 'in_transit' && currentStatus !== 'in_transit') {
                setDefaultDateTime(dateInTransitField);
                dateInTransitField.style.backgroundColor = '#fff3cd';
                dateInTransitField.focus();
                alert('Please set the Date In Transit for this asset.');
            } else if (this.value === 'completed' && currentStatus !== 'completed') {
                setDefaultDateTime(dateReceivedField);
                dateReceivedField.style.backgroundColor = '#fff3cd';
                dateReceivedField.focus();
                alert('Please set the Date Received for this completed asset.');
            } else if (this.value === 'rejected' && currentStatus !== 'rejected') {
                setDefaultDateTime(dateRejectedField);
                dateRejectedField.style.backgroundColor = '#fff3cd';
                dateRejectedField.focus();
                alert('Please set the Date Rejected for this asset.');
            }
        });
    </script>
</body>
</html>
