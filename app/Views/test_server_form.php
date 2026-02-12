<!DOCTYPE html>
<html>
<head>
    <title>Test Server Form</title>
</head>
<body>
    <h1>Test Server Form Submission</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div style="color: red; padding: 10px; border: 1px solid red;">
            Error: <?= $_SESSION['error'] ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div style="color: green; padding: 10px; border: 1px solid green;">
            Success: <?= $_SESSION['success'] ?>
        </div>
    <?php endif; ?>
    
    <form action="<?= site_url('settings/servers/store') ?>" method="post" style="margin-top: 20px;">
        <?= csrf_field() ?>
        
        <div style="margin-bottom: 15px;">
            <label for="server_name">Server Name (Required):</label><br>
            <input type="text" name="server_name" id="server_name" value="Test Server from Debug Form" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="ip_address">IP Address:</label><br>
            <input type="text" name="ip_address" id="ip_address" value="192.168.1.100">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label for="server_type">Server Type:</label><br>
            <input type="text" name="server_type" id="server_type" value="Web Server">
        </div>
        
        <button type="submit">Submit Server</button>
    </form>
    
    <hr>
    
    <h2>Debug Information</h2>
    <p>This form submits to: <code><?= site_url('settings/servers/store') ?></code></p>
    <p>Check the log file at: <code>writable/logs/log-<?= date('Y-m-d') ?>.log</code></p>
    
    <h3>POST Data Received (if any):</h3>
    <pre><?php 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        print_r($_POST);
    } else {
        echo "No POST data yet";
    }
    ?></pre>
</body>
</html>
