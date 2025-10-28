<?php
require __DIR__ . '/../init.php';
require_login();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);

if (!$id) {
    echo json_encode(['error' => 'ID no válido']);
    exit;
}

// Buscar préstamo pendiente de devolución
$stmt = $mysqli->prepare("SELECT equipo_id FROM prestamos WHERE id=? AND estado='pendiente_devolucion'");
$stmt->bind_param("i", $id);
$stmt->execute();
$prestamo = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$prestamo) {
    echo json_encode(['error' => 'Préstamo no encontrado o no pendiente de devolución']);
    exit;
}

$equipo_id = intval($prestamo['equipo_id']);

$mysqli->begin_transaction();

try {
    // Cambiar estado del préstamo
    $stmt = $mysqli->prepare("UPDATE prestamos SET estado='devuelto', fecha_devolucion=NOW() WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Marcar equipo como disponible
    $stmt = $mysqli->prepare("UPDATE equipos SET prestado=0, estado='bueno' WHERE id=?");
    $stmt->bind_param("i", $equipo_id);
    $stmt->execute();
    $stmt->close();

    // -------- AUDITORÍA --------
    // Si tenés el usuario logueado disponible (por require_login), usamos auditar():
    $accion = "Aprobó la devolución del préstamo ID {$id} para el equipo ID {$equipo_id}. El activo vuelve al inventario.";
    auditar($accion);
    // ----------------------------

    $mysqli->commit();

    echo json_encode(['ok' => 'Devolución aprobada correctamente.']);
} catch (Exception $e) {
    $mysqli->rollback();
    echo json_encode(['error' => 'Error al aprobar devolución: ' . $e->getMessage()]);
}

exit;
