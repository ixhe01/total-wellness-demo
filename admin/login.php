<?php
require __DIR__ . '/_auth.php';
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = row('SELECT * FROM users WHERE email=?', [trim($_POST['email'] ?? '')]);
    if ($u && password_verify($_POST['password'] ?? '', $u['pass_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => $u['id'], 'name' => $u['name'], 'role' => $u['role']];
        header('Location: ./'); exit;
    }
    $err = 'Incorrect email or password';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Staff Portal · Total Wellness</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500&family=Jost:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css?v=1"><style>:root{--logo:url("<?= e(img('logo_light', 700)) ?>")}</style></head>
<body class="login">
<form class="login__box" method="post">
  <span class="emblem"></span>
  <h1>Staff Portal</h1>
  <p class="muted">Total Wellness Clinic · Appointments</p>
  <?php if ($err): ?><p class="err"><?= e($err) ?></p><?php endif; ?>
  <label>Email<input name="email" type="email" required autofocus value="<?= is_file(__DIR__ . '/../includes/secret.php') ? '' : 'admin@totalwellness.test' ?>"></label>
  <label>Password<input name="password" type="password" required ></label>
  <button class="b b--gold">Sign in</button>
  <?php if (!is_file(__DIR__ . '/../includes/secret.php')): ?><p class="hint">Demo: admin@totalwellness.test / demo1234</p><?php endif; ?>
</form>
</body></html>
