<?php
require __DIR__ . '/../init.php';
require_login();

$rol = user()['rol'];

// Solo el admin puede ver la auditoría
if ($rol !== 'admin') {
    header("Location: /inventario_uni/");
    exit;
}

//------------- Consulta para las busquedas y las busquedas por fecha en la auditoría --------------
$search = trim($_GET['q'] ?? '');
$fecha_inicio = trim($_GET['fecha_inicio'] ?? '');
$fecha_fin = trim($_GET['fecha_fin'] ?? '');
$tipo_accion = trim($_GET['tipo'] ?? ''); // <--- NUEVA LÍNEA

$params = [];
$types = '';
$where_clauses = [];

// 1. Filtro de búsqueda por texto (acción o usuario)
if (!empty($search)) {
    $where_clauses[] = "(a.accion LIKE ? OR u.nombre LIKE ?)";
    $like_search = "%" . $search . "%";
    $params[] = $like_search;
    $params[] = $like_search;
    $types .= 'ss';
}

// 2. Filtro de fecha de inicio
if (!empty($fecha_inicio)) {
    $where_clauses[] = "a.fecha >= ?";
    $params[] = $fecha_inicio . ' 00:00:00';
    $types .= 's';
}

// 3. Filtro de fecha de fin
if (!empty($fecha_fin)) {
    $where_clauses[] = "a.fecha <= ?";
    $params[] = $fecha_fin . ' 23:59:59';
    $types .= 's';
}

// 4. NUEVO FILTRO POR TIPO DE ACCIÓN
if (!empty($tipo_accion)) {
    $where_clauses[] = "a.tipo_accion = ?"; // ¡Igualdad exacta!
    $params[] = $tipo_accion;
    $types .= 's';
}

$where = '';
if (!empty($where_clauses)) {
    $where = " WHERE " . implode(" AND ", $where_clauses);
}

//---------------------------------------------------------------------
$sql = "SELECT a.id, a.accion, a.fecha, u.nombre, a.tipo_accion
        FROM auditoria a
        JOIN usuarios u ON a.usuario_id = u.id 
        " . $where . "
        ORDER BY a.fecha DESC
        LIMIT 100";

// Usar sentencia preparada si hay búsqueda, si no, usar query simple
if (!empty($params)) {
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        die("Error al preparar la consulta: " . $mysqli->error);
    }
} else {
    $result = $mysqli->query($sql);
}

// Inicializar el contador de filas
$contador = 0; // <--- AÑADIR ESTA LÍNEA
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Auditoría - Inventario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/general.css">
</head>

<body>
    <?php include __DIR__ . '/navbar.php'; ?>

    <div class="container">
        <h2>Registro de Auditoría</h2>

        <!--Formulario de búsqueda----------------------------->
        <div class="actions">
            <form method="get" style="display:inline-flex; align-items: center; gap: 10px;">
                <input type="text" name="q" placeholder="Buscar..." value="<?= htmlspecialchars($search) ?>">

                <label for="tipo">Tipo:</label>
                <select name="tipo" id="tipo">
                    <option value="">-- Todos --</option>
                    <option value="préstamo" <?= ($_GET['tipo'] ?? '') == 'préstamo' ? 'selected' : '' ?>>Préstamos</option>
                    <option value="devolución" <?= ($_GET['tipo'] ?? '') == 'devolución' ? 'selected' : '' ?>>Devoluciones</option>
                    <option value="mantenimiento" <?= ($_GET['tipo'] ?? '') == 'mantenimiento' ? 'selected' : '' ?>>Mantenimiento</option>

                    <option value="registro_estudiante" <?= ($_GET['tipo'] ?? '') == 'registro_estudiante' ? 'selected' : '' ?>>Registro de estudiantes</option>

                    <option value="sesión" <?= ($_GET['tipo'] ?? '') == 'sesión' ? 'selected' : '' ?>>Inicios de Sesión</option>
                </select>
                <label for="fecha_inicio">Desde:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($fecha_inicio) ?>">

                <label for="fecha_fin">Hasta:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($fecha_fin) ?>">

                <button type="submit">Filtrar</button>
            </form>
        </div>

        <div class="report-actions" style="margin-bottom: 20px;">

            <?php
            // Obtenemos TODOS los parámetros GET actuales
            $query_string = http_build_query($_GET);
            ?>

            <a href="generar_reporte.php?formato=xlsx&<?= $query_string ?>" class="button">
                Descargar Reporte (Excel XLSX)
            </a>
            <a href="generar_reporte.php?formato=pdf&<?= $query_string ?>" class="button">
                Descargar PDF
            </a>
        </div>
        <table>
    </div>
    <!-------------------------------------------------->

    <table>
        <thead>
            <tr>
                <th>Nro</th>
                <th>Usuario</th>
                <th>Acción realizada</th>
                <th>Fecha</th>
            </tr>
        </thead>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php $contador++; ?> <tr>
                <td><?= $contador ?></td>
                <td><?= htmlspecialchars($row['nombre']) ?></td>
                <td><?= htmlspecialchars($row['accion']) ?></td>
                <td><?= htmlspecialchars($row['fecha']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    </div>
</body>

</html>