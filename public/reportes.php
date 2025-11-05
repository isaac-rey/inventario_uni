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
    r.reporte_verificar,
    e.id AS equipo_id,
    e.marca,
    e.modelo,
    e.tipo,
    e.serial_interno,
    e.estado,
    (SELECT COUNT(*) FROM mantenimientos m WHERE m.reporte_id = r.id AND m.fecha_devolucion IS NULL) AS en_mantenimiento,
    (SELECT COUNT(*) FROM mantenimientos m WHERE m.reporte_id = r.id AND m.fecha_devolucion IS NOT NULL) AS devoluciones
FROM reporte_fallos r
LEFT JOIN equipos e ON e.id = r.id_equipo
ORDER BY r.fecha DESC
";

$res = $mysqli->query($sql);
$rows = $res && $res->num_rows > 0 ? $res->fetch_all(MYSQLI_ASSOC) : [];

// separar
$sin_verificar = array_filter($rows, fn($r) => $r['reporte_verificar'] == 0);
$verificados   = array_filter($rows, fn($r) => $r['reporte_verificar'] == 1);
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Historial de Reportes</title>
    <!-- Usamos el CSS que nos pasaste (prestamos/index style) -->
    <link rel="stylesheet" href="../css/tabla_prestamo_index.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Pequeña regla exclusivamente para el scroll de 6 filas -->
    <style>
        /* === ESTILOS GENERALES === */
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        /* Variables CSS (necesarias para tus estilos de label) */
        :root {
            --text-primary: #2c3e50;
            --spacing-sm: 6px;
        }

        /* === ESTILOS DE FORMULARIO Y LABEL === */
        .form-group-custom label {
            font-weight: 600;
            color: #4a4a4a;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: var(--spacing-sm);
            font-size: 0.875rem;
            cursor: default;
        }

        .form-group-custom input[type="text"],
        .form-group-custom select,
        .form-group-custom input[type="date"] {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Contenedor principal */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 15px;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        /* Tarjetas para las tablas */
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 1px solid #e1e8ed;
            overflow: hidden;
            padding: 0;
        }

        /* Títulos de las secciones */
        .card h2 {
            color: #2c3e50;
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pill {
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            min-width: 24px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        thead {
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            color: #fff;
        }

        th {
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        tbody tr:hover {
            background: #f0f4f8;
        }

        .muted {
            color: #888;
            text-align: center;
        }

        select,
        input:not([type="date"]) {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        /* Contenedor de Acciones (Flexbox) */
        td.acciones {
            white-space: nowrap;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        /* Estilos para botones de acción (Aprobar/Rechazar/Cancelar) */
        .action-buttons button {
            padding: 7px 14px;
            border: none;
            border-radius: 6px;
            background: #1e3c72;
            color: #fff;
            cursor: pointer;
            transition: 0.3s ease;
            font-size: 0.875rem;
        }

        .action-buttons button:hover {
            background: #2a5298;
        }

        /* Estilo para el botón de cancelar/rechazar */
        .action-buttons button[style*="#dc2626"] {
            background: #dc2626 !important;
        }

        .action-buttons button[style*="#dc2626"]:hover {
            background: #b91c1c !important;
        }

        /* Estilos para la sección de filtros */
        #filtroPrestamos {
            padding: 0 20px 20px 20px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        /* === ESTILOS PARA LA PAGINACIÓN (Basados en la imagen) === */
        #paginacionHistorial {
            display: flex;
            justify-content: center;
        }

        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 10px 15px;
            background: #1e3c72;
            /* Fondo de la barra de paginación: Azul oscuro */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            margin-top: 15px;
            color: #fff;
        }

        .pagination-button {
            padding: 8px 18px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s ease-in-out;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            color: #fff;
        }

        /* Estilo para botón Siguiente (activo) */
        .pagination-button.active-next {
            background: #2a5298;
            /* Un azul más vibrante para el botón activo */
        }

        .pagination-button.active-next:hover:not(:disabled) {
            background: #3e68b3;
        }

        /* Estilo para botón Anterior (deshabilitado/menos activo) */
        .pagination-button:disabled {
            background: #4a5a7d;
            /* Tono grisáceo azulado y más oscuro */
            color: rgba(255, 255, 255, 0.7);
            cursor: not-allowed;
            box-shadow: none;
        }

        .pagination-button:not(:disabled):not(.active-next) {
            /* Si quieres que 'Anterior' se vea como 'Siguiente' si está activo, 
                pero no es la página 1, puedes usar esta regla o ajustarla */
            background: #2a5298;
        }

        .pagination-button:not(:disabled):not(.active-next):hover {
            background: #3e68b3;
        }


        .pagination-info {
            font-size: 1rem;
            font-weight: 600;
            /* Más audaz para el número de página */
            color: #fff;
            padding: 0 10px;
            text-align: center;
            white-space: nowrap;
            /* Evita que el texto de página se rompa */
        }

        /* Responsive para móviles */
        @media (max-width: 768px) {
            .container {
                margin: 1rem;
                padding: 0;
            }

            .card {
                overflow-x: auto;
            }

            table {
                min-width: 750px;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/navbar.php'; ?>

    <div class="container">

        <h1 style="margin:1.25rem 0">Historial de Reportes</h1>

        <?php if (!$rows): ?>
            <p class="muted">No existen reportes aún.</p>
        <?php else: ?>

            <!-- Reportes sin verificar -->
            <div class="card">
                <h2>Reportes sin verificar <span class="pill" id="count-pendientes"><?= count($sin_verificar) ?></span></h2>
                <table>
                    <thead>
                        <tr>

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
                    <tbody id="tbody-pendientes">
                        <?php if (count($sin_verificar) > 0): ?>
                            <?php foreach ($sin_verificar as $r): ?>
                                <tr id="reporte-<?= $r['reporte_id'] ?>" data-en_mantenimiento="<?= (int)$r['en_mantenimiento'] ?>" data-devoluciones="<?= (int)$r['devoluciones'] ?>">

                                    <td><?= htmlspecialchars($r['fecha']) ?></td>
                                    <td><?= htmlspecialchars($r['marca'] . ' ' . $r['modelo'] . ' (' . $r['tipo'] . ')') ?></td>
                                    <td><?= htmlspecialchars($r['estado']) ?></td>
                                    <td><?= htmlspecialchars($r['serial_interno']) ?></td>
                                    <td><?= htmlspecialchars($r['tipo_fallo']) ?></td>
                                    <td><?= nl2br(htmlspecialchars($r['descripcion_fallo'])) ?></td>
                                    <td><?= htmlspecialchars($r['nombre_usuario_reportante']) ?></td>
                                    <td class="acciones">
                                        <div class="action-buttons">
                                            <button class="btn-verificar" data-id="<?= $r['reporte_id'] ?>">Verificar</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="muted">No hay reportes pendientes.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Reportes verificados -->
            <div class="card">
                <h2>Reportes verificados <span class="pill" id="count-verificados"><?= count($verificados) ?></span></h2>
                <table>
                    <thead>
                        <tr>

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
                    <tbody id="tbody-verificados">
                        <?php if (count($verificados) > 0): ?>
                            <?php foreach ($verificados as $r): ?>
                                <tr id="reporte-<?= $r['reporte_id'] ?>" data-en_mantenimiento="<?= (int)$r['en_mantenimiento'] ?>" data-devoluciones="<?= (int)$r['devoluciones'] ?>">

                                    <td><?= htmlspecialchars($r['fecha']) ?></td>
                                    <td><?= htmlspecialchars($r['marca'] . ' ' . $r['modelo'] . ' (' . $r['tipo'] . ')') ?></td>
                                    <td><?= htmlspecialchars($r['estado']) ?></td>
                                    <td><?= htmlspecialchars($r['serial_interno']) ?></td>
                                    <td><?= htmlspecialchars($r['tipo_fallo']) ?></td>
                                    <td><?= nl2br(htmlspecialchars($r['descripcion_fallo'])) ?></td>
                                    <td><?= htmlspecialchars($r['nombre_usuario_reportante']) ?></td>
                                    <td class="acciones">
                                        <?php if ($r['devoluciones'] > 0): ?>
                                            <span class="badge ok status-devuelto">Volvió del mantenimiento</span>
                                        <?php elseif ($r['en_mantenimiento'] > 0): ?>
                                            <span class="badge warn status-enviado">Enviado a mantenimiento</span>
                                            <div class="action_buttons">
                                                <a href="mantenimiento_volver.php?reporte_id=<?= $r['reporte_id'] ?>" class="btn">Volvió del mantenimiento</a>
                                            </div>
                                            <?php else: ?>
                                                <a href="mantenimiento_enviar.php?reporte_id=<?= $r['reporte_id'] ?>" class="btn">Enviar a mantenimiento</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="muted">No hay reportes verificados aún.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </div>


    <script>
        /**
         * Manejo de verificación:
         * - Llamamos a verificar_reporte.php enviando reporte_id (POST urlencoded)
         * - Si ok: movemos la fila a la tabla de verificados y reemplazamos la celda de acciones
         */
        document.addEventListener('DOMContentLoaded', () => {
            const botones = document.querySelectorAll('.btn-verificar');

            botones.forEach(btn => {
                btn.addEventListener('click', async (ev) => {
                    const id = btn.dataset.id;
                    const fila = document.querySelector(`#reporte-${id}`);
                    if (!fila) return;

                    const confirmacion = confirm('¿Estás seguro de que deseas marcar este reporte como verificado?');
                    if (!confirmacion) return;

                    try {
                        const res = await fetch('verificar_reporte.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'reporte_id=' + encodeURIComponent(id)
                        });
                        const data = await res.json();
                        if (data.success) {
                            // Construir la celda de acciones para verificados usando los datos ya presentes en la fila
                            const en_mantenimiento = parseInt(fila.dataset.en_mantenimiento || '0', 10);
                            const devoluciones = parseInt(fila.dataset.devoluciones || '0', 10);

                            // Remover la celda actual de acciones (botón Verificar)
                            const accionesCell = fila.querySelector('.actions-cell') || fila.querySelector('td:last-child');
                            if (accionesCell) accionesCell.remove();

                            // Crear nueva celda de acciones con el mismo markup que usabas para verificados
                            const nuevaAcciones = document.createElement('td');
                            nuevaAcciones.className = 'actions-cell';

                            if (devoluciones > 0) {
                                nuevaAcciones.innerHTML = '<span class="badge ok status-devuelto">Volvió del mantenimiento</span>';
                            } else if (en_mantenimiento > 0) {
                                nuevaAcciones.innerHTML = '<span class="badge warn status-enviado">Enviado a mantenimiento</span>' +
                                    '<a href="mantenimiento_volver.php?reporte_id=' + id + '" class="btn" style="margin-top:6px;display:inline-block">Volvió del mantenimiento</a>';
                            } else {
                                nuevaAcciones.innerHTML = '<a href="mantenimiento_enviar.php?reporte_id=' + id + '" class="btn">Enviar a mantenimiento</a>';
                            }

                            // Mover fila al comienzo de tbody-verificados
                            const tbodyVer = document.getElementById('tbody-verificados');
                            // Prepend: si tbodyVer tiene filas "No hay..." detectarlo y eliminar
                            // (si existía fila de "No hay reportes verificados aún." la removemos)
                            const firstRow = tbodyVer.querySelector('tr');
                            if (firstRow && firstRow.querySelector('.muted')) {
                                tbodyVer.innerHTML = '';
                            }

                            fila.appendChild(nuevaAcciones); // añadir la celda nueva al final de la fila
                            tbodyVer.prepend(fila);

                            // Actualizar contadores
                            const countPendientesEl = document.getElementById('count-pendientes');
                            const countVerificadosEl = document.getElementById('count-verificados');
                            const pendientes = document.querySelectorAll('#tbody-pendientes tr').length;
                            const verificados = document.querySelectorAll('#tbody-verificados tr').length;
                            // Si en pendientes queda una fila tipo "No hay..." la cuenta puede incluirla; manejar:
                            // Si tbody-pendientes tiene una fila con .muted no la contamos como reporte
                            const pendRows = Array.from(document.querySelectorAll('#tbody-pendientes tr'));
                            const pendCount = pendRows.filter(r => !r.querySelector('.muted')).length;
                            countPendientesEl.textContent = pendCount;
                            countVerificadosEl.textContent = verificados;

                            alert('Reporte verificado correctamente.');
                        } else {
                            alert(data.error || 'Error al verificar el reporte.');
                        }
                    } catch (err) {
                        console.error(err);
                        alert('Error en la solicitud AJAX.');
                    }
                });
            });
        });
    </script>

</body>

</html>