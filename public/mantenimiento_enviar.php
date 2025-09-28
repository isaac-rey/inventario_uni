<?php
require __DIR__ . '/../init.php';
require_login();

$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $destino = trim($_POST['destino']);
    $fecha_envio = $_POST['fecha_envio'];
    $motivo = trim($_POST['motivo']);
    $usuario_id = $_SESSION['user_id']; // suponiendo que guardás el id del usuario logueado

    // 1. Marcar equipo en mantenimiento
    $stmt = $mysqli->prepare("
        UPDATE equipos
        SET en_mantenimiento=1, con_reporte=1, estado='no_disponible'
        WHERE id=?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // 2. Insertar registro en la tabla mantenimientos
    $stmt2 = $mysqli->prepare("
        INSERT INTO mantenimientos (equipo_id, destino, motivo, fecha_envio, usuario_id)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt2->bind_param("isssi", $id, $destino, $motivo, $fecha_envio, $usuario_id);
    $stmt2->execute();

    header("Location: reportes.php");
    exit;
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

        <form method="post">
            <label for="destino">Destino:</label>
            <input id="destino" type="text" name="destino" required>

            <label for="fecha_envio">Fecha de envío:</label>
            <input id="fecha_envio" type="date" name="fecha_envio" required>

            <label for="motivo">Motivo:</label>
            <textarea id="motivo" name="motivo" required rows="4"></textarea>

            <button type="submit">Guardar</button>
        </form>

        <div class="muted">
            <a href="mantenimientos.php">← Volver a mantenimientos</a>
        </div>
    </div>
</body>
</html>

