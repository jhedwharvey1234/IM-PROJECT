<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        .detail-card { background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Edit Item']) ?>

    <div class="main-content">
        <h1>Edit Item #<?= $item['id'] ?></h1>
        
        <div class="mb-3">
            <a href="<?= site_url('assets/details/' . $asset['id']) ?>" class="btn btn-secondary">Back to Asset Details</a>
        </div>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="detail-card">
            <h4 class="mb-4">Asset Information</h4>
            <p><strong>ID:</strong> <?= $asset['id'] ?></p>
            <p><strong>Tracking Number:</strong> <?= $asset['tracking_number'] ?? '-' ?></p>
            <p><strong>Sender:</strong> <?= $asset['sender'] ?></p>
            <p><strong>Recipient:</strong> <?= $asset['recipient'] ?></p>
        </div>

        <form action="<?= site_url('assets/item/update/' . $item['id']) ?>" method="post" class="row g-3">
            <?= csrf_field() ?>
            
            <div class="col-md-6">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="E.g., Laptop" value="<?php 
                    $decoded = json_decode($item['item_description'], true);
                    echo old('item_name', $decoded['item_name'] ?? '');
                ?>">
            </div>

            <div class="col-md-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="E.g., 5" value="<?php 
                    $decoded = json_decode($item['item_description'], true);
                    echo old('quantity', $decoded['quantity'] ?? '');
                ?>">
            </div>

            <div class="col-md-3">
                <label for="unit" class="form-label">Unit</label>
                <input type="text" class="form-control" id="unit" name="unit" placeholder="E.g., pieces" value="<?php 
                    $decoded = json_decode($item['item_description'], true);
                    echo old('unit', $decoded['unit'] ?? '');
                ?>">
            </div>

            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Additional details about this item"><?php 
                    $decoded = json_decode($item['item_description'], true);
                    echo old('description', $decoded['description'] ?? '');
                ?></textarea>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">Update Item</button>
                <a href="<?= site_url('assets/details/' . $asset['id']) ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
