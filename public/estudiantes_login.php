<?php
// public/estudiantes_login.php
session_start();
require __DIR__ . '/../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ci = trim($_POST['ci'] ?? '');
  $pass = $_POST['password'] ?? '';

  if ($ci === '' || $pass === '') {
    $error = 'CI y contraseña son obligatorios.';
  } else {
    $stmt = $mysqli->prepare("SELECT id, ci, nombre, apellido, password_hash FROM estudiantes WHERE ci=? LIMIT 1");
    $stmt->bind_param('s', $ci);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row && password_verify($pass, $row['password_hash'])) {
      $_SESSION['est'] = [
        'id' => $row['id'],
        'ci' => $row['ci'],
        'nombre' => $row['nombre'],
        'apellido' => $row['apellido'],
      ];
      header('Location: /inventario_uni/public/estudiante_panel.php');
      exit;
    } else {
      $error = 'CI o contraseña incorrectos.';
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Login estudiante — Inventario</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0}
    .card{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:24px;max-width:380px;width:100%}
    h1{margin:0 0 10px;font-size:20px}
    label{display:block;margin:12px 0 6px}
    input{width:100%;padding:10px;border-radius:8px;border:1px solid #374151;background:#0b1220;color:#e5e7eb}
    button{width:100%;padding:10px;margin-top:16px;border-radius:8px;border:0;background:#2563eb;color:white;font-weight:600;cursor:pointer}
    .error{background:#7f1d1d;color:#fecaca;padding:10px;border-radius:8px;margin-bottom:12px}
    .muted{color:#9ca3af;font-size:12px;margin-top:8px;text-align:center}
    a{color:#93c5fd;text-decoration:none}
  </style>
</head>
<body>
  <div class="card">
    <h1>Ingreso de estudiante</h1>
    <?php if ($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="post" autocomplete="off">
      <label>CI</label>
      <input name="ci" required>
      <label>Contraseña</label>
      <input name="password" type="password" required>
      <button type="submit">Entrar</button>
    </form>
    <div class="muted" style="margin-top:12px;">
      ¿No tenés cuenta? <a href="/inventario_uni/public/estudiantes_registro.php">Registrate</a>
       <p><a href="../config/password_estudiantes.php">¿Olvidaste tu contraseña?</a></p>
    </div>
  </div>
</body>
</html>
