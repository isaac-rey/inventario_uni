<?php
// auth/register.php

//require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';


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

         // Necesitamos el ID del usuario que se acaba de registrar para auditar correctamente, lo sacamos del mysqli
            $new_user_id = $mysqli->insert_id; 
            auditar("Registró al nuevo usuario con ID {$new_user_id}"); 
            // ---------------------------------
        header("Location: ../public/usuarios_index.php");
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
  <link rel="stylesheet" href="../css/form_login.css">
</head>
<body>
<?php include __DIR__ . '/../public/navbar.php'; ?>

  <div class="card">
    <h1>Registro de Usuario</h1>
    <?php if ($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?=htmlspecialchars($success)?></div><?php endif; ?>
    <form method="post">
      <label>CI</label>
      <input name="ci" type="text" required>
      <label>Nombre</label>
      <input name="nombre" type="text" required>
      <label>Correo electrónico</label>
      <input name="email" type="email" required>
      <label>Contraseña</label>
      <input name="password" type="password" required>
      <button type="submit">Registrar</button>
    </form>
  </div>
</body>
</html>