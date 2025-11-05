<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';
require_login();

// Traemos reportes junto con estado de mantenimiento
$sql = "SELECT 
    r.id AS reporte_id,
    r.fecha,
    r.tipo_fallo,
    r.descripcion_fallo,
    r.nombre_usuario_reportante,
    e.id AS equipo_id,
    e.marca,
    e.modelo,
    e.tipo,
    e.serial_interno,
    e.estado,
    -- mantenimiento en curso solo para este reporte
    (SELECT COUNT(*) 
     FROM mantenimientos m 
     WHERE m.reporte_id = r.id AND m.fecha_devolucion IS NULL) AS en_mantenimiento,
    -- devoluciones realizadas solo para este reporte
    (SELECT COUNT(*) 
     FROM mantenimientos m 
     WHERE m.reporte_id = r.id AND m.fecha_devolucion IS NOT NULL) AS devoluciones
FROM reporte_fallos r
LEFT JOIN equipos e ON e.id = r.id_equipo
ORDER BY r.fecha DESC
";

$res = $mysqli->query($sql);
$rows = $res && $res->num_rows > 0 ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Historial de Reportes</title>
    <link rel="stylesheet" href="../css/tabla_usuarios.css">
    <style>
        .muted {
            color: #718096;
            font-style: italic;
            text-align: center;
            margin-top: 1rem;
        }

        .status-enviado {
            color: #d69e2e;
            font-weight: 600;
            display: block; 
        }

        /* amarillo */
        .status-devuelto {
            color: #38a169;
            font-weight: 600;
            display: block; /* Asegurar que ocupe toda la línea */
        }

        /* verde */
        .btn {
            background: linear-gradient(135deg, #1e3c72 , #2a5298 );
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
            box-shadow: 0 4px 15px rgba(7, 45, 218, 0.4);
        }

        /* ESTILO APLICADO PARA SOLUCIONAR EL PROBLEMA DE ALINEACIÓN */
        /* Muestra los elementos en columna y les da espacio. */
        .actions-cell {
            display: flex;
            flex-direction: column;
            align-items: center; /* Centrar horizontalmente */
            gap: 0.5rem; /* Espacio entre el estado y el botón */
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <div class="container">
        <h1>Historial de Reportes</h1>

        <?php if (!$rows): ?>
            <p class="muted">No existen reportes aún.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Equipo</th>
                        <th>Estado Equipo</th>
                        <th>Serial</th>
                        <th>Tipo de fallo</th>
                        <th>Descripción</th>
                        <th>Reportado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r): ?>
                        <tr>
                            <td><?= $r['reporte_id'] ?></td>
                            <td><?= htmlspecialchars($r['fecha']) ?></td>
                            <td><?= htmlspecialchars($r['marca'] . ' ' . $r['modelo'] . ' (' . $r['tipo'] . ')') ?></td>
                            <td><?= htmlspecialchars($r['estado']) ?></td>
                            <td><?= htmlspecialchars($r['serial_interno']) ?></td>
                            <td><?= htmlspecialchars($r['tipo_fallo']) ?></td>
                            <td><?= nl2br(htmlspecialchars($r['descripcion_fallo'])) ?></td>
                            <td><?= htmlspecialchars($r['nombre_usuario_reportante']) ?></td>
                            
                            <td class="actions-cell">
                                <?php if ($r['devoluciones'] > 0): ?>
                                    <span class="status-devuelto">Volvió del mantenimiento</span>
                                <?php elseif ($r['en_mantenimiento'] > 0): ?>
                                    <span class="status-enviado">Enviado a mantenimiento</span>
                                    <a href="mantenimiento_volver.php?reporte_id=<?= $r['reporte_id'] ?>" class="btn">Volvió del mantenimiento</a>
                                <?php else: ?>
                                    <a href="mantenimiento_enviar.php?reporte_id=<?= $r['reporte_id'] ?>" class="btn">Enviar a mantenimiento</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>
