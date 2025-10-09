<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Panel</title>
    <link rel="stylesheet" href="<?= BASEURL . '/css/admin/login.css' ?>">
</head>

<body>
    <div class="bg-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="logo">
            <div class="logo-icon">
                <img src="<?= BASEURL . '/img/pinarak-logo.png' ?>" alt="Pinarak Jogja Logo">
            </div>
            <h1>Selamat Datang</h1>
            <p>Masuk ke akun Anda</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <span class="alert-icon">⚠️</span>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <span><?= htmlspecialchars($success) ?></span>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="<?= BASEURL . '/admin/auth/login' ?>">
            <div class="form-group">
                <label for="username">Email atau Username</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" placeholder="Masukkan email atau username" required autocomplete="email"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    <div class="input-icon">👤</div>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required autocomplete="current-password">
                    <div class="input-icon">🔒</div>
                </div>
            </div>

            <button type="submit" class="login-button">
                Masuk
            </button>
        </form>
    </div>

    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>

</html>