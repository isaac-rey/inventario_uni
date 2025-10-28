<?php
// public/salas_registro.php
require __DIR__ . '/../init.php'; 
require_login();

$ok = false;
$error = '';

// Obtener áreas disponibles
$stmt_areas = $mysqli->query("SELECT id, nombre FROM areas ORDER BY nombre");
$areas = $stmt_areas->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $area_id = trim($_POST['area_id'] ?? '');
  $nombre = trim($_POST['nombre'] ?? '');
  $descripcion = trim($_POST['descripcion'] ?? '');

  if ($area_id === '' || $nombre === '') {
    $error = 'El área y el nombre de la sala son obligatorios.';
  } else {
    // Verificar si ya existe esa sala en esa área
    $stmt = $mysqli->prepare("SELECT id FROM salas WHERE area_id=? AND nombre=? LIMIT 1");
    $stmt->bind_param('is', $area_id, $nombre);
    $stmt->execute();
    $existe = $stmt->get_result()->fetch_assoc();

    if ($existe) {
      $error = 'Ya existe una sala con ese nombre en el área seleccionada.';
    } else {
      $stmt = $mysqli->prepare("
        INSERT INTO salas (area_id, nombre, descripcion)
        VALUES (?,?,?)
      ");
      $stmt->bind_param('iss', $area_id, $nombre, $descripcion);

      if ($stmt->execute()) {
        $nueva_sala_id = $mysqli->insert_id;
        auditar("Registró una nueva sala con ID {$nueva_sala_id} y Nombre: {$nombre}");
        $ok = true;
      } else {
        $error = "Error al registrar la sala: " . $mysqli->error;
      }
    }
  } 
}

$currentPage = basename(__FILE__);
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Registro de Sala</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/form_salas.css">
</head>

<body>
  <?php include __DIR__ . '/navbar.php'; ?>
  
  <div class="card">
    <h1>Registro de Sala</h1>

    <?php if ($ok): ?>
      <div class="ok">¡Sala registrada exitosamente!</div>
      <div class="muted">
        <a href="/inventario_uni/public/salas.php">Ver listado de salas</a> | 
        <a href="/inventario_uni/public/salas_registro.php">Registrar otra sala</a>
      </div>
    <?php else: ?>
      <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
      
      <?php if (empty($areas)): ?>
        <div class="error">No hay áreas registradas. Por favor, registre un área primero.</div>
        <div class="muted"><a href="/inventario_uni/public/areas_registro.php">Registrar Área</a></div>
      <?php else: ?>
        <form method="post" autocomplete="off">
          <label>Área *</label>
          <select name="area_id" required>
            <option value="">-- Seleccione un área --</option>
            <?php foreach ($areas as $area): ?>
              <option value="<?= $area['id'] ?>"><?= htmlspecialchars($area['nombre']) ?></option>
            <?php endforeach; ?>
          </select>

          <label>Nombre de la Sala *</label>
          <input name="nombre" type="text" required placeholder="Ej: Laboratorio 1, Estantería A, etc.">

          <label>Descripción</label>
          <textarea name="descripcion" rows="4" placeholder="Descripción adicional de la sala..."></textarea>

          <button type="submit">Registrar Sala</button>
        </form>
        <div class="muted">Los campos marcados con * son obligatorios.</div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</body>

</html>