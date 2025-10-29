<?php
require __DIR__ . '/../init.php';
require_login();

// Parámetros
$reporte_id = intval($_GET['reporte_id'] ?? 0);
$equipo_id  = intval($_GET['id_equipo'] ?? 0);

if (!$reporte_id && !$equipo_id) die("Ni reporte ni equipo especificado.");

// Primero, buscamos mantenimiento activo
if ($reporte_id) {
    $stmt = $mysqli->prepare("
        SELECT m.*, e.serial_interno
        FROM mantenimientos m
        JOIN equipos e ON e.id = m.equipo_id
        WHERE m.reporte_id = ?
    ");
    $stmt->bind_param("i", $reporte_id);
} else {
    $stmt = $mysqli->prepare("
        SELECT m.*, e.serial_interno
        FROM mantenimientos m
        JOIN equipos e ON e.id = m.equipo_id
        WHERE m.equipo_id = ? AND m.fecha_devolucion IS NULL
        ORDER BY m.id DESC
        LIMIT 1
    ");
    $stmt->bind_param("i", $equipo_id);
}

$stmt->execute();
$mantenimiento = $stmt->get_result()->fetch_assoc();

// Si no existe mantenimiento activo, creamos uno “temporal” para poder finalizarlo
if (!$mantenimiento) {
    if (!$equipo_id && $reporte_id) {
        // Obtener equipo desde reporte
        $stmt2 = $mysqli->prepare("SELECT id AS equipo_id, serial_interno FROM equipos e JOIN reporte_fallos r ON e.id=r.id_equipo WHERE r.id=?");
        $stmt2->bind_param("i", $reporte_id);
        $stmt2->execute();
        $equipo = $stmt2->get_result()->fetch_assoc();
        $equipo_id = $equipo['equipo_id'];
        $serial = $equipo['serial_interno'];
    } else {
        $stmt2 = $mysqli->prepare("SELECT serial_interno FROM equipos WHERE id=?");
        $stmt2->bind_param("i", $equipo_id);
        $stmt2->execute();
        $equipo = $stmt2->get_result()->fetch_assoc();
        $serial = $equipo['serial_interno'];
    }

    // Crear mantenimiento temporal
    $stmt3 = $mysqli->prepare("INSERT INTO mantenimientos (equipo_id, reporte_id, fecha_envio, usuario_id) VALUES (?, ?, NOW(), ?)");
    $user_id = $_SESSION['user']['id'];
    $null_reporte = null;
    $stmt3->bind_param("iii", $equipo_id, $null_reporte, $user_id);
    $stmt3->execute();
    $mantenimiento_id = $stmt3->insert_id;

    $mantenimiento = [
        'id' => $mantenimiento_id,
        'equipo_id' => $equipo_id,
        'serial_interno' => $serial
    ];
}

// El resto sigue igual: POST para finalizar mantenimiento
$ok = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_devolucion = $_POST['fecha_devolucion'];
    $solucionado = intval($_POST['solucionado']);

    $stmt = $mysqli->prepare("UPDATE mantenimientos SET fecha_devolucion = ?, solucionado = ? WHERE id = ?");
    $stmt->bind_param("sii", $fecha_devolucion, $solucionado, $mantenimiento['id']);
    if ($stmt->execute()) {
        if ($solucionado) {
            $mysqli->query("UPDATE equipos SET en_mantenimiento=0, estado='Disponible', con_reporte=0, con_fallos=0 WHERE id=".$mantenimiento['equipo_id']);
        } else {
            $mysqli->query("UPDATE equipos SET en_mantenimiento=0, estado='Con fallos', con_reporte=1, con_fallos=1 WHERE id=".$mantenimiento['equipo_id']);
        }

        $ok = true;
        $accion_msg = "Finalizó el mantenimiento del Equipo ID {$mantenimiento['equipo_id']} (Serial: {$mantenimiento['serial_interno']}). Resultado: ".($solucionado?'SOLUCIONADO y devuelto':'DEVUELTO SIN SOLUCIONAR');
        auditar($accion_msg,'mantenimiento');
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
