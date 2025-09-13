<?php
session_start();
require __DIR__ . '/../config/db.php'; // ✅ conexión a la BD


$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ci = trim($_POST['ci'] ?? '');

  if ($ci === '') {
    $error = 'Debe ingresar su CI.';
  } else {
    $stmt = $mysqli->prepare("SELECT id, nombre, email FROM usuarios WHERE ci = ? LIMIT 1");
    $stmt->bind_param('s', $ci);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if (!$user) {
      $error = 'No existe un usuario con ese CI.';
    } else {
      // Generar token y vencimiento
      $token = bin2hex(random_bytes(32));
      $expira_en = date('Y-m-d H:i:s', strtotime('+30 minutes'));

      // Guardar en DB
      $stmt = $mysqli->prepare("INSERT INTO password_resets (user_id, token, expira_en) VALUES (?, ?, ?)");
      $stmt->bind_param('iss', $user['id'], $token, $expira_en);
      $stmt->execute();

      // Enlace de recuperación
      $reset_link = "http://localhost/inventario_uni/auth/reset_password.php?token=" . $token;

      // Aquí envías el correo (usar mail() o PHPMailer)
     //  mail($user['email'], "Recupera tu contraseña", "Haz clic en: $reset_link");
      $success = "Se envió un enlace de recuperación (simulado): <a href='$reset_link'>$reset_link</a>";
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Recuperar Contraseña</title>
  <style>
    body{font-family:system-ui;background:#0f172a;color:#e2e8f0;display:flex;min-height:100vh;align-items:center;justify-content:center}
    .card{background:#111827;padding:24px;border-radius:12px;max-width:400px;width:100%;}
    input,button{width:100%;padding:10px;margin-top:10px;border-radius:8px;border:0}
    button{background:#2563eb;color:#fff;font-weight:600;cursor:pointer}
    .error{background:#7f1d1d;color:#fecaca;padding:10px;border-radius:8px;margin-bottom:12px}
    .success{background:#14532d;color:#bbf7d0;padding:10px;border-radius:8px;margin-bottom:12px}
  </style>
</head>
<body>
  <div class="card">
    <h1>Recuperar Contraseña</h1>
    <?php if ($error): ?><div class="error"><?=$error?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?=$success?></div><?php endif; ?>
    <form method="post">
      <label>Ingrese su CI</label>
      <input name="ci" required>
      <button type="submit">Enviar enlace</button>
    </form>
  </div>
</body>
</html>
