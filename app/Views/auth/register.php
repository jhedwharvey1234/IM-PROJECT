<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/IM/public/css/auth.css?v=20260226-2">
    <link rel="stylesheet" href="/IM/public/css/buttons.css">
    <link rel="stylesheet" href="/IM/public/css/responsive-global.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .email-hint-invalid { display: none; font-size: 12px; margin-top: 4px; }
        input[type="email"]:not(:placeholder-shown):invalid + .email-hint-invalid { display: block; }
    </style>
</head>
<body>
    <div class="auth-shell">
        <header class="auth-topbar" aria-label="Authentication navigation">
            <a class="auth-topbar-brand" href="<?= site_url('register') ?>">
                <i class="bi bi-shield-lock"></i>
                <span>IM Admin</span>
            </a>
            <nav class="auth-topbar-nav">
                <a href="<?= site_url('documents') ?>">
                    <i class="bi bi-folder2-open"></i>
                    <span>Public Documents</span>
                </a>
                <a href="<?= site_url('login') ?>">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Sign in</span>
                </a>
            </nav>
        </header>

        <main class="auth-wrap">
            <div class="auth-card" role="main" aria-labelledby="register-heading">
            <div class="auth-brand">
                <h2 id="register-heading">Create Account</h2>
                <div class="auth-sub">Register a new administrator account</div>
            </div>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="flash-error" role="alert">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="/IM/auth/store" method="post" novalidate>
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="username" class="visually-hidden">Username</label>
                    <input id="username" class="form-control" type="text" name="username" placeholder="Username" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="email" class="visually-hidden">Email</label>
                    <input id="email" class="form-control" type="email" name="email" placeholder="name@example.com" required autocomplete="email" title="Please enter a valid email address">
                    <small class="email-hint-invalid text-danger">Please enter a valid email address</small>
                </div>

                <div class="form-group password-field">
                    <label for="password" class="visually-hidden">Password</label>
                    <input id="password" class="form-control" type="password" name="password" placeholder="Password" required autocomplete="new-password">
                    <button type="button" class="password-toggle-btn" id="togglePassword" aria-label="Show password">Show</button>
                </div>

                <div class="form-group">
                    <label for="usertype" class="visually-hidden">User Role</label>
                    <select id="usertype" class="form-control" name="usertype" required>
                        <option value="">Select User Role</option>
                        <?php if (!empty($userRoles)): ?>
                            <?php foreach ($userRoles as $role): ?>
                                <option value="<?= esc($role['role_key']) ?>" <?= old('usertype') === $role['role_key'] ? 'selected' : '' ?>>
                                    <?= esc($role['role_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary btn-block" type="submit">Create account</button>
                </div>
            </form>

            <div class="auth-help">
                <p>Already have an account? <a href="<?= site_url('login') ?>">Sign in</a></p>
            </div>
            <div class="auth-footer">&copy; <?= date('Y') ?> IM Admin</div>
            </div>
        </main>
    </div>

    <script>
    (function(){
      var pw = document.getElementById('password');
      var btn = document.getElementById('togglePassword');
      if(!pw || !btn) return;
      btn.addEventListener('click', function(){
        if(pw.type === 'password'){ pw.type = 'text'; btn.textContent = 'Hide'; btn.setAttribute('aria-pressed','true'); }
        else { pw.type = 'password'; btn.textContent = 'Show'; btn.setAttribute('aria-pressed','false'); }
      });
    })();
    </script>
</body>
</html>