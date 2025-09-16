<?php
// public/estudiantes_registro.php
require __DIR__ . '/../config/db.php'; // NO requiere login del sistema interno

$ok = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ci       = trim($_POST['ci'] ?? '');
  $nombre   = trim($_POST['nombre'] ?? '');
  $apellido = trim($_POST['apellido'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $pass     = $_POST['password'] ?? '';

  if ($ci === '' || $nombre === '' || $apellido === '' || $email === '' || $pass === '') {
    $error = 'Todos los campos son obligatorios.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'El email no es válido.';
  } else {
    // ¿existe ya ese CI o email?
    $stmt = $mysqli->prepare("SELECT id FROM estudiantes WHERE ci=? OR email=? LIMIT 1");
    $stmt->bind_param('ss', $ci, $email);
    $stmt->execute();
    $existe = $stmt->get_result()->fetch_assoc();

    if ($existe) {
      $error = 'Ya existe un estudiante con ese CI o Email.';
    } else {
      $hash = password_hash($pass, PASSWORD_BCRYPT);

      $stmt = $mysqli->prepare("
        INSERT INTO estudiantes (ci, nombre, apellido, email, password_hash)
        VALUES (?,?,?,?,?)
      ");
      $stmt->bind_param('sssss', $ci, $nombre, $apellido, $email, $hash);
      $stmt->execute();

      $ok = true;
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Registro de estudiante</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;
         display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0}
    .card{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:24px;max-width:420px;width:100%}
    h1{margin:0 0 10px;font-size:20px}
    label{display:block;margin:12px 0 6px}
    input{width:100%;padding:10px;border-radius:8px;border:1px solid #374151;background:#0b1220;color:#e5e7eb}
    button{width:100%;padding:10px;margin-top:16px;border-radius:8px;border:0;background:#2563eb;color:white;font-weight:600;cursor:pointer}
    .error{background:#7f1d1d;color:#fecaca;padding:10px;border-radius:8px;margin-bottom:12px}
    .ok{background:#052e16;color:#bbf7d0;padding:10px;border-radius:8px;margin-bottom:12px}
    .muted{color:#9ca3af;font-size:12px;margin-top:8px;text-align:center}
    a{color:#93c5fd;text-decoration:none}
  </style>
</head>
<body>
  <div class="card">
    <h1>Registro de estudiante</h1>

    <?php if ($ok): ?>
      <div class="ok">¡Cuenta creada! Ya podés iniciar sesión cuando habilitemos el acceso de estudiantes.</div>
      <div class="muted"><a href="/inventario_uni/public/estudiantes_login.php">Volver al Inicio</a></div>
    <?php else: ?>
      <?php if ($error): ?><div class="error"><?=$error?></div><?php endif; ?>
      <form method="post" autocomplete="off">
        <label>CI</label>
        <input name="ci" required>

        <label>Nombre</label>
        <input name="nombre" required>

        <label>Apellido</label>
        <input name="apellido" required>

        <label>Email</label>
        <input name="email" type="email" required>

        <label>Contraseña</label>
        <input name="password" type="password" required minlength="8">

        <button type="submit">Registrarme</button>
      </form>
      <div class="muted">Tus datos serán usados solo para registrar préstamos.</div>
    <?php endif; ?>
  </div>
</body>
</html>
