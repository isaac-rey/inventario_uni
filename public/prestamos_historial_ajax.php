<?php
require __DIR__ . '/../init.php';
require_login();

$pagina = max(1, intval($_POST['pagina'] ?? 1));
$por_pagina = 10;
$offset = ($pagina - 1) * $por_pagina;

$where = "1";
if (!empty($_POST['fecha_inicio'])) {
    $fi = $_POST['fecha_inicio'];
    $where .= " AND p.fecha_devolucion >= '$fi'";
}
if (!empty($_POST['fecha_fin'])) {
    $ff = $_POST['fecha_fin'];
    $where .= " AND p.fecha_devolucion <= '$ff'";
}
if (!empty($_POST['tipo_solicitante'])) {
    if ($_POST['tipo_solicitante'] === 'estudiante')
        $where .= " AND p.estudiante_id IS NOT NULL";
    else
        $where .= " AND p.docente_id IS NOT NULL";
}

$total = $mysqli->query("SELECT COUNT(*) AS c FROM prestamos p WHERE p.estado='devuelto' AND $where")->fetch_assoc()['c'];
$res = $mysqli->query("
    SELECT p.*, e.tipo, e.marca, e.modelo, e.serial_interno,
           est.nombre AS est_nombre, est.apellido AS est_apellido,
           d.nombre AS d_nombre, d.apellido AS d_apellido
    FROM prestamos p
    JOIN equipos e ON e.id = p.equipo_id
    LEFT JOIN estudiantes est ON est.id = p.estudiante_id
    LEFT JOIN docentes d ON d.id = p.docente_id
    WHERE p.estado='devuelto' AND $where
    ORDER BY p.fecha_devolucion DESC
    LIMIT $offset, $por_pagina
");

echo "<table class='historial'><thead>
<tr><th>Equipo</th><th>Serial</th><th>Solicitante</th><th>Fecha devolución</th><th>Observación</th></tr></thead><tbody>";

while ($r = $res->fetch_assoc()) {
    $solicitante = $r['est_nombre']
        ? "{$r['est_nombre']} {$r['est_apellido']} (Estudiante)"
        : "{$r['d_nombre']} {$r['d_apellido']} (Docente)";
    echo "<tr>
        <td>{$r['tipo']} {$r['marca']} {$r['modelo']}</td>
        <td>{$r['serial_interno']}</td>
        <td>$solicitante</td>
        <td>{$r['fecha_devolucion']}</td>
        <td>{$r['observacion']}</td>
    </tr>";
}
echo "</tbody></table>";

$total_paginas = ceil($total / $por_pagina);
echo "<div id='totalPaginas' data-total='$total_paginas'></div>";
