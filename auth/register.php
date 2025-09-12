<?php
// auth/register.php
session_start();
require __DIR__ . '/../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ci = trim($_POST['ci'] ?? '');
  $nombre = trim($_POST['nombre'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($ci === '' || $nombre === '' || $email === '' || $password === '') {
    $error = 'Todos los campos son obligatorios.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'El correo no es válido.';
  } else {
    // Verificar si ya existe CI o Email
    $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE ci = ? OR email = ? LIMIT 1");
    $stmt->bind_param('ss', $ci, $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->fetch_assoc()) {
      $error = 'El CI o correo ya están registrados.';
    } else {
      // Hash de la contraseña
      $hash = password_hash($password, PASSWORD_BCRYPT);
      $rol_id = 2; // por defecto

      $stmt = $mysqli->prepare("INSERT INTO usuarios (ci, nombre, email, password_hash, role_id) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param('ssssi', $ci, $nombre, $email, $hash, $rol_id);

      if ($stmt->execute()) {
        $success = 'Usuario registrado correctamente. Ahora puedes iniciar sesión.';
      } else {
        $error = 'Error al registrar usuario.';
      }
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Registro — Inventario Universidad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;display:flex;min-height:100vh;align-items:center;justify-content:center}
    .card{background:#111827;padding:24px;border-radius:12px;max-width:400px;width:100%;box-shadow:0 10px 30px rgba(0,0,0,.3)}
    h1{margin:0 0 12px;font-size:20px}
    label{display:block;margin:12px 0 6px}
    input{width:100%;padding:10px;border-radius:8px;border:1px solid #374151;background:#0b1220;color:#e5e7eb}
    button{width:100%;padding:10px;margin-top:16px;border-radius:8px;border:0;background:#16a34a;color:white;font-weight:600;cursor:pointer}
    .error{background:#7f1d1d;color:#fecaca;padding:10px;border-radius:8px;margin-bottom:12px}
    .success{background:#14532d;color:#bbf7d0;padding:10px;border-radius:8px;margin-bottom:12px}
    .muted{color:#9ca3af;font-size:12px;margin-top:8px;text-align:center}
    a{color:#3b82f6;text-decoration:none}
  </style>
</head>
<body>
  <div class="card">
    <h1>Registro de Usuario</h1>
    <?php if ($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?=htmlspecialchars($success)?></div><?php endif; ?>
    <form method="post">
      <label>CI</label>
      <input name="ci" required>
      <label>Nombre</label>
      <input name="nombre" required>
      <label>Correo electrónico</label>
      <input name="email" type="email" required>
      <label>Contraseña</label>
      <input name="password" type="password" required>
      <button type="submit">Registrar</button>
    </form>
    <div class="muted">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></div>
  </div>
</body>
</html>
