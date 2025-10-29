<?php
// public/estudiantes_registro.php
//
require __DIR__ . '/../init.php'; 
//

//require __DIR__ . '/../config/db.php';


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
     // $stmt->execute();

      //-----------------insersion de la auditoria--------------------
      if ($stmt->execute()) {
 $nuevo_estudiante_id = $mysqli->insert_id;
        
        // --- CAMBIO CLAVE AQUÍ ---
        $accion_descripcion = "Registró un nuevo estudiante con ID {$nuevo_estudiante_id} - Nombre: {$nombre} {$apellido} (C.I: {$ci}).";
        // Añadimos el nuevo parámetro: 'registro_estudiante'
auditar($accion_descripcion, 'acción_estudiante'); 

$ok = true;
      } else {
        // Manejo de error de inserción
        $error = "Error al registrar el estudiante: " . $mysqli->error;
      }
      //--------------------------------------------------------------
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
  <link rel="stylesheet" href="../css/estudiantes_registro.css">
</head>

<body>
  <?php
  include __DIR__ . '/navbar.php';
  ?>
  <div class="card">
    <h1>Registro de estudiante</h1>

    <?php if ($ok): ?>
      <div class="ok">¡Cuenta creada! Ya podés iniciar sesión cuando habilitemos el acceso de estudiantes.</div>
      <div class="muted"><a href="/inventario_uni/public/estudiantes_listar.php">Volver al Inicio</a></div>
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