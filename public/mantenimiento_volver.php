<?php
//require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';
require_login();

$reporte_id = intval($_GET['reporte_id'] ?? 0);
 
if (!$reporte_id) die("Mantenimiento no especificado.");

// Obtener info del mantenimiento
//$stmt = $mysqli->prepare("SELECT * FROM mantenimientos WHERE reporte_id = ?");
//--------------------------------
$stmt = $mysqli->prepare("
    SELECT m.*, e.serial_interno
    FROM mantenimientos m
    JOIN equipos e ON e.id = m.equipo_id
    WHERE m.reporte_id = ?
");
//--------------------------------
$stmt->bind_param("i", $reporte_id);
$stmt->execute();
$mantenimiento = $stmt->get_result()->fetch_assoc();

if (!$mantenimiento) die("Mantenimiento no encontrado.");

$ok = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fecha_devolucion = $_POST['fecha_devolucion'];
  $solucionado = intval($_POST['solucionado']);

  $stmt = $mysqli->prepare("UPDATE mantenimientos SET fecha_devolucion = ?, solucionado = ? WHERE reporte_id = ?");
  $stmt->bind_param("sii", $fecha_devolucion, $solucionado, $reporte_id);

  if ($stmt->execute()) {
    // actualizar estado del equipo
    $estado = $solucionado ? 'Disponible' : 'No disponible';
    $con_reporte = $solucionado ? 0 : 1;
    $mysqli->query("UPDATE equipos SET en_mantenimiento=0, estado='$estado', con_reporte=$con_reporte WHERE id=" . $mantenimiento['equipo_id']);
    $ok = true;

//--------------------INSERCIÓN DE LA AUDITORÍA---------------------
    $resultado = $solucionado ? 'SOLUCIONADO y devuelto' : 'DEVUELTO SIN SOLUCIONAR';
    $accion_msg = "Finalizó el mantenimiento del Equipo ID {$mantenimiento['equipo_id']} (Serial: {$mantenimiento['serial_interno']}). Resultado: {$resultado}.";
    auditar($accion_msg);
    // ---------------------------------------------------------------

  } else {
    $error = "No se pudo actualizar el mantenimiento.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Finalizar mantenimiento</title>
  <link rel="stylesheet" href="../css/form_mantenimiento_enviar.css">
</head>

<body>
  <?php include __DIR__ . '/navbar.php'; ?>
  <div class="container">
    <div class="card">
      <h2>Finalizar mantenimiento</h2>

      <?php if ($ok): ?>
        <div class="ok">✔ Mantenimiento actualizado correctamente.</div>
        <div class="muted"><a href="reportes.php">Volver a reportes</a></div>
      <?php else: ?>
        <?php if ($error) echo '<div class="error">' . htmlspecialchars($error) . '</div>'; ?>

        <form method="post">
          <label for="fecha_devolucion">Fecha de devolución *</label>
          <input type="date" name="fecha_devolucion" id="fecha_devolucion" required>

          <label for="solucionado">¿Se solucionó?</label>
          <select name="solucionado" id="solucionado">
            <option value="1">Sí</option>
            <option value="0">No</option>
          </select>

          <button type="submit">Guardar</button>
        </form>
        <div class="muted">Si no se solucionó, el equipo seguirá marcado con reporte.</div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>