<?php
require __DIR__ . '/../config/db.php';

// Consultar todos los mantenimientos con datos del equipo
$sql = "SELECT m.id AS mantenimiento_id, m.equipo_id, m.destino, m.motivo, 
               m.fecha_envio, m.fecha_devolucion, m.solucionado, m.observaciones,
               e.marca, e.modelo, e.estado
        FROM mantenimientos m
        LEFT JOIN equipos e ON e.id = m.equipo_id
        ORDER BY m.fecha_envio DESC";

$res = $mysqli->query($sql);
$mantenimientos = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Historial de Mantenimientos</title>
    <link rel="stylesheet" href="../css/tabla_mantenimientos.css">
       
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<div class="container">
    <h1>Historial de Mantenimientos</h1>

    <?php if (!$mantenimientos): ?>
        <p class="muted">No hay registros de mantenimientos.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Equipo</th>
                    <th>Estado Equipo</th>
                    <th>Destino</th>
                    <th>Motivo</th>
                    <th>Fecha Envío</th>
                    <th>Fecha Devolución</th>
                    <th>Solucionado</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mantenimientos as $m): ?>
                <tr>
                    <td><?= $m['mantenimiento_id'] ?></td>
                    <td><?= htmlspecialchars($m['marca'] . ' ' . $m['modelo']) ?></td>
                    <td><?= htmlspecialchars($m['estado']) ?></td>
                    <td><?= htmlspecialchars($m['destino']) ?></td>
                    <td><?= htmlspecialchars($m['motivo']) ?></td>
                    <td><?= $m['fecha_envio'] ?></td>
                    <td><?= $m['fecha_devolucion'] ?? '<span class="muted">No devuelto</span>' ?></td>
                    <td class="<?= $m['solucionado'] ? 'status-active' : 'status-returned' ?>">
                        <?= $m['solucionado'] ? 'Sí' : 'No' ?>
                    </td>
                    <td><?= htmlspecialchars($m['observaciones'] ?? '-') ?></td>
    
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
