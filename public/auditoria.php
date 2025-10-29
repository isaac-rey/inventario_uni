<?php
require __DIR__ . '/../init.php';
require_login();

$rol = user()['rol'];
if ($rol !== 'admin') {
    header("Location: /inventario_uni/");
    exit;
}

$items_por_pagina = 5;
$pagina_actual = $_GET['pagina'] ?? 1;
$pagina_actual = max(1, (int)$pagina_actual);
$offset = ($pagina_actual - 1) * $items_por_pagina;

$search = trim($_GET['q'] ?? '');
$fecha_inicio = trim($_GET['fecha_inicio'] ?? '');
$fecha_fin = trim($_GET['fecha_fin'] ?? '');
$tipo_accion = trim($_GET['tipo'] ?? '');

$params = [];
$types = '';
$where_clauses = [];

if (!empty($search)) {
    $where_clauses[] = "(a.accion LIKE ? OR u.nombre LIKE ?)";
    $like_search = "%" . $search . "%";
    $params[] = $like_search;
    $params[] = $like_search;
    $types .= 'ss';
}
if (!empty($fecha_inicio)) {
    $where_clauses[] = "a.fecha >= ?";
    $params[] = $fecha_inicio . ' 00:00:00';
    $types .= 's';
}
if (!empty($fecha_fin)) {
    $where_clauses[] = "a.fecha <= ?";
    $params[] = $fecha_fin . ' 23:59:59';
    $types .= 's';
}
if (!empty($tipo_accion)) {
    $where_clauses[] = "a.tipo_accion = ?";
    $params[] = $tipo_accion;
    $types .= 's';
}

$where = '';
if (!empty($where_clauses)) {
    $where = " WHERE " . implode(" AND ", $where_clauses);
}

$count_params = $params;
$count_types = $types;
$sql_count = "SELECT COUNT(*) AS total FROM auditoria a JOIN usuarios u ON a.usuario_id = u.id " . $where;

if (!empty($count_params)) {
    $stmt_count = $mysqli->prepare($sql_count);
    if ($stmt_count) {
        $stmt_count->bind_param($count_types, ...$count_params);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $total_registros = $result_count->fetch_assoc()['total'];
        $stmt_count->close();
    } else {
        die("Error al preparar el COUNT: " . $mysqli->error);
    }
} else {
    $result_count = $mysqli->query($sql_count);
    $total_registros = $result_count->fetch_assoc()['total'];
}

$total_paginas = ceil($total_registros / $items_por_pagina);
$pagina_actual = min($pagina_actual, $total_paginas);
$offset = ($pagina_actual - 1) * $items_por_pagina;

$sql = "SELECT a.id, a.accion, a.fecha, u.nombre, a.tipo_accion
        FROM auditoria a
        JOIN usuarios u ON a.usuario_id = u.id
        $where
        ORDER BY a.fecha DESC
        LIMIT ? OFFSET ?";

$data_params = $params;
$data_types = $types . 'ii';
$data_params[] = $items_por_pagina;
$data_params[] = $offset;

$stmt = $mysqli->prepare($sql);
if ($stmt) {
    $bind_params = array_merge([$data_types], $data_params);
    $refs = [];
    foreach ($bind_params as $key => $value) $refs[$key] = &$bind_params[$key];
    call_user_func_array([$stmt, 'bind_param'], $refs);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error al preparar la consulta de datos: " . $mysqli->error);
}

$current_query = $_GET;
unset($current_query['pagina']);
$contador = $offset;
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Auditoría - Inventario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/reportes.css">
    <style>
        .paginacion {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            padding: 0 10px;
        }

        .paginacion button{
            color: #1b228aff;
        }

        .paginacion button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .paginacion span {
            font-weight: bold;
            color: #333663ff;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <div class="container">
        <h2>Registros de Auditoría</h2>
        <h3 style="text-align:right;color:#555;">Total de registros: <?= $total_registros ?></h3>

        <div class="actions">
            <form method="get" style="display:inline-flex;align-items:center;gap:10px;">
                <input type="text" name="q" placeholder="Buscar..." value="<?= htmlspecialchars($search) ?>">
                <label for="tipo">Tipo:</label>
                <select name="tipo" id="tipo">
                    <option value="">-- Todos --</option>
                    <option value="sesión" <?= ($_GET['tipo'] ?? '') == 'sesión' ? 'selected' : '' ?>>Inicios de sesión</option>
                    <option value="acción_equipo" <?= ($_GET['tipo'] ?? '') == 'acción_equipo' ? 'selected' : '' ?>>Equipos</option>
                    <option value="acción_componente" <?= ($_GET['tipo'] ?? '') == 'acción_componente' ? 'selected' : '' ?>>Componentes</option>
                    <option value="préstamo" <?= ($_GET['tipo'] ?? '') == 'préstamo' ? 'selected' : '' ?>>Préstamos</option>
                    <option value="devolución" <?= ($_GET['tipo'] ?? '') == 'devolución' ? 'selected' : '' ?>>Devoluciones</option>
                    <option value="reporte" <?= ($_GET['tipo'] ?? '') == 'reporte' ? 'selected' : '' ?>>Reportes de equipos</option>
                    <option value="mantenimiento" <?= ($_GET['tipo'] ?? '') == 'mantenimiento' ? 'selected' : '' ?>>Mantenimientos</option>
                    <option value="acción_usuario" <?= ($_GET['tipo'] ?? '') == 'acción_usuario' ? 'selected' : '' ?>>Usuarios</option>
                    <option value="acción_docentes" <?= ($_GET['tipo'] ?? '') == 'acción_docentes' ? 'selected' : '' ?>>Docentes</option>
                    <option value="acción_estudiante" <?= ($_GET['tipo'] ?? '') == 'acción_estudiante' ? 'selected' : '' ?>>Estudiantes</option>
                    <option value="acción_sala" <?= ($_GET['tipo'] ?? '') == 'acción_sala' ? 'selected' : '' ?>>Salas</option>
                </select>
                <label for="fecha_inicio">Desde:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
                <label for="fecha_fin">Hasta:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
                <button type="submit">Filtrar</button>
            </form>
        </div>

        <div class="report-actions" style="margin-bottom:20px;">
            <?php $query_string = http_build_query($_GET); ?>
            <a href="generar_reporte.php?formato=xlsx&<?= $query_string ?>" class="button button-excel">Descargar Excel (XLSX)</a>
            <a href="generar_reporte.php?formato=csv&<?= $query_string ?>" class="button button-csv">Descargar Excel (CSV)</a>
            <a href="generar_reporte.php?formato=pdf&<?= $query_string ?>" class="button button-pdf">Descargar PDF</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>Usuario</th>
                    <th>Acción realizada</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($total_registros > 0 && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php $contador++; ?>
                        <tr>
                            <td><?= $contador ?></td>
                            <td><?= htmlspecialchars($row['nombre']) ?></td>
                            <td><?= htmlspecialchars($row['accion']) ?></td>
                            <td><?= htmlspecialchars($row['fecha']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">No se encontraron registros de auditoría con los filtros aplicados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="paginacion">
            <?php
            $prev_query = $current_query;
            $prev_query['pagina'] = $pagina_actual - 1;
            $prev_link = http_build_query($prev_query);
            ?>
            <button <?= $pagina_actual <= 1 ? 'disabled' : '' ?> onclick="window.location.href='?<?= $prev_link ?>'">Anterior</button>
            <span>Página <?= $pagina_actual ?> de <?= $total_paginas ?></span>
            <?php
            $next_query = $current_query;
            $next_query['pagina'] = $pagina_actual + 1;
            $next_link = http_build_query($next_query);
            ?>
            <button <?= $pagina_actual >= $total_paginas ? 'disabled' : '' ?> onclick="window.location.href='?<?= $next_link ?>'">Siguiente</button>
        </div>
    </div>
</body>

</html>