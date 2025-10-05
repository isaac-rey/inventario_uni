<?php
require __DIR__ . '/../init.php';
require_login();

$error = '';
$ok = false;
$rol = user()['rol'];

// Obtener áreas según rol
if ($rol === 'admin') {
  $areas = $mysqli->query("SELECT id, nombre FROM areas ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
} else {
  $areas = $mysqli->query("SELECT id, nombre FROM areas WHERE id=1")->fetch_all(MYSQLI_ASSOC);
}

// Insertar equipo
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
    $serial = bin2hex(random_bytes(6));

    $stmt = $mysqli->prepare("INSERT INTO equipos (area_id, sala_id, tipo, marca, modelo, serial_interno, estado) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("iisssss", $area_id, $sala_id, $tipo, $marca, $modelo, $serial, $estado);
    if ($stmt->execute()) {
      //-----------------------insersion de la auditoria-----------------------
      $nuevo_equipo_id = $mysqli->insert_id;

      // Creamos un mensaje descriptivo
      $descripcion = trim("$tipo $marca $modelo");

      auditar("Registró un nuevo equipo (ID {$nuevo_equipo_id}) con Serial {$serial}: {$descripcion}");

      // -----------------------------

      $ok = true;
      // Redirigir si quieres, o mostrar mensaje:
      // header("Location: /inventario_uni/public/equipos_index.php"); exit;
    } else {
      $error = "Error al guardar el equipo.";
    }
  }
}
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Nuevo equipo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/form_equipos_nuevo.css">
</head>

<body>
  <?php include __DIR__ . '/navbar.php'; ?>

  <div class="container">
    <div class="card">
      <h1>Nuevo equipo</h1>



      <form method="post">
        <div>
          <label for="area_id">Área</label>
          <select name="area_id" id="area_id" required>
            <?php foreach ($areas as $a): ?>
              <option value="<?= $a['id'] ?>"><?= $a['nombre'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="sala_id">Sala (opcional)</label>
          <select name="sala_id" id="sala_id">
            <option value="">-- Ninguna --</option>
            <?php
            $salas = $mysqli->query("SELECT id, nombre FROM salas ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
            foreach ($salas as $s): ?>
              <option value="<?= $s['id'] ?>"><?= $s['nombre'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="tipo">Tipo de equipo</label>
          <input type="text" name="tipo" id="tipo" placeholder="Proyector, PC, TV, etc." required>
        </div>

        <div>
          <label for="marca">Marca</label>
          <input type="text" name="marca" id="marca">
        </div>

        <div>
          <label for="modelo">Modelo</label>
          <input type="text" name="modelo" id="modelo">
        </div>

        <div>
          <label for="estado">Estado</label>
          <select name="estado" id="estado">
            <option value="Disponible">Bueno</option>
            <option value="en_uso">En uso</option>
            <option value="dañado">Dañado</option>
            <option value="fuera_servicio">Fuera de servicio</option>
          </select>
        </div>

        <button type="submit">Guardar equipo</button>
      </form>
    </div>
  </div>
</body>

</html>