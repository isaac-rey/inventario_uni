<?php
// public/reset.php
session_start();
require __DIR__ . '/../config/db.php';

$token = $_GET['token'] ?? '';

$stmt = $mysqli->prepare("SELECT pr.id, pr.user_id, pr.expires_at, pr.used, u.email 
                          FROM password_resets pr
                          JOIN usuarios u ON u.id = pr.user_id
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
  <link rel="stylesheet" href="../css/form_login.css">
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