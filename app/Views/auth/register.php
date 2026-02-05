<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="<?= base_url('public/css/auth.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/css/buttons.css') ?>">
</head>
<body>
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

            <form action="<?= site_url('auth/store') ?>" method="post" novalidate>
                <div class="form-group">
                    <label for="username" class="visually-hidden">Username</label>
                    <input id="username" class="form-control" type="text" name="username" placeholder="Username" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="email" class="visually-hidden">Email</label>
                    <input id="email" class="form-control" type="email" name="email" placeholder="Email" required autocomplete="email">
                </div>

                <div class="form-group password-field">
                    <label for="password" class="visually-hidden">Password</label>
                    <input id="password" class="form-control" type="password" name="password" placeholder="Password" required autocomplete="new-password">
                    <button type="button" class="password-toggle-btn" id="togglePassword" aria-label="Show password">Show</button>
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