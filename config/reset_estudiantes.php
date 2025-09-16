<?php
// public/reset.php
session_start();
require __DIR__ . '/../config/db.php';

$token = $_GET['token'] ?? '';

$stmt = $mysqli->prepare("SELECT pr.id, pr.user_id, pr.expires_at, pr.used, u.email 
                          FROM password_resets pr
                          JOIN estudiantes u ON u.id = pr.user_id
                          WHERE pr.token = ? LIMIT 1");
$stmt->bind_param('s', $token);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row || $row['used'] || strtotime($row['expires_at']) < time()) {
  die("Token inválido o vencido.");
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Restablecer contraseña — Inventario Universidad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;display:flex;min-height:100vh;align-items:center;justify-content:center}
    .card{background:#111827;padding:24px;border-radius:12px;max-width:380px;width:100%;box-shadow:0 10px 30px rgba(0,0,0,.3)}
    h1{margin:0 0 16px;font-size:20px;text-align:center}
    label{display:block;margin:12px 0 6px}
    input{width:100%;padding:10px;border-radius:8px;border:1px solid #374151;background:#0b1220;color:#e5e7eb}
    button{width:100%;padding:10px;margin-top:16px;border-radius:8px;border:0;background:#2563eb;color:white;font-weight:600;cursor:pointer}
    .info{font-size:14px;text-align:center;margin-top:12px;color:#9ca3af}
  </style>
</head>
<body>
  <div class="card">
    <h1>Crear nueva contraseña</h1>
    <form action="process_reset.php" method="post">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <label>Nueva contraseña</label>
      <input type="password" name="password" required minlength="8">
      <label>Repetir contraseña</label>
      <input type="password" name="password2" required minlength="8">
      <button type="submit">Guardar</button>
    </form>
    <p class="info">La contraseña debe tener al menos 8 caracteres.</p>
  </div>
</body>
</html>