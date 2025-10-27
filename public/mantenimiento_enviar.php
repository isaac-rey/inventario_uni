<?php
require __DIR__ . '/../init.php';
require_login();

// Obtener id del reporte (no solo del equipo)
$reporte_id = intval($_GET['reporte_id'] ?? 0);
if (!$reporte_id) die("Reporte no especificado.");

// Obtener info del reporte y equipo
$stmt = $mysqli->prepare("SELECT r.*, e.id AS equipo_id, e.marca, e.modelo, e.tipo, e.serial_interno 
                          FROM reporte_fallos r
                          JOIN equipos e ON e.id = r.id_equipo
                          WHERE r.id = ?");
$stmt->bind_param("i", $reporte_id);
$stmt->execute();
$reporte = $stmt->get_result()->fetch_assoc();
if (!$reporte) die("Reporte no encontrado.");

$ok = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destino = trim($_POST['destino']);
    $motivo = trim($_POST['motivo']);
    $usuario_id = $_SESSION['user']['id']; // id del usuario logueado
    $fecha_envio = date('Y-m-d'); // fecha automática de envío

    if (!$destino || !$motivo) {
        $error = "Destino y motivo son obligatorios.";
    } else {
        // 1. Marcar equipo en mantenimiento
        $stmt1 = $mysqli->prepare("UPDATE equipos SET en_mantenimiento=1, estado='no_disponible' WHERE id=?");
        $stmt1->bind_param("i", $reporte['equipo_id']);
        $stmt1->execute();

        // 2. Insertar registro en la tabla mantenimientos vinculado al reporte
        $stmt2 = $mysqli->prepare("
            INSERT INTO mantenimientos (equipo_id, reporte_id, destino, motivo, fecha_envio, usuario_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt2->bind_param("iisssi", $reporte['equipo_id'], $reporte_id, $destino, $motivo, $fecha_envio, $usuario_id);

        if ($stmt2->execute()) {
            $ok = true;

            //-------------------INSERCIÓN DE LA AUDITORÍA-----------------------
            $equipo_desc = htmlspecialchars($reporte['tipo'] . ' ' . $reporte['marca'] . ' (Serial: ' . $reporte['serial_interno'] . ')');
            $accion_msg = "Envió el Equipo ID {$reporte['equipo_id']} ({$equipo_desc}) a mantenimiento. Destino: {$destino}. Motivo: {$motivo}.";
            // El ID del usuario que realiza la acción se toma de la sesión (user())
            auditar($accion_msg);
            // --------------------------------

        } else {
            $error = "No se pudo registrar el mantenimiento: " . $mysqli->error;
        }
    }
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Enviar a mantenimiento</title>
    <link rel="stylesheet" href="../css/form_mantenimiento_enviar.css">
</head>

<body>
    <?php include __DIR__ . '/navbar.php'; ?>

    <div class="card">
        <h1>Enviar equipo a mantenimiento</h1>

        <?php if ($ok): ?>
            <div class="ok">✔ Equipo enviado a mantenimiento correctamente.</div>
            <div class="muted"><a href="reportes.php">← Volver a reportes</a></div>
        <?php else: ?>
            <?php if ($error) echo '<div class="error">' . htmlspecialchars($error) . '</div>'; ?>

            <p class="muted">
                <strong>Equipo:</strong> <?= htmlspecialchars($reporte['marca'] . ' ' . $reporte['modelo']) ?> <br>
                <strong>Serial:</strong> <?= htmlspecialchars($reporte['serial_interno']) ?>
            </p>

            <form method="post">
                <label for="destino">Destino *</label>
                <input id="destino" type="text" name="destino" required>

                <label for="motivo">Motivo *</label>
                <textarea id="motivo" name="motivo" required rows="4"></textarea>

                <button type="submit">Guardar</button>
            </form>
            <div class="muted">
                <a href="mantenimientos.php">← Volver a mantenimientos</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>