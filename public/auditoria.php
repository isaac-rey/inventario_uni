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

// Ajuste en la búsqueda para incluir nombres de docentes
if (!empty($search)) {
 // La búsqueda incluye ahora (acción, usuario, docente, estudiante)
 $where_clauses[] = "(
 a.accion LIKE ? 
 OR u.nombre LIKE ? 
 OR CONCAT(d.nombre, ' ', d.apellido) LIKE ? 
 OR CONCAT(e.nombre, ' ', e.apellido) LIKE ? -- ⭐ NUEVO
 )";
 $like_search = "%" . $search . "%";
 $params[] = $like_search; // a.accion
 $params[] = $like_search; // u.nombre
 $params[] = $like_search; // CONCAT(d.nombre, ' ', d.apellido)
 $params[] = $like_search; // CONCAT(e.nombre, ' ', e.apellido) -- ⭐ NUEVO
 $types .= 'ssss'; // Se añade un 's' adicional por el nuevo parámetro
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

// --- CONTEO TOTAL DE REGISTROS ---
// Mantenemos la unión estándar para el conteo.
$count_params = $params;
$count_types = $types;

$sql_count = "
 SELECT COUNT(*) AS total 
 FROM auditoria a 
 LEFT JOIN usuarios u ON a.usuario_id = u.id 
 LEFT JOIN docentes d ON a.usuario_id = d.id 
 LEFT JOIN estudiantes e ON a.usuario_id = e.id -- ⭐ NUEVO
 " . $where;

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

// --- CONSULTA DE DATOS CON LÓGICA ESPECIAL PARA CESIÓN, REPORTE Y AMBIGÜEDAD ---
$sql = "
SELECT 
    a.id, 
    a.accion, 
    a.fecha, 
    a.tipo_accion,
    -- ⭐ LÓGICA CORREGIDA PARA EL ACTOR (Añadiendo lógica basada en el texto para REPORTE) ⭐
    CASE 
        WHEN a.tipo_accion = 'CESION' THEN
            -- Lógica para cesión (no cambia)
            SUBSTRING_INDEX(
                SUBSTRING_INDEX(a.accion, ' cedió el equipo', 1),
                'Docente ', -1
            )
        -- ⭐ NUEVA LÓGICA: Usar el texto de la acción para distinguir Docente/Estudiante en 'reporte' ⭐
        WHEN a.tipo_accion = 'reporte' THEN
            CASE
                -- 1. Si la acción dice 'alumno/a', priorizar el Estudiante
                WHEN a.accion LIKE '%alumno/a reportó%' THEN
                    COALESCE(u.nombre, CONCAT(e.nombre, ' ', e.apellido))
                -- 2. Si la acción dice 'docente', priorizar el Docente
                WHEN a.accion LIKE '%docente Reportó%' THEN
                    COALESCE(u.nombre, CONCAT(d.nombre, ' ', d.apellido))
                ELSE
                    -- Si el tipo es 'reporte' pero el texto no coincide, usar la lógica general
                    COALESCE(u.nombre, CONCAT(d.nombre, ' ', d.apellido), CONCAT(e.nombre, ' ', e.apellido))
            END
        -- Para el resto de acciones (préstamo, devolución, etc. donde no hay texto distintivo en la acción)
        ELSE
            -- Prioridad general (asumiendo que Docente sigue siendo la entidad principal, salvo que se demuestre lo contrario)
            COALESCE(
                u.nombre, 
                CONCAT(d.nombre, ' ', d.apellido),
                CONCAT(e.nombre, ' ', e.apellido)
            )
    END AS nombre_actor
FROM auditoria a
-- Se mantienen las uniones para que el filtro de búsqueda funcione correctamente
LEFT JOIN usuarios u ON a.usuario_id = u.id
LEFT JOIN docentes d ON a.usuario_id = d.id 
LEFT JOIN estudiantes e ON a.usuario_id = e.id 
$where
ORDER BY a.fecha DESC
LIMIT ? OFFSET ?
";

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
</head>

<body>
    <?php include __DIR__ . '/navbar.php'; ?>

    <div class="container">
        <h2 class="mb-2">Registros de Auditoría</h2>
        <h3 class="text-right muted mb-3">Total de registros: <?= $total_registros ?></h3>

        <div class="filter-card">

            <form method="get" class="form-row">

                <div class="form-group-custom">
                    <label for="q">Buscar acción o usuario</label>
                    <input type="text" id="q" name="q" placeholder="Buscar acción o usuario..." value="<?= htmlspecialchars($search) ?>">
                </div>

                <div class="form-group-custom">
                    <label for="tipo">Tipo de Acción</label>
                    <select name="tipo" id="tipo">
                        <option value="">-- Todos los Tipos --</option>
                        <option value="inicio_sesión" <?= ($_GET['tipo'] ?? '') == 'inicio_sesión' ? 'selected' : '' ?>>Inicios de sesión</option>
                        <option value="cierre_sesión" <?= ($_GET['tipo'] ?? '') == 'cierre_sesión' ? 'selected' : '' ?>>Cierres de sesión</option>
                        <option value="contra_restablecimiento" <?= ($_GET['tipo'] ?? '') == 'contra_restablecimiento' ? 'selected' : '' ?>>Acciones de contraseñas</option>
                        <option value="acción_equipo" <?= ($_GET['tipo'] ?? '') == 'acción_equipo' ? 'selected' : '' ?>>Equipos</option>
                        <option value="acción_componente" <?= ($_GET['tipo'] ?? '') == 'acción_componente' ? 'selected' : '' ?>>Componentes</option>
                        <option value="préstamo" <?= ($_GET['tipo'] ?? '') == 'préstamo' ? 'selected' : '' ?>>Préstamos</option>
                        <option value="devolución" <?= ($_GET['tipo'] ?? '') == 'devolución' ? 'selected' : '' ?>>Devoluciones</option>
                        <option value="cesión_docentes" <?= ($_GET['tipo'] ?? '') == 'cesión_docentes' ? 'selected' : '' ?>>Préstamos cedidos</option>
                        <option value="reporte" <?= ($_GET['tipo'] ?? '') == 'reporte' ? 'selected' : '' ?>>Reportes de equipos</option>
                        <option value="mantenimiento" <?= ($_GET['tipo'] ?? '') == 'mantenimiento' ? 'selected' : '' ?>>Mantenimientos</option>
                        <option value="acción_usuario" <?= ($_GET['tipo'] ?? '') == 'acción_usuario' ? 'selected' : '' ?>>Usuarios</option>
                        <option value="acción_docentes" <?= ($_GET['tipo'] ?? '') == 'acción_docentes' ? 'selected' : '' ?>>Docentes</option>
                        <option value="acción_estudiante" <?= ($_GET['tipo'] ?? '') == 'acción_estudiante' ? 'selected' : '' ?>>Estudiantes</option>
                        <option value="acción_sala" <?= ($_GET['tipo'] ?? '') == 'acción_sala' ? 'selected' : '' ?>>Salas</option>
                    </select>
                </div>


                <div class="form-group-custom">
                    <label for="fecha_inicio">Desde (Fecha)</label>

                    <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">
                </div>


                <div class="form-group-custom">
                    <label for="fecha_fin">Hasta (Fecha)</label>

                    <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">
                </div>


                <div class="form-group-custom" style="flex-grow: 0;">
                    <button type="submit" class="btn-filter-custom">Filtrar</button>
                </div>
            </form>
        </div>


        <div class="report-buttons">
            <?php $query_string = http_build_query($_GET); ?>

            <a href="generar_reporte.php?formato=xlsx&<?= $query_string ?>" class="btn-excel-xlsx">Descargar Excel (XLSX)</a>
            <a href="generar_reporte.php?formato=pdf&<?= $query_string ?>" class="btn-pdf">Descargar PDF</a>
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

                            <td data-label="Nro"><?= $contador ?></td>
                            <td data-label="Usuario"><?= htmlspecialchars($row['nombre_actor'] ?? 'Sistema/Anónimo') ?></td> 
                            <td data-label="Acción"><?= htmlspecialchars($row['accion']) ?></td>
                            <td data-label="Fecha" class="align-left"><?= htmlspecialchars($row['fecha']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center muted">No se encontraron registros de auditoría con los filtros aplicados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>


        <div class="pagination-container">
            <?php
            $prev_query = $current_query;
            $prev_query['pagina'] = $pagina_actual - 1;
            $prev_link = http_build_query($prev_query);
            ?>
            <button class="pagination-button" <?= $pagina_actual <= 1 ? 'disabled' : '' ?> onclick="window.location.href='?<?= $prev_link ?>'">Anterior</button>

            <span class="pagination-info">Página <?= $pagina_actual ?> de <?= $total_paginas ?></span>

            <?php
            $next_query = $current_query;
            $next_query['pagina'] = $pagina_actual + 1;
            $next_link = http_build_query($next_query);
            ?>
            <button class="pagination-button" <?= $pagina_actual >= $total_paginas ? 'disabled' : '' ?> onclick="window.location.href='?<?= $next_link ?>'">Siguiente</button>
        </div>
    </div>

</body>

</html>