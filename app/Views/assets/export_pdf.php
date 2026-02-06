<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset #<?= $asset['id'] ?> - Report</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            font-size: 12px;
        }
        
        .container {
            max-width: 8.5in;
            height: 11in;
            margin: 0 auto;
            padding: 0.4in;
            background: white;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18px;
            margin-bottom: 2px;
        }
        
        .header p {
            font-size: 11px;
            color: #666;
        }
        
        .content {
            flex: 1;
            overflow: hidden;
        }
        
        .section {
            margin-bottom: 8px;
            page-break-inside: avoid;
        }
        
        .section-title {
            font-size: 11px;
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 4px 8px;
            border-left: 3px solid #007bff;
            margin-bottom: 6px;
        }
        
        .row {
            display: flex;
            gap: 15px;
            margin-bottom: 6px;
        }
        
        .col {
            flex: 1;
            min-width: 0;
        }
        
        .field {
            margin-bottom: 6px;
        }
        
        .field-label {
            font-weight: bold;
            color: #007bff;
            margin-bottom: 2px;
            font-size: 10px;
        }
        
        .field-value {
            padding: 4px 6px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 2px;
            word-break: break-word;
            font-size: 11px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        
        .status-in_transit {
            background-color: #17a2b8;
            color: #fff;
        }
        
        .status-completed {
            background-color: #28a745;
            color: #fff;
        }
        
        .status-rejected {
            background-color: #dc3545;
            color: #fff;
        }
        
        .barcode-container {
            text-align: center;
            margin: 6px 0;
            page-break-inside: avoid;
        }
        
        .barcode-container img {
            max-width: 200px;
            max-height: 80px;
            border: 1px solid #ddd;
            padding: 4px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 10px;
        }
        
        .items-table th {
            background-color: #007bff;
            color: white;
            padding: 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #007bff;
        }
        
        .items-table td {
            padding: 4px;
            border: 1px solid #ddd;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .print-button {
            display: block;
            margin: 10px auto;
            padding: 8px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
        }
        
        .print-button:hover {
            background-color: #0056b3;
        }
        
        .signature-section {
            margin-top: auto;
            page-break-inside: avoid;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        
        .signature-row {
            display: flex;
            gap: 30px;
            margin-top: 20px;
        }
        
        .signature-col {
            flex: 1;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            height: 40px;
            margin-bottom: 4px;
        }
        
        .signature-label {
            font-size: 10px;
            font-weight: bold;
        }

        .signature-input {
            width: 90%;
            border: none;
            
            padding: 2px 0;
            font-size: 10px;
            background: transparent;
            text-align: center;
        }

        .signature-name {
            margin-top: 16px;
            border-bottom: 1px solid #000;
        }

        .signature-date {
            margin-top: 4px;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            body {
                margin: 0;
                padding: 0;
            }
            
            .container {
                max-width: 100%;
                height: auto;
                padding: 0;
            }
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 10px;
            color: #007bff;
            text-decoration: none;
            font-size: 12px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        @media print {
            .back-link {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="<?= site_url('assets') ?>" class="back-link">← Back to Assets</a>
        
        <div class="header">
            <h1>Asset Report</h1>
            <p>Asset #<?= $asset['id'] ?> | <?= date('M d, Y') ?></p>
        </div>
        
        <div class="content">
            <div class="section">
                <div class="section-title">Basic Information</div>
                <div class="row">
                    <div class="col">
                        <div class="field">
                            <div class="field-label">Asset ID</div>
                            <div class="field-value"><?= $asset['id'] ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="field">
                            <div class="field-label">Tracking #</div>
                            <div class="field-value"><?= $asset['tracking_number'] ?? '-' ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="field">
                            <div class="field-label">Box #</div>
                            <div class="field-value"><?= $asset['box_number'] ?? '-' ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="field">
                            <div class="field-label">Status</div>
                            <div class="field-value">
                                <span class="status-badge status-<?= $asset['status'] ?>">
                                    <?= ucfirst($asset['status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($asset['barcode'])): ?>
            <div class="section">
                <div class="barcode-container">
                    <img src="<?= base_url('uploads/barcodes/' . $asset['barcode']) ?>" alt="barcode">
                </div>
            </div>
            <?php endif; ?>
            
            <div class="section">
                <div class="section-title">Shipment Details</div>
                <div class="row">
                    <div class="col">
                        <div class="field">
                            <div class="field-label">Sender</div>
                            <div class="field-value"><?= $asset['sender'] ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="field">
                            <div class="field-label">Recipient</div>
                            <div class="field-value"><?= $asset['recipient'] ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="field">
                    <div class="field-label">Address</div>
                    <div class="field-value"><?= nl2br($asset['address']) ?></div>
                </div>
                
                <?php if (!empty($asset['description'])): ?>
                <div class="field">
                    <div class="field-label">Description</div>
                    <div class="field-value"><?= nl2br(substr($asset['description'], 0, 100)) ?><?= strlen($asset['description']) > 100 ? '...' : '' ?></div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="section">
                <div class="section-title">Timeline</div>
                <div class="row">
                    <div class="col">
                        <div class="field">
                            <div class="field-label">Date Sent</div>
                            <div class="field-value"><?= date('M d, Y H:i', strtotime($asset['date_sent'])) ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="field">
                            <div class="field-label">In Transit</div>
                            <div class="field-value"><?= !empty($asset['date_in_transit']) ? date('M d H:i', strtotime($asset['date_in_transit'])) : '-' ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="field">
                            <div class="field-label">Received</div>
                            <div class="field-value"><?= !empty($asset['date_received']) ? date('M d H:i', strtotime($asset['date_received'])) : '-' ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="field">
                            <div class="field-label">Rejected</div>
                            <div class="field-value"><?= !empty($asset['date_rejected']) ? date('M d H:i', strtotime($asset['date_rejected'])) : '-' ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($items)): ?>
            <div class="section">
                <div class="section-title">Items</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Item Description</th>
                            <th style="width: 60px;">Qty</th>
                            <th style="width: 50px;">Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <?php
                                $itemData = json_decode($item['item_description'], true);
                                $itemName = $itemData['item_name'] ?? '-';
                                $quantity = $itemData['quantity'] ?? '-';
                                $unit = $itemData['unit'] ?? '-';
                            ?>
                            <tr>
                                <td><strong><?= substr($itemName, 0, 30) ?></strong></td>
                                <td><?= $quantity ?></td>
                                <td><?= $unit ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="signature-section">
            <div style="font-size: 11px; font-weight: bold; margin-bottom: 10px;">Authorized Signatures</div>
            <div class="signature-row">
                <div class="signature-col">
                    <div class="signature-label">Prepared By</div>
                    <input type="text" class="signature-input signature-name" placeholder="Name">
                    <input type="date" class="signature-input signature-date">
                </div>
                <div class="signature-col">
                    <div class="signature-label">Received By</div>
                    <input type="text" class="signature-input signature-name" placeholder="Name">
                    <input type="date" class="signature-input signature-date">
                </div>
                <div class="signature-col">
                    <div class="signature-label">Approved By</div>
                    <input type="text" class="signature-input signature-name" placeholder="Name">
                    <input type="date" class="signature-input signature-date">
                </div>
            </div>
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 20px;">
        <button class="print-button" onclick="window.print()">🖨️ Print / Save as PDF</button>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-adjust to fit page
            window.addEventListener('beforeprint', function() {
                console.log('Printing...');
            });
        });
    </script>
</body>
</html>
