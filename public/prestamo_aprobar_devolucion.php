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

// Buscar préstamo pendiente de devolución, OBTENIENDO IDs DE USUARIO Y DATOS DEL EQUIPO
$stmt = $mysqli->prepare("
    SELECT 
        p.equipo_id, 
        p.docente_id, 
        p.estudiante_id, 
        e.tipo,
        e.marca,
        e.modelo 
    FROM prestamos p
    JOIN equipos e ON e.id = p.equipo_id
    WHERE p.id=? AND p.estado='pendiente_devolucion'
");
$stmt->bind_param("i", $id);
$stmt->execute();
$prestamo = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$prestamo) {
    echo json_encode(['error' => 'Préstamo no encontrado o no pendiente de devolución']);
    exit;
}

$equipo_id = intval($prestamo['equipo_id']);
$equipo_desc = htmlspecialchars($prestamo['tipo'] . ' ' . $prestamo['marca'] . ' ' . $prestamo['modelo']); // Nuevo: descripción del equipo
$nombre_usuario = 'Desconocido';
$ci_usuario = 'N/A';
$tipo_usuario = 'usuario';

// --- NUEVA LÓGICA PARA OBTENER DATOS DEL USUARIO ---
if (!empty($prestamo['docente_id'])) {
    $stmt_user = $mysqli->prepare("SELECT nombre, apellido, ci FROM docentes WHERE id=? LIMIT 1");
    $stmt_user->bind_param("i", $prestamo['docente_id']);
    $tipo_usuario = 'docente';
} elseif (!empty($prestamo['estudiante_id'])) {
    $stmt_user = $mysqli->prepare("SELECT nombre, apellido, ci FROM estudiantes WHERE id=? LIMIT 1");
    $stmt_user->bind_param("i", $prestamo['estudiante_id']);
    $tipo_usuario = 'estudiante';
}

if (isset($stmt_user)) {
    $stmt_user->execute();
    $user_data = $stmt_user->get_result()->fetch_assoc();
    $stmt_user->close();

    if ($user_data) {
        $nombre_usuario = "{$user_data['nombre']} {$user_data['apellido']}";
        $ci_usuario = $user_data['ci'];
    }
}

$mysqli->begin_transaction();

try {
    // Cambiar estado del préstamo
    $stmt = $mysqli->prepare("UPDATE prestamos SET estado='devuelto', fecha_devolucion=NOW() WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Marcar equipo como disponible
    $stmt = $mysqli->prepare("UPDATE equipos SET prestado=0, estado='Disponible' WHERE id=?");
    $stmt->bind_param("i", $equipo_id);
    $stmt->execute();
    $stmt->close();

    // -------- AUDITORÍA --------
    // Si tenés el usuario logueado disponible (por require_login), usamos auditar():
    $accion = "Aprobó la devolución del préstamo del equipo ID {$equipo_id} ({$equipo_desc}), devuelto por el {$tipo_usuario}: '{$nombre_usuario}' (CI: {$ci_usuario}).";
    // CLAVE: Se añade el tipo de acción 'devolución'
    auditar($accion, 'devolución');
    // ----------------------------

    $mysqli->commit();

    echo json_encode(['ok' => 'Devolución aprobada correctamente.']);
} catch (Exception $e) {
    $mysqli->rollback();
    echo json_encode(['error' => 'Error al aprobar devolución: ' . $e->getMessage()]);
}

exit;
