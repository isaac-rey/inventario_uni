<?php
require __DIR__ . '/../init.php';
require_login();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// Buscar devolución pendiente
$stmt = $mysqli->prepare("
    SELECT d.*, p.equipo_id
    FROM devoluciones d
    JOIN prestamos p ON p.id = d.prestamo_id
    WHERE p.id=? AND d.estado='pendiente' LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$devolucion = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$devolucion) {
    echo json_encode(['error' => 'No se encontró la devolución pendiente.']);
    exit;
}

$prestamo_id = intval($devolucion['prestamo_id']);
$equipo_id = intval($devolucion['equipo_id']);

$mysqli->begin_transaction();

try {
    // Marcar devolución como aprobada
    $stmt = $mysqli->prepare("UPDATE devoluciones SET estado='aprobada' WHERE id=?");
    $stmt->bind_param("i", $devolucion['id']);
    $stmt->execute();
    $stmt->close();

    // Marcar préstamo como devuelto
    $stmt = $mysqli->prepare("
        UPDATE prestamos
        SET estado='devuelto', fecha_devolucion=NOW()
        WHERE id=? LIMIT 1
    ");
    $stmt->bind_param("i", $prestamo_id);
    $stmt->execute();
    $stmt->close();

    // Actualizar equipo a disponible
    $stmt = $mysqli->prepare("
        UPDATE equipos SET prestado=0, estado='bueno', actualizado_en=NOW()
        WHERE id=? LIMIT 1
    ");
    $stmt->bind_param("i", $equipo_id);
    $stmt->execute();
    $stmt->close();

    //-----------------INSERCIÓN DE LA AUDITORÍA-----------------------
    // Necesitamos el serial del equipo. Dado que no lo traemos, solo usaremos el ID.
    $accion_msg = "Aprobó la devolución del Préstamo ID {$prestamo_id} para el Equipo ID {$equipo_id}. El activo vuelve al inventario.";
    // La función auditar() toma el ID del usuario logueado por require_login()
    auditar($accion_msg);
    // ---------------------------------------------------------------

    $mysqli->commit();
    echo json_encode(['ok' => '✅ Devolución aprobada correctamente.']);
} catch (Exception $e) {
    $mysqli->rollback();
    echo json_encode(['error' => 'Error al aprobar devolución: ' . $e->getMessage()]);
}
exit;
