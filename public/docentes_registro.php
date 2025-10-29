<?php
// public/estudiantes_registro.php
//require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';
require_login(); // Asumiendo que solo usuarios logueados pueden crear docentes.

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
    $stmt = $mysqli->prepare("SELECT id FROM docentes WHERE ci=? OR email=? LIMIT 1");
    $stmt->bind_param('ss', $ci, $email);
    $stmt->execute();
    $existe = $stmt->get_result()->fetch_assoc();

    if ($existe) {
      $error = 'Ya existe un docente con ese CI o Email.';
    } else {
      $hash = password_hash($pass, PASSWORD_BCRYPT);

      $stmt = $mysqli->prepare("
        INSERT INTO docentes (ci, nombre, apellido, email, password_hash)
        VALUES (?,?,?,?,?)
      ");
      $stmt->bind_param('sssss', $ci, $nombre, $apellido, $email, $hash);
      $stmt->execute();
      //--------parte de auditoria----------------
      // Obtener el ID del registro recién insertado
      $new_docente_id = $mysqli->insert_id;
      //-----------------------------------------------

      $ok = true;
      
      //---------INSERCIÓN DE LA AUDITORÍA-------------
      $docente_nombre = htmlspecialchars($nombre . ' ' . $apellido);
      $docente_ci = htmlspecialchars($ci);

      $accion_msg = "Registró un nuevo docente ID {$new_docente_id}: {$docente_nombre} (CI: {$docente_ci}).";
      // El ID del usuario que realiza la acción se toma de la sesión (user())
      // CLAVE: Añadir el tipo de acción 'accion_docentes'
auditar($accion_msg, 'acción_docentes');
      // ---------------------------------------------
    }
  }
}
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Registro de docente</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/docentes_registro.css">
</head>

<body>
  <?php
  include __DIR__ . '/navbar.php';
  ?>
  <div class="card">
    <h1>Registro de docente</h1>

    <?php if ($ok): ?>
      <div class="ok">¡Cuenta creada! El docente ya puede iniciar sesión.</div>
      <div class="muted"><a href="/inventario_uni/public/docentes_listar.php">Volver al Inicio</a></div>
    <?php else: ?>
      <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
      <form method="post" autocomplete="off">
        <label>CI</label>
        <input name="ci" type="text" required>

        <label>Nombre</label>
        <input name="nombre" type="text" required>

        <label>Apellido</label>
        <input name="apellido" type="text" required>

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