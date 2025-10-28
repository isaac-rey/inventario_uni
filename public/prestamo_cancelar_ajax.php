<?php
require __DIR__ . '/../init.php';
require_login();

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$id = $data['id'] ?? null;
$motivo = trim($data['motivo'] ?? '');

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

    // === Traer préstamo actual ===
    $stmt = $mysqli->prepare("SELECT id, equipo_id, docente_id, estudiante_id, estado FROM prestamos WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $prestamo = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$prestamo) {
        $mysqli->rollback();
        echo json_encode(['error' => 'Préstamo no encontrado.']);
        exit;
    }

    // === Obtener datos del equipo (tipo + marca + modelo) ===
    $nombre_equipo = '';
    if (!empty($prestamo['equipo_id'])) {
        $stmt = $mysqli->prepare("SELECT tipo, marca, modelo FROM equipos WHERE id = ?");
        $stmt->bind_param("i", $prestamo['equipo_id']);
        $stmt->execute();
        $stmt->bind_result($tipo, $marca, $modelo);
        $stmt->fetch();
        $stmt->close();

        $partes = array_filter([$tipo, $marca, $modelo]);
        $nombre_equipo = $partes ? implode(' ', $partes) : 'Equipo sin descripción';
    }

    // === Determinar tipo de usuario (docente o estudiante) ===
    $tipo_usuario = '';
    $nombre_usuario = '';

    if (!empty($prestamo['docente_id'])) {
        $tipo_usuario = 'docente';
        $stmt = $mysqli->prepare("SELECT nombre, apellido FROM docentes WHERE id = ?");
        $stmt->bind_param("i", $prestamo['docente_id']);
        $stmt->execute();
        $stmt->bind_result($nombre, $apellido);
        $stmt->fetch();
        $stmt->close();
        $nombre_usuario = trim("{$nombre} {$apellido}");
    } elseif (!empty($prestamo['estudiante_id'])) {
        $tipo_usuario = 'estudiante';
        $stmt = $mysqli->prepare("SELECT nombre, apellido FROM estudiantes WHERE id = ?");
        $stmt->bind_param("i", $prestamo['estudiante_id']);
        $stmt->execute();
        $stmt->bind_result($nombre, $apellido);
        $stmt->fetch();
        $stmt->close();
        $nombre_usuario = trim("{$nombre} {$apellido}");
    }

    $mensaje_usuario = '';
    $tipo_accion = '';

    // === Determinar tipo de cancelación según estado ===
    if ($prestamo['estado'] === 'pendiente') {
        $tipo_accion = 'Ha rechazado la solicitud de préstamo';
        $mensaje_usuario = 'La solicitud de préstamo ha sido rechazada correctamente.';
    } elseif ($prestamo['estado'] === 'activo') {
        $tipo_accion = 'Ha cancelado de préstamo activo';
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

    // === Actualizar estado del préstamo ===
    $stmt = $mysqli->prepare("UPDATE prestamos SET estado = 'cancelado' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // === AUDITORÍA ===
    $accion_msg = "{$tipo_accion} del equipo ID {$prestamo['equipo_id']}";

    if ($nombre_equipo) {
        $accion_msg .= " ({$nombre_equipo})";
    }

    if ($tipo_usuario && $nombre_usuario) {
        $accion_msg .= " al {$tipo_usuario} '{$nombre_usuario}'";
    }

    if ($motivo !== '') {
        $accion_msg .= ". Motivo: {$motivo}.";
    }

    auditar($accion_msg);
    // ==================

    $mysqli->commit();

    echo json_encode(['ok' => $mensaje_usuario]);

} catch (Exception $e) {
    $mysqli->rollback();
    error_log("Error al cancelar préstamo: " . $e->getMessage());
    echo json_encode(['error' => 'Error interno al cancelar préstamo: ' . $e->getMessage()]);
}
