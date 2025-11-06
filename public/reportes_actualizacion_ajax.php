<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

// Misma consulta que usas en reportes.php
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
ORDER BY r.fecha DESC";

$res = $mysqli->query($sql);
$rows = $res && $res->num_rows > 0 ? $res->fetch_all(MYSQLI_ASSOC) : [];

// Separar reportes sin verificar y verificados
$sin_verificar = array_values(array_filter($rows, fn($r) => $r['reporte_verificar'] == 0));
$verificados   = array_values(array_filter($rows, fn($r) => $r['reporte_verificar'] == 1));

echo json_encode([
    'sin_verificar' => $sin_verificar,
    'verificados' => $verificados
]);
exit;
