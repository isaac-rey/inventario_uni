<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';
require_login();

// Traemos reportes junto con estado de mantenimiento
$sql = "SELECT r.id AS reporte_id, r.fecha, r.tipo_fallo, r.descripcion_fallo, r.nombre_usuario_reportante,
               e.id AS equipo_id, e.marca, e.modelo, e.tipo, e.serial_interno,
               -- mantenimiento en curso
               (SELECT COUNT(*) FROM mantenimientos m WHERE m.equipo_id = e.id AND m.fecha_devolucion IS NULL) AS en_mantenimiento,
               -- devoluciones realizadas
               (SELECT COUNT(*) FROM mantenimientos m WHERE m.equipo_id = e.id AND m.fecha_devolucion IS NOT NULL) AS devoluciones
        FROM reporte_fallos r
        LEFT JOIN equipos e ON e.id = r.id_equipo
        ORDER BY r.fecha DESC";

$res = $mysqli->query($sql);
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Fallos</title>
    <link rel="stylesheet" href="../css/tabla_equipos_index.css">
    <style>
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-danger {
            background: #e53e3e;
        }
        .muted {
            color: #718096;
            font-style: italic;
            text-align: center;
        }
        .status-enviado { color: #d69e2e; font-weight: 600; } /* amarillo */
        .status-devuelto { color: #38a169; font-weight: 600; } /* verde */
    </style>
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>

<div class="container">
    <h1>Reportes de Fallos</h1>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Equipo</th>
                <th>Serial</th>
                <th>Tipo de fallo</th>
                <th>Descripción</th>
                <th>Reportado por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!$rows): ?>
            <tr>
                <td colspan="7" class="muted">No existen reportes aún.</td>
            </tr>
        <?php else: foreach ($rows as $r): ?>
            <tr>
                <td data-label="Fecha"><?= htmlspecialchars($r['fecha']) ?></td>
                <td data-label="Equipo"><?= htmlspecialchars($r['marca'] . ' ' . $r['modelo'] . ' (' . $r['tipo'] . ')') ?></td>
                <td data-label="Serial"><?= htmlspecialchars($r['serial_interno']) ?></td>
                <td data-label="Tipo de fallo"><?= htmlspecialchars($r['tipo_fallo']) ?></td>
                <td data-label="Descripción"><?= nl2br(htmlspecialchars($r['descripcion_fallo'])) ?></td>
                <td data-label="Reportado por"><?= htmlspecialchars($r['nombre_usuario_reportante']) ?></td>
                <td data-label="Acciones">
                    <?php if ($r['devoluciones'] > 0): ?>
                        <span class="status-devuelto">Volvió del mantenimiento</span>
                    <?php elseif ($r['en_mantenimiento'] > 0): ?>
                        <span class="status-enviado">Enviado a mantenimiento</span>
                        <a href="mantenimiento_volver.php?id=<?= $r['equipo_id'] ?>" class="btn">Volvió del mantenimiento</a>
                    <?php else: ?>
                        <a href="mantenimiento_enviar.php?id=<?= $r['equipo_id'] ?>" class="btn">Enviar a mantenimiento</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
