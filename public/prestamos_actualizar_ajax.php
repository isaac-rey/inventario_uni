<?php
require __DIR__ . '/../init.php';
require_login();

header('Content-Type: application/json');

$prestamos = [];

$q = $mysqli->query("
    SELECT p.id, p.estado, p.fecha_entrega, p.observacion,
           e.tipo, e.marca, e.modelo, e.serial_interno,
           est.id AS est_id, est.nombre, est.apellido, est.ci
    FROM prestamos p
    JOIN equipos e ON e.id = p.equipo_id
    LEFT JOIN estudiantes est ON est.id = p.estudiante_id
    WHERE p.estado IN ('activo','pendiente')
    ORDER BY p.fecha_entrega DESC
");

while ($r = $q->fetch_assoc()) {
    // Verificar si existe una devolución pendiente
    $stmt = $mysqli->prepare("SELECT id FROM devoluciones WHERE prestamo_id=? AND estado='pendiente' LIMIT 1");
    $stmt->bind_param("i", $r['id']);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $r['estado'] = 'pendiente_devolucion';
    }
    $stmt->close();

    $r['historial_cesiones'] = [];
    $prestamos[] = $r;
}

echo json_encode(['prestamos' => $prestamos]);
exit;
