<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <a class="auth-topbar-brand" href="<?= site_url('login') ?>">
                <i class="bi bi-shield-lock"></i>
                <span>IM Admin</span>
            </a>
            <nav class="auth-topbar-nav">
                <a href="<?= site_url('documents') ?>">
                    <i class="bi bi-folder2-open"></i>
                    <span>Public Documents</span>
                </a>
                <a href="<?= site_url('register') ?>">
                    <i class="bi bi-person-plus"></i>
                    <span>Register</span>
                </a>
            </nav>
        </header>

        <main class="auth-wrap">
            <div class="auth-card" role="main" aria-labelledby="login-heading">
            <div class="auth-brand">
                <h2 id="login-heading">IM Admin</h2>
                <div class="auth-sub">Sign in to your account</div>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash-error" role="alert"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="flash-success" role="status"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <form action="/IM/auth/authenticate" method="post" novalidate>
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="email" class="visually-hidden">Email</label>
                    <input id="email" class="form-control" type="email" name="email" placeholder="name@example.com" required autocomplete="username" title="Please enter a valid email address">
                    <small class="email-hint-invalid text-danger">Please enter a valid email address</small>
                </div>

                <div class="form-group password-field">
                    <label for="password" class="visually-hidden">Password</label>
                    <input id="password" class="form-control" type="password" name="password" placeholder="Password" required autocomplete="current-password">
                    <button type="button" class="password-toggle-btn" id="togglePassword" aria-label="Show password">Show</button>
                </div>

                <div class="form-actions">
                    <button class="btn btn-primary btn-block" type="submit">Sign in</button>
                </div>
            </form>

            <?php
                $entraEnabled = filter_var((string) (getenv('ENTRA_ENABLED') ?: 'false'), FILTER_VALIDATE_BOOLEAN);
                $entraTenant = trim((string) (getenv('ENTRA_TENANT_ID') ?: ''));
                $entraClientId = trim((string) (getenv('ENTRA_CLIENT_ID') ?: ''));
                $showMicrosoftLogin = $entraEnabled && $entraTenant !== '' && $entraClientId !== '';
            ?>
            <?php if ($showMicrosoftLogin): ?>
                <div class="my-3 text-center text-muted">or</div>
                <div class="form-actions">
                    <a class="btn btn-outline-secondary btn-block" href="<?= site_url('auth/entra/login') ?>">
                        <i class="bi bi-microsoft me-1"></i> Sign in with Microsoft
                    </a>
                </div>
            <?php endif; ?>

            <div class="auth-help">
                <p>Don't have an account? <a href="<?= site_url('register') ?>">Create an account</a></p>
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