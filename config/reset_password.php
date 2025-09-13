<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../config/db.php'; // ✅ conexión a la BD


$error = '';
$success = '';

$token = $_GET['token'] ?? '';

if ($token === '') {
  die('Token inválido.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $password = $_POST['password'] ?? '';

  if ($password === '') {
    $error = 'Debe ingresar una nueva contraseña.';
  } else {
    // Buscar token válido
    $stmt = $mysqli->prepare("SELECT user_id, expira_en FROM password_resets WHERE token = ? LIMIT 1");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $res = $stmt->get_result();
    $reset = $res->fetch_assoc();

    if (!$reset) {
      $error = 'Token no válido.';
    } elseif (strtotime($reset['expira_en']) < time()) {
      $error = 'El token ha expirado.';
    } else {
      // Actualizar contraseña del usuario
      $hash = password_hash($password, PASSWORD_BCRYPT);
      $stmt = $mysqli->prepare("UPDATE usuarios SET password_hash = ? WHERE id = ?");
      $stmt->bind_param('si', $hash, $reset['user_id']);
      $stmt->execute();

      // Eliminar token usado
      $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE token = ?");
      $stmt->bind_param('s', $token);
      $stmt->execute();

      $success = 'Contraseña actualizada correctamente. <a href="login.php">Inicia sesión</a>';
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Restablecer Contraseña</title>
</head>
<body>
  <h1>Restablecer Contraseña</h1>
  <?php if ($error): ?><p style="color:red"><?=$error?></p><?php endif; ?>
  <?php if ($success): ?><p style="color:green"><?=$success?></p><?php endif; ?>
  <form method="post">
    <label>Nueva Contraseña</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Actualizar</button>
  </form>
</body>
</html>
