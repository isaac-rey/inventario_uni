<?php
// public/equipos_editar.php
require __DIR__ . '/../init.php';
require_login();

$rol = user()['rol'];
$id = intval($_GET['id'] ?? 0);
if (!$id) {
  die("ID no especificado.");
}

// Cargar equipo
$stmt = $mysqli->prepare("
  SELECT e.*, a.nombre AS area_nombre, s.nombre AS sala_nombre
  FROM equipos e
  JOIN areas a ON a.id = e.area_id
  LEFT JOIN salas s ON s.id = e.sala_id
  WHERE e.id = ?
  LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) {
  die("Equipo no encontrado.");
}

// Si no es admin, limitar edición a su área (Biblioteca id=1)
if ($rol !== 'admin' && intval($equipo['area_id']) !== 1) {
  http_response_code(403);
  die("Acceso denegado para editar equipos fuera de tu área.");
}

// Listar áreas (admin ve todas; bibliotecaria solo Biblioteca)
if ($rol === 'admin') {
  $areas = $mysqli->query("SELECT id, nombre FROM areas ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
} else {
  $areas = $mysqli->query("SELECT id, nombre FROM areas WHERE id=1")->fetch_all(MYSQLI_ASSOC);
}

// Listar salas (todas para elegir; en mejora futura podemos filtrar por área elegida vía JS)
$salas = $mysqli->query("SELECT id, nombre FROM salas ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);

$error = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $area_id = intval($_POST['area_id']);
  $sala_id = $_POST['sala_id'] !== '' ? intval($_POST['sala_id']) : null;
  $tipo    = trim($_POST['tipo']);
  $marca   = trim($_POST['marca']);
  $modelo  = trim($_POST['modelo']);
  $estado  = $_POST['estado'];

  if ($tipo === '') {
    $error = "El campo tipo es obligatorio.";
  } else {
    // Si no es admin, forzar área=1 por seguridad
    if ($rol !== 'admin') {
      $area_id = 1;
    }

    $stmt = $mysqli->prepare("
      UPDATE equipos
      SET area_id=?, sala_id=?, tipo=?, marca=?, modelo=?, estado=?
      WHERE id=?
      LIMIT 1
    ");
    // sala_id puede ser NULL -> usar 'i' y pasar null con bind_param requiere cuidado:
    // truco: si $sala_id es null, usar null; mysqli lo envía como 0 si no seteamos types-> usamos set de types y values normal.
    $stmt->bind_param("iissssi", $area_id, $sala_id, $tipo, $marca, $modelo, $estado, $id);


    //--------------------insersion de auditoria-----------------------
    if ($stmt->execute()){
      $descripcion = trim("$tipo $marca $modelo");  
      auditar("Editó el equipo (ID {$id}) {$descripcion}.");

    $ok = true;
    } else {
      $error = "Error al guardar los cambios: " . $mysqli->error;
    } 
    // -------------------------------------------------------------

    // Recargar datos actualizados
    $stmt = $mysqli->prepare("
      SELECT e.*, a.nombre AS area_nombre, s.nombre AS sala_nombre
      FROM equipos e
      JOIN areas a ON a.id = e.area_id
      LEFT JOIN salas s ON s.id = e.sala_id
      WHERE e.id = ?
      LIMIT 1
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc();
  }
}
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Editar equipo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/form_equipos_editar.css">
</head>

<body>
  <?php
  include __DIR__ . '/navbar.php';
  ?>

  <div class="container form-container">
    <div class="card">
      <h1>Editar equipo</h1>

      <?php if ($ok): ?>
        <div class="ok">Cambios guardados.</div>
      <?php endif; ?>
      <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="row">
          <!-- Área -->
          <div class="col">
            <label for="area_id">Área</label>
            <select name="area_id" id="area_id" required <?= $rol !== 'admin' ? 'disabled' : '' ?>>
              <?php foreach ($areas as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $a['id'] == $equipo['area_id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($a['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if ($rol !== 'admin'): ?>
              <input type="hidden" name="area_id" value="<?= $equipo['area_id'] ?>">
            <?php endif; ?>
          </div>

          <!-- Sala -->
          <div class="col">
            <label for="sala_id">Sala (opcional)</label>
            <select name="sala_id" id="sala_id">
              <option value="">-- Ninguna --</option>
              <?php foreach ($salas as $s): ?>
                <option value="<?= $s['id'] ?>" <?= ($equipo['sala_id'] ?? null) == $s['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($s['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row">
          <!-- Tipo -->
          <div class="col">
            <label for="tipo">Tipo</label>
            <input type="text" name="tipo" id="tipo" value="<?= htmlspecialchars($equipo['tipo']) ?>" required>
          </div>

          <!-- Estado -->
          <div class="col">
            <label for="estado">Estado</label>
            <select name="estado" id="estado">
              <?php
              $estados = ['bueno', 'en_uso', 'dañado', 'fuera_servicio'];
              foreach ($estados as $e) {
                $sel = $equipo['estado'] === $e ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($e) . "\" $sel>" . htmlspecialchars($e) . "</option>";
              }
              ?>
            </select>
          </div>
        </div>

        <div class="row">
          <!-- Marca -->
          <div class="col">
            <label for="marca">Marca</label>
            <input type="text" name="marca" id="marca" value="<?= htmlspecialchars($equipo['marca']) ?>">
          </div>

          <!-- Modelo -->
          <div class="col">
            <label for="modelo">Modelo</label>
            <input type="text" name="modelo" id="modelo" value="<?= htmlspecialchars($equipo['modelo']) ?>">
          </div>
        </div>

        <!-- Serial interno (solo lectura) -->
        <div class="row">
          <div class="col-full">
            <label class="muted" for="serial_interno">Serial interno (solo lectura)</label>
            <input type="text" id="serial_interno" value="<?= htmlspecialchars($equipo['serial_interno']) ?>" disabled>
          </div>
        </div>

        <button type="submit" class="mt-2">Guardar cambios</button>
      </form>
    </div>
  </div>

</body>

</html>