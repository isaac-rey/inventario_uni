<?php
require __DIR__ . '/../init.php';
require_login();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);
$motivo = trim($data['motivo'] ?? '');

if ($id <= 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// Buscar préstamo con devolución pendiente
$stmt = $mysqli->prepare("SELECT equipo_id FROM prestamos WHERE id=? AND estado='pendiente_devolucion' LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$prestamo = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$prestamo) {
    echo json_encode(['error' => 'No se encontró una devolución pendiente para este préstamo.']);
    exit;
}

$equipo_id = intval($prestamo['equipo_id']);

$mysqli->begin_transaction();

try {
    // Rechazar la devolución (volvemos a estado 'activo')
    $stmt = $mysqli->prepare("
        UPDATE prestamos
        SET estado='activo',
            observacion = CONCAT(IFNULL(observacion,''), '\nRechazado: ', ?)
        WHERE id=? LIMIT 1
    ");
    $stmt->bind_param("si", $motivo, $id);
    $stmt->execute();
    $stmt->close();

    // Registrar auditoría
    $accion = "Rechazó la solicitud de devolución del préstamo ID {$id} para el equipo ID {$equipo_id}. Motivo: {$motivo}";
    auditar($accion);

    $mysqli->commit();

    echo json_encode(['ok' => 'Solicitud de devolución rechazada correctamente.']);
} catch (Exception $e) {
    $mysqli->rollback();
    echo json_encode(['error' => 'Error al rechazar devolución: ' . $e->getMessage()]);
}

exit;
