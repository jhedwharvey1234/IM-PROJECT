<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
    <?= view('partials/header', ['title' => 'User Management']) ?>

    <div class="main-content">
        <h1>User Management</h1>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="<?= site_url('users/create') ?>" class="btn btn-primary me-2">Add New User</a>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">Back to Dashboard</a>
            </div>
            <div class="d-flex gap-2">
                <input type="text" id="users_search" class="form-control" placeholder="Search (username, email, usertype, created_at)" style="width: 400px;">
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>

            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Usertype</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="usersTable">
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td><?= $user['usertype'] ?></td>
                        <td><?= $user['created_at'] ?></td>
                        <td>
                            <a href="<?= site_url('users/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= site_url('users/delete/' . $user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?= view('partials/footer') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('users_search');
    const tableBody = document.getElementById('usersTable');
    let timer;

    function fetchResults() {
        const q = searchInput.value;

        fetch(`<?= site_url('users/search') ?>?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-muted">No results found</td>
                        </tr>`;
                    return;
                }

                data.forEach(user => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>${user.usertype}</td>
                            <td>${user.created_at}</td>
                            <td>
                                <a href="<?= site_url('users/edit/') ?>${user.id}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="<?= site_url('users/delete/') ?>${user.id}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>`;
                });
            });
    }

    searchInput.addEventListener('keyup', () => {
        clearTimeout(timer);
        timer = setTimeout(fetchResults, 250);
    });
});
</script>

</body>
</html>