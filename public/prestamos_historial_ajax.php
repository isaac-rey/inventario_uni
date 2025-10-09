<?php
require __DIR__ . '/../init.php';
require_login();

// helper para bind dinámico
function refValues($arr)
{
    $refs = [];
    foreach ($arr as $k => $v) $refs[$k] = &$arr[$k];
    return $refs;
}

// parámetros
$fecha_inicio = $_POST['fecha_inicio'] ?? '';
$fecha_fin = $_POST['fecha_fin'] ?? '';
$tipo_solicitante = $_POST['tipo_solicitante'] ?? '';
$pagina = max(1, intval($_POST['pagina'] ?? 1));
$por_pagina = 5;
$offset = ($pagina - 1) * $por_pagina;

// condiciones
$where = ["p.estado='devuelto'"];
$params = [];
$types = '';

if ($fecha_inicio) {
    $where[] = "DATE(p.fecha_devolucion) >= ?";
    $params[] = $fecha_inicio;
    $types .= "s";
}
if ($fecha_fin) {
    $where[] = "DATE(p.fecha_devolucion) <= ?";
    $params[] = $fecha_fin;
    $types .= "s";
}
if ($tipo_solicitante === 'docente') {
    $where[] = "p.docente_id IS NOT NULL AND p.estudiante_id IS NULL";
} elseif ($tipo_solicitante === 'estudiante') {
    $where[] = "p.estudiante_id IS NOT NULL AND p.docente_id IS NULL";
}


$where_sql = "WHERE " . implode(" AND ", $where);

// total
$total_sql = "SELECT COUNT(DISTINCT p.id) FROM prestamos p $where_sql";
$total_stmt = $mysqli->prepare($total_sql);
if ($params) {
    $bind = array_merge([$types], $params);
    call_user_func_array([$total_stmt, 'bind_param'], refValues($bind));
}
$total_stmt->execute();
$total_stmt->bind_result($total);
$total_stmt->fetch();
$total_stmt->close();

$total_paginas = max(1, ceil($total / $por_pagina));

// consulta principal
$sql = "
SELECT DISTINCT 
    p.id, p.equipo_id, p.fecha_entrega, p.fecha_devolucion, p.observacion,
    p.devuelto_por_tercero_nombre, p.devuelto_por_tercero_ci,
    e.tipo, e.marca, e.modelo, e.serial_interno,
    est.id AS est_id, est.nombre AS est_nombre, est.apellido AS est_apellido, est.ci AS est_ci,
    d.id AS doc_id, d.nombre AS doc_nombre, d.apellido AS doc_apellido, d.ci AS doc_ci
FROM prestamos p
JOIN equipos e ON e.id = p.equipo_id
LEFT JOIN estudiantes est ON est.id = p.estudiante_id
LEFT JOIN docentes d ON d.id = p.docente_id
$where_sql
GROUP BY p.id
ORDER BY p.fecha_devolucion DESC, p.id DESC
LIMIT ? OFFSET ?
";

$stmt = $mysqli->prepare($sql);
$params_full = $params;
$params_full[] = $por_pagina;
$params_full[] = $offset;
$types_full = $types . "ii";
$bind = array_merge([$types_full], $params_full);
call_user_func_array([$stmt, 'bind_param'], refValues($bind));
$stmt->execute();
$result = $stmt->get_result();
$prestamos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// historial de cesiones (solo docentes)
foreach ($prestamos as &$p) {
    if ($p['doc_id']) {
        $hstmt = $mysqli->prepare("
            SELECT d.nombre, d.apellido, d.ci, hc.fecha
            FROM historial_cesiones hc
            JOIN docentes d ON d.id = hc.de_docente_id
            WHERE hc.prestamo_id = ?
            ORDER BY hc.fecha ASC
        ");
        $hstmt->bind_param("i", $p['id']);
        $hstmt->execute();
        $p['historial'] = $hstmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $hstmt->close();
    } else {
        $p['historial'] = [];
    }
}
unset($p);

// render
echo '<div id="totalPaginas" data-total="' . intval($total_paginas) . '" style="display:none;"></div>';

echo '<table>';
echo '<thead>
<tr>
<th>Equipo</th>
<th>Serial</th>
<th>Solicitante</th>
<th>Fecha entrega</th>
<th>Fecha devolución</th>
<th>Devuelto por</th>
<th>Obs</th>
</tr>
</thead><tbody>';

if (!$prestamos) {
    echo '<tr><td colspan="7" class="muted">No hay préstamos devueltos.</td></tr>';
} else {
    foreach ($prestamos as $p) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($p['tipo'] . ' ' . $p['marca'] . ' ' . $p['modelo']) . '</td>';
        echo '<td>' . htmlspecialchars($p['serial_interno']) . '</td>';

        // Solicitante
        echo '<td>';
        if ($p['est_id']) {
            echo htmlspecialchars("{$p['est_nombre']} {$p['est_apellido']} (CI: {$p['est_ci']}) (Estudiante)");
        } else {
            if (!empty($p['historial'])) {
                foreach ($p['historial'] as $h) {
                    echo htmlspecialchars("{$h['nombre']} {$h['apellido']} (CI: {$h['ci']}) (Docente)") . "<br>";
                }
            }
            echo htmlspecialchars("{$p['doc_nombre']} {$p['doc_apellido']} (CI: {$p['doc_ci']}) (Docente)");
        }
        echo '</td>';

        echo '<td>' . htmlspecialchars($p['fecha_entrega']) . '</td>';
        echo '<td>' . htmlspecialchars($p['fecha_devolucion'] ?? '-') . '</td>';

        // Devuelto por
        echo '<td>';
        if ($p['est_id']) {
            // Estudiante
            // Mostrar siempre quién devolvió (tercero o mismo estudiante)
            echo htmlspecialchars(
                ($p['devuelto_por_tercero_nombre'] ?? $p['est_nombre']) . ' ' . $p['est_apellido'] .
                    ' (CI: ' . ($p['devuelto_por_tercero_ci'] ?? $p['est_ci']) . ') (Estudiante)'
            );
        } else {
            // Docente
            if (!empty($p['historial_cesiones'])) {
                // Tomar el último docente según historial
                $ultimo_doc = end($p['historial_cesiones']);
                echo htmlspecialchars($ultimo_doc['nombre'] . ' ' . $ultimo_doc['apellido'] . ' (CI: ' . $ultimo_doc['ci'] . ') (Docente)');
            } else {
                // Si no hubo cesiones, es el prestamista original
                echo htmlspecialchars($p['doc_nombre'] . ' ' . $p['doc_apellido'] . ' (CI: ' . $p['doc_ci'] . ') (Docente)');
            }
        }
        echo '</td>';

        echo '<td>' . htmlspecialchars($p['observacion'] ?? '') . '</td>';
        echo '</tr>';
    }
}

echo '</tbody></table>';
