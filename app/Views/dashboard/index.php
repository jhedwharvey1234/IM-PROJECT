<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background-color: #f8f9fa; padding: 20px; position: fixed; height: 100%; top: 56px; left: 0; }
        .main-content { margin-left: 250px; margin-top: 56px; padding: 20px; flex: 1; }
        .sidebar a { display: block; padding: 10px; text-decoration: none; color: #333; border-bottom: 1px solid #ddd; }
        .sidebar a:hover { background-color: #e9ecef; }
        
        /* Statistics Cards */
        .stat-card { 
            border: none; 
            border-radius: 12px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); 
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .stat-card-body {
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
            flex-shrink: 0;
        }
        .stat-content {
            flex: 1;
            margin-left: 16px;
        }
        .stat-label {
            font-size: 0.9rem;
            font-weight: 500;
            opacity: 0.8;
            margin-bottom: 8px;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
        }
        
        /* Card color variations */
        .stat-card-users { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .stat-card-assignable { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; }
        .stat-card-azure { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
        .stat-card-non-azure { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; }
        .stat-card-units { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white; }
        .stat-card-assets { background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); color: white; }
        .stat-card-peripherals { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333; }
        .stat-card-applications { background: linear-gradient(135deg, #c471f5 0%, #fa71cd 100%); color: white; }
        .stat-card-documents { background: linear-gradient(135deg, #f6d365 0%, #fda085 100%); color: #333; }
        .stat-card-dcfs { background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%); color: #333; }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .welcome-header {
            margin-bottom: 30px;
        }
        .welcome-header h1 {
            color: #2f3550;
            margin-bottom: 8px;
        }
        .welcome-header p {
            color: #6c757d;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <?= view('partials/header', ['title' => 'Dashboard']) ?>

    <div class="main-content">
        <div class="welcome-header">
            <h1>Welcome to Dashboard, <?= session()->get('username') ?>!</h1>
            <p>Here's a quick overview of your system statistics.</p>
        </div>

        <div class="stats-grid">
            <!-- Total Users Card -->
            <div class="card stat-card stat-card-users">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Users</div>
                        <div class="stat-value"><?= $totalUsers ?></div>
                    </div>
                </div>
            </div>

            <!-- Assignable Users Card -->
            <div class="card stat-card stat-card-assignable">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Assignable Users</div>
                        <div class="stat-value"><?= $totalAssignableUsers ?></div>
                    </div>
                </div>
            </div>

            <!-- Azure AD Users Card -->
            <div class="card stat-card stat-card-azure">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="bi bi-cloud-check-fill"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Azure AD Users</div>
                        <div class="stat-value"><?= $azureAdUsers ?></div>
                    </div>
                </div>
            </div>

            <!-- Non-Azure AD Users Card -->
            <div class="card stat-card stat-card-non-azure">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Local Users</div>
                        <div class="stat-value"><?= $nonAzureAdUsers ?></div>
                    </div>
                </div>
            </div>

            <!-- Total Units Card -->
            <div class="card stat-card stat-card-units">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="bi bi-hdd-rack-fill"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Units</div>
                        <div class="stat-value"><?= $totalUnits ?></div>
                    </div>
                </div>
            </div>

            <!-- Total Assets Card -->
            <div class="card stat-card stat-card-assets">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Assets</div>
                        <div class="stat-value"><?= $totalAssets ?></div>
                    </div>
                </div>
            </div>

            <!-- Total Peripherals Card -->
            <div class="card stat-card stat-card-peripherals">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="bi bi-phone-fill"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Peripherals</div>
                        <div class="stat-value"><?= $totalPeripherals ?></div>
                    </div>
                </div>
            </div>

            <!-- Total Applications Card -->
            <div class="card stat-card stat-card-applications">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="bi bi-window-stack"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Applications</div>
                        <div class="stat-value"><?= $totalApplications ?></div>
                    </div>
                </div>
            </div>

            <!-- Total Documents Card -->
            <div class="card stat-card stat-card-documents">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Documents</div>
                        <div class="stat-value"><?= $totalDocuments ?></div>
                    </div>
                </div>
            </div>

            <!-- Total DCF Card -->
            <div class="card stat-card stat-card-dcfs">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="bi bi-ui-checks-grid"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total DCF</div>
                        <div class="stat-value"><?= $totalDcfs ?></div>
                    </div>
                </div>
            </div>
        </div>

        <?= view('partials/footer') ?>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>