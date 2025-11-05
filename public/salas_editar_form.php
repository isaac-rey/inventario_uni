<?php
// public/salas_editar_form.php
require __DIR__ . '/../init.php';
require_login();

$id = intval($_GET['id'] ?? 0);
if (!$id) {
  die("Sala no especificada.");
}

// Obtener datos actuales de la sala
$stmt = $mysqli->prepare("
  SELECT s.*, a.nombre AS area_nombre 
  FROM salas s
  INNER JOIN areas a ON s.area_id = a.id
  WHERE s.id=? LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$sala = $stmt->get_result()->fetch_assoc();
if (!$sala) {
  die("Sala no encontrada.");
}

// Obtener todas las áreas para el combo
$areas = $mysqli->query("SELECT id, nombre FROM areas ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);

$ok = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre      = trim($_POST['nombre'] ?? '');
  $descripcion = trim($_POST['descripcion'] ?? '');
  $area_id     = intval($_POST['area_id'] ?? 0);
  $nombre_area = htmlspecialchars($sala['area_nombre']);

  if ($nombre === '' || !$area_id) {
    $error = 'El nombre y el área son obligatorios.';
  } else {
    // Verificar si ya existe otra sala con el mismo nombre en la misma área
    $stmt = $mysqli->prepare("SELECT id FROM salas WHERE nombre=? AND area_id=? AND id<>? LIMIT 1");
    $stmt->bind_param('sii', $nombre, $area_id, $id);
    $stmt->execute();
    $existe = $stmt->get_result()->fetch_assoc();

    if ($existe) {
      $error = 'Ya existe otra sala con ese nombre en la misma área.';
    } else {
      // Actualizar la sala
      $stmt = $mysqli->prepare("UPDATE salas SET nombre=?, descripcion=?, area_id=? WHERE id=?");
      $stmt->bind_param('ssii', $nombre, $descripcion, $area_id, $id);
      $stmt->execute();
      $ok = true;

      // ------- AUDITORÍA -------
      $accion_msg = "Editó la sala ID {$id} ({$nombre}) del área '{$nombre_area}'.";
      auditar($accion_msg, 'acción_sala');
      // --------------------------
    }
  }
}
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Editar Sala</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/form_equipos_editar.css">
</head>

<body>
  <?php include __DIR__ . '/navbar.php'; ?>
  <div class="card">
    <h1>Editar Sala</h1>

    <?php if ($ok): ?>
      <div class="ok">Sala actualizada correctamente.</div>
      <div class="muted"><a href="/inventario_uni/public/salas.php">Volver a la lista</a></div>
    <?php else: ?>
      <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post" autocomplete="off">
        <label>Área</label>
        <select name="area_id" required>
          <option value="">Seleccionar área</option>
          <?php foreach ($areas as $a): ?>
            <option value="<?= $a['id'] ?>" <?= ($a['id'] == $sala['area_id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($a['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <label>Nombre de la Sala</label>
        <input name="nombre" type="text" required value="<?= htmlspecialchars($sala['nombre']) ?>">

        <label>Descripción (opcional)</label>
        <textarea name="descripcion" rows="3" placeholder="Descripción breve..."><?= htmlspecialchars($sala['descripcion']) ?></textarea>

        <button type="submit">Guardar cambios</button>
      </form>
    <?php endif; ?>
  </div>
</body>

</html>