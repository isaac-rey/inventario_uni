<?php
require __DIR__ . '/../init.php';
require_login();

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$id = $data['id'] ?? null;
$motivo = $data['motivo'] ?? 'Cancelado por administrador/staff.';

if (!is_numeric($id) || $id <= 0) {
    echo json_encode(['error' => 'ID de préstamo inválido.']);
    exit;
}

global $mysqli;

if (!$mysqli) {
    echo json_encode(['error' => 'ERROR FATAL: La conexión a la base de datos ($mysqli) no está disponible.']);
    exit;
}

try {
    $mysqli->begin_transaction();

    // Traer préstamo actual
    $stmt = $mysqli->prepare("SELECT id, equipo_id, estado FROM prestamos WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $prestamo = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$prestamo) {
        $mysqli->rollback();
        echo json_encode(['error' => 'Préstamo no encontrado.']);
        exit;
    }

    $mensaje_usuario = '';
    $tipo_accion = '';

    // Determinar tipo de cancelación según estado
    if ($prestamo['estado'] === 'pendiente') {
        $tipo_accion = 'Rechazo de solicitud de préstamo';
        $mensaje_usuario = 'La solicitud de préstamo ha sido rechazada correctamente.';
    } elseif ($prestamo['estado'] === 'activo') {
        $tipo_accion = 'Cancelación de préstamo activo';
        $mensaje_usuario = 'El préstamo aprobado ha sido cancelado correctamente.';

        // Liberar equipo
        if ($prestamo['equipo_id']) {
            $stmt = $mysqli->prepare("UPDATE equipos SET prestado = 0, estado = 'bueno' WHERE id = ?");
            $stmt->bind_param("i", $prestamo['equipo_id']);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        $mysqli->rollback();
        echo json_encode(['error' => 'No se puede cancelar este préstamo.']);
        exit;
    }

    // Actualizar estado del préstamo
    $stmt = $mysqli->prepare("UPDATE prestamos SET estado = 'cancelado' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Auditoría
    $accion_msg = "{$tipo_accion} - Préstamo ID {$id}. Motivo: {$motivo}.";
    auditar($accion_msg);

    $mysqli->commit();

    echo json_encode(['ok' => $mensaje_usuario]);

} catch (Exception $e) {
    $mysqli->rollback();
    error_log("Error al cancelar préstamo: " . $e->getMessage());
    echo json_encode(['error' => 'Error interno al cancelar préstamo: ' . $e->getMessage()]);
}
