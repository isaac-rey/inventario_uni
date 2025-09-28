<?php
require_once '../init.php';
require_login();

$db = db();
$user = user();
$rol = $user['rol'];

// Obtener préstamos pendientes de retiro del usuario actual
$stmt = $db->prepare("SELECT p.id, e.nombre AS equipo, p.fecha_prestamo, p.estado 
                      FROM prestamos p 
                      JOIN equipos e ON p.equipo_id = e.id 
                      WHERE p.usuario_id = ? AND p.estado = 'pendiente'");
$stmt->execute([$user['id']]);
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Procesar solicitud de cancelación
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prestamo_id'], $_POST['motivo'])) {
    $prestamo_id = intval($_POST['prestamo_id']);
    $motivo = trim($_POST['motivo']);

    // Registrar la solicitud de cancelación
    $stmt = $db->prepare("UPDATE prestamos SET estado = 'cancelacion_pendiente', motivo_cancelacion = ? WHERE id = ? AND usuario_id = ?");
    $stmt->execute([$motivo, $prestamo_id, $user['id']]);
    $msg = "Solicitud de cancelación enviada. Espera la validación del titular de área.";
    // Opcional: notificar al titular de área por email
}

// Si es titular de área, mostrar cancelaciones pendientes para validar
$cancelaciones = [];
if ($rol === 'admin' || $rol === 'titular_area') {
    $stmt = $db->query("SELECT p.id, e.nombre AS equipo, u.nombre AS solicitante, p.motivo_cancelacion, p.fecha_prestamo
                        FROM prestamos p
                        JOIN equipos e ON p.equipo_id = e.id
                        JOIN usuarios u ON p.usuario_id = u.id
                        WHERE p.estado = 'cancelacion_pendiente'");
    $cancelaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Procesar validación
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aprobar_id'])) {
        $id = intval($_POST['aprobar_id']);
        // Cambiar estado a cancelado y liberar equipo
        $db->prepare("UPDATE prestamos SET estado = 'cancelado' WHERE id = ?")->execute([$id]);
        $db->prepare("UPDATE equipos SET estado = 'disponible' WHERE id = (SELECT equipo_id FROM prestamos WHERE id = ?)")->execute([$id]);
        $msg = "Cancelación aprobada y equipo liberado.";
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rechazar_id'])) {
        $id = intval($_POST['rechazar_id']);
        // Rechazar cancelación
        $db->prepare("UPDATE prestamos SET estado = 'pendiente', motivo_cancelacion = NULL WHERE id = ?")->execute([$id]);
        $msg = "Cancelación rechazada.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cancelar Préstamo — Inventario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
        .container{max-width:700px;margin:32px auto;padding:24px;background:#111827;border-radius:12px}
        h2{margin-top:0}
        table{width:100%;border-collapse:collapse;margin-bottom:24px}
        th,td{padding:8px;border-bottom:1px solid #222}
        th{background:#1f2937}
        .msg{background:#1e293b;padding:12px;border-radius:8px;margin-bottom:16px}
        textarea{width:100%;min-height:60px}
        button{background:#2563eb;color:#fff;padding:8px 16px;border:none;border-radius:6px;cursor:pointer}
        button:hover{background:#1d4ed8}
        .acciones form{display:inline}
    </style>
</head>
<body>
<div class="container">
    <h2>Cancelar Préstamo</h2>
    <?php if (!empty($msg)): ?>
        <div class="msg"><?=htmlspecialchars($msg)?></div>
    <?php endif; ?>

    <?php if ($rol !== 'admin' && $rol !== 'titular_area'): ?>
        <h3>Mis préstamos pendientes de retiro</h3>
        <?php if ($prestamos): ?>
            <table>
                <tr>
                    <th>Equipo</th>
                    <th>Fecha de Préstamo</th>
                    <th>Estado</th>
                    <th>Cancelar</th>
                </tr>
                <?php foreach ($prestamos as $p): ?>
                    <tr>
                        <td><?=htmlspecialchars($p['equipo'])?></td>
                        <td><?=htmlspecialchars($p['fecha_prestamo'])?></td>
                        <td><?=htmlspecialchars($p['estado'])?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('¿Seguro que deseas solicitar la cancelación?');">
                                <input type="hidden" name="prestamo_id" value="<?=$p['id']?>">
                                <textarea name="motivo" required placeholder="Motivo de la cancelación"></textarea>
                                <button type="submit">Solicitar cancelación</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No tienes préstamos pendientes de retiro.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($rol === 'admin' || $rol === 'titular_area'): ?>
        <h3>Solicitudes de cancelación pendientes</h3>
        <?php if ($cancelaciones): ?>
            <table>
                <tr>
                    <th>Equipo</th>
                    <th>Solicitante</th>
                    <th>Motivo</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach ($cancelaciones as $c): ?>
                    <tr>
                        <td><?=htmlspecialchars($c['equipo'])?></td>
                        <td><?=htmlspecialchars($c['solicitante'])?></td>
                        <td><?=htmlspecialchars($c['motivo_cancelacion'])?></td>
                        <td><?=htmlspecialchars($c['fecha_prestamo'])?></td>
                        <td class="acciones">
                            <form method="post" style="display:inline">
                                <input type="hidden" name="aprobar_id" value="<?=$c['id']?>">
                                <button type="submit">Aprobar</button>
                            </form>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="rechazar_id" value="<?=$c['id']?>">
                                <button type="submit" style="background:#b91c1c">Rechazar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No hay solicitudes de cancelación pendientes.</p>
        <?php endif; ?>
    <?php endif; ?>
    <a href="../index.php" style="color:#93c5fd">← Volver al panel</a>
</div>
</body>
</html>