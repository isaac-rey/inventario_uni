<?php
// public/docentes_editar.php
require __DIR__ . '/../config/db.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
  die("Docente no especificado.");
}

// Obtener datos actuales
$stmt = $mysqli->prepare("SELECT * FROM docentes WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$docente = $stmt->get_result()->fetch_assoc();
if (!$docente) {
  die("Docente no encontrado.");
}

$ok = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ci       = trim($_POST['ci'] ?? '');
  $nombre   = trim($_POST['nombre'] ?? '');
  $apellido = trim($_POST['apellido'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $pass     = $_POST['password'] ?? '';

  if ($ci === '' || $nombre === '' || $apellido === '' || $email === '') {
    $error = 'Todos los campos son obligatorios (excepto contraseña si no quieres cambiarla).';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'El email no es válido.';
  } else {
    // ¿Existe ya ese CI o email en otro estudiante?
    $stmt = $mysqli->prepare("SELECT id FROM docentes WHERE (ci=? OR email=?) AND id<>? LIMIT 1");
    $stmt->bind_param('ssi', $ci, $email, $id);
    $stmt->execute();
    $existe = $stmt->get_result()->fetch_assoc();

    if ($existe) {
      $error = 'Ya existe otro docente con ese CI o Email.';
    } else {
      if ($pass !== '') {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $stmt = $mysqli->prepare("UPDATE docentes SET ci=?, nombre=?, apellido=?, email=?, password_hash=? WHERE id=?");
        $stmt->bind_param('sssssi', $ci, $nombre, $apellido, $email, $hash, $id);
      } else {
        $stmt = $mysqli->prepare("UPDATE docentes SET ci=?, nombre=?, apellido=?, email=? WHERE id=?");
        $stmt->bind_param('ssssi', $ci, $nombre, $apellido, $email, $id);
      }
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
  <title>Editar docente</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/docentes_registro.css">
</head>

<body>
  <?php include __DIR__ . '/navbar.php'; ?>
  <div class="card">
    <h1>Editar docente</h1>

    <?php if ($ok): ?>
      <div class="ok">Datos actualizados correctamente.</div>
      <div class="muted"><a href="/inventario_uni/public/docentes_listar.php">Volver a la lista</a></div>
    <?php else: ?>
      <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
      <form method="post" autocomplete="off">
        <label>CI</label>
        <input name="ci" type="text" required value="<?= htmlspecialchars($docente['ci']) ?>">

        <label>Nombre</label>
        <input name="nombre" type="text" required value="<?= htmlspecialchars($docente['nombre']) ?>">

        <label>Apellido</label>
        <input name="apellido" type="text" required value="<?= htmlspecialchars($docente['apellido']) ?>">

        <label>Email</label>
        <input name="email" type="email" required value="<?= htmlspecialchars($docente['email']) ?>">

        <label>Nueva contraseña (opcional)</label>
        <input name="password" type="password" minlength="8" placeholder="Dejar en blanco para no cambiar">

        <button type="submit">Guardar cambios</button>
      </form>
      <div class="muted">Podés dejar la contraseña en blanco si no querés modificarla.</div>
    <?php endif; ?>
  </div>
</body>

</html>
