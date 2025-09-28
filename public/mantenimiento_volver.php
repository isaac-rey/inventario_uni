<?php
require __DIR__ . '/../config/db.php';
include __DIR__ . '/navbar.php';

$id = intval($_GET['id'] ?? 0);
$error = null;
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fecha_devolucion = $_POST['fecha_devolucion'];
  $solucionado = intval($_POST['solucionado']);

  if ($solucionado === 1) {
    $estado = 'disponible';
    $con_reporte = 0; // limpiamos reporte
  } else {
    $estado = 'no_disponible';
    $con_reporte = 1; // sigue marcado con reporte
  }

  $stmt = $mysqli->prepare("
      UPDATE equipos
      SET en_mantenimiento=0, estado='Disponible', con_reporte=?
      WHERE id=?");
  $stmt->bind_param("ii", $con_reporte, $id);
  $stmt->execute();

  $stmt = $mysqli->prepare("
    INSERT INTO mantenimientos (equipo_id, fecha_devolucion, solucionado)
    VALUES (?, ?, ?)");
  $stmt->bind_param("iss", $id, $fecha_devolucion, $solucionado);



  if ($stmt->execute()) {
    $ok = true;
  } else {
    $error = "Hubo un problema al actualizar el equipo.";
  }
}
?>

<head>
  <meta charset="utf-8">
  <title>Finalizar mantenimiento</title>
  <link rel="stylesheet" href="../css/form_mantenimiento_enviar.css">
</head>

<body>
  <div class="card">
    <h1>Finalizar mantenimiento</h1>

    <?php if ($ok): ?>
      <div class="ok">El mantenimiento fue registrado correctamente.</div>
      <div class="muted"><a href="reportes.php">Volver a la lista de reportes</a></div>
    <?php else: ?>
      <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

      <form method="post" autocomplete="off">
        <div>
          <label for="fecha_devolucion">Fecha de devolución:</label>
          <input type="date" id="fecha_devolucion" name="fecha_devolucion" required>
        </div>

        <div>
          <label for="solucionado">¿Se solucionó?</label>
          <select id="solucionado" name="solucionado" required>
            <option value="1">Sí</option>
            <option value="0">No</option>
          </select>
        </div>

        <button type="submit">Guardar</button>
      </form>
      <div class="muted">Si no se solucionó, el equipo seguirá marcado con reporte.</div>
    <?php endif; ?>
  </div>
</body>