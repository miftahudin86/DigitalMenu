<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Digital Menu</title>
    <meta name="description" content="Login ke panel admin Digital Menu Management">
    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">
</head>
<body>

<div class="login-page">
    <div class="login-card">
        <div class="login-logo">
            <div class="icon">🍽️</div>
            <h1>Digital Menu</h1>
            <p>Masuk ke panel manajemen menu</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                ⚠️ <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('/auth/login') ?>" method="POST" id="loginForm">

            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan username"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary" id="loginBtn">
                🔑 Masuk ke Dashboard
            </button>
        </form>

        <div class="login-footer">
            &copy; <?= date('Y') ?> Digital Menu Management System
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.textContent = '⏳ Memproses...';
    btn.disabled = true;
});
</script>

</body>
</html>
