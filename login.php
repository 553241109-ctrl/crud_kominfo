<?php
require __DIR__ . '/config.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, username, password FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: index.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin | InventarisKu</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-page">
  <main class="login-card">
    <div class="brand-mark">IK</div>
    <p class="eyebrow">SISTEM INVENTARIS</p>
    <h1>Selamat datang</h1>
    <p class="muted">Masuk untuk mengelola perangkat komputer.</p>

    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

    <form method="post" class="form-stack">
      <label>Username
        <input type="text" name="username" autocomplete="username" required autofocus>
      </label>
      <label>Password
        <input type="password" name="password" autocomplete="current-password" required>
      </label>
      <button class="btn primary full" type="submit">Masuk ke dashboard</button>
    </form>
    <div class="demo-login">
      <strong>Akun demo</strong>
      <span>Username: <code>admin</code></span>
      <span>Password: <code>password</code></span>
    </div>
  </main>
</body>
</html>
