<?php
// auth/login.php
session_start();
require __DIR__ . '/../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['login'] ?? ''); // Puede ser CI o email
  $password = $_POST['password'] ?? '';

  if ($login === '' || $password === '') {
    $error = 'Correo/CI y contraseña son obligatorios.';
  } else {
    $stmt = $mysqli->prepare("
      SELECT u.id, u.ci, u.nombre, u.email, u.password_hash, r.nombre AS rol
      FROM usuarios u
      JOIN roles r ON r.id = u.role_id
      WHERE u.ci = ? OR u.email = ?
      LIMIT 1
    ");
    $stmt->bind_param('ss', $login, $login);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
      $_SESSION['user'] = [
        'id'     => $user['id'],
        'ci'     => $user['ci'],
        'email'  => $user['email'],
        'nombre' => $user['nombre'],
        'rol'    => $user['rol'],
      ];
      header('Location: /inventario_uni/index.php');
      exit;
    } else {
      $error = 'Credenciales incorrectas.';
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login — Inventario Universidad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;display:flex;min-height:100vh;align-items:center;justify-content:center}
    .card{background:#111827;padding:24px;border-radius:12px;max-width:360px;width:100%;box-shadow:0 10px 30px rgba(0,0,0,.3)}
    h1{margin:0 0 12px;font-size:20px;text-align:center}
    label{display:block;margin:12px 0 6px}
    input{width:100%;padding:10px;border-radius:8px;border:1px solid #374151;background:#0b1220;color:#e5e7eb}
    button{width:100%;padding:10px;margin-top:16px;border-radius:8px;border:0;background:#2563eb;color:white;font-weight:600;cursor:pointer}
    .error{background:#7f1d1d;color:#fecaca;padding:10px;border-radius:8px;margin-bottom:12px}
    .register-btn{background:#16a34a;margin-top:8px}
  </style>
</head>
<body>
  <div class="card">
    <h1>Inventario — Universidad</h1>
    <?php if ($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="post">
      <label>Correo electrónico o CI</label>
      <input name="login" autocomplete="username" required>
      <label>Contraseña</label>
      <input name="password" type="password" autocomplete="current-password" required>
      <button type="submit">Ingresar</button>
    </form>
    <form action="register.php" method="get">
      <button type="submit" class="register-btn">Registrarse</button>
      <p><a href="forgot_password.php">¿Olvidaste tu contraseña?</a></p>
    </form>
  </div>
</body>
</html>
