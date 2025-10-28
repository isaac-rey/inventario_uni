<?php
// auth/login.php
//session_start();
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';


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
//---------------------Auditar el login---------------------------
// ✅ 2. REGISTRAR AUDITORÍA DE INICIO DE SESIÓN EXITOSO
      // (Debemos registrar la auditoría DESPUÉS de establecer la sesión, 
      // ya que 'auditar' utiliza $_SESSION['user']['id'])
      $user_desc = htmlspecialchars($user['nombre'] . ' (Rol: ' . $user['rol'] . ')');
      // La función 'auditar' utiliza la ID del usuario logueado
      auditar("Inicio de sesión exitoso. Usuario: {$user_desc}.");
// ---------------------------------------------------------------

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
  <link rel="stylesheet" href="../css/form_login.css">
</head>
<body>
  <div class="card">
    <h1>Inventario — Universidad</h1>
    <?php if ($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="post">
      <label>Correo electrónico o CI</label>
      <input type="text" name="login" autocomplete="username" required>
      <label>Contraseña</label>
      <input name="password" type="password" autocomplete="current-password" required>
      <button type="submit">Ingresar</button>
    </form>
    <form action="register.php" method="get">
      
      <p><a href="../config/forgot_password.php">¿Olvidaste tu contraseña?</a></p>
    </form>
  </div>
</body>
</html>
