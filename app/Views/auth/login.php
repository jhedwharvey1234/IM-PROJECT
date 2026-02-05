<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?= base_url('public/css/auth.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/css/buttons.css') ?>">
</head>
<body>
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

            <form action="<?= site_url('auth/authenticate') ?>" method="post" novalidate>
                <div class="form-group">
                    <label for="email" class="visually-hidden">Email</label>
                    <input id="email" class="form-control" type="email" name="email" placeholder="Email" required autocomplete="username">
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

            <div class="auth-help">
                <p>Don't have an account? <a href="<?= site_url('register') ?>">Create an account</a></p>
            </div>
            <div class="auth-footer">&copy; <?= date('Y') ?> IM Admin</div>
        </div>
    </main>

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