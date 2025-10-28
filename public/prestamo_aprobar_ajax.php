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

// Traer préstamo pendiente
$stmt = $mysqli->prepare("SELECT equipo_id, docente_id, estudiante_id FROM prestamos WHERE id=? AND estado='pendiente'");
$stmt->bind_param("i", $id);
$stmt->execute();
$prestamo = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$prestamo) {
    echo json_encode(['error' => 'Préstamo no encontrado o ya fue aprobado']);
    exit;
}

// Traer información del equipo
$stmt = $mysqli->prepare("SELECT tipo, marca, modelo FROM equipos WHERE id=? LIMIT 1");
$stmt->bind_param("i", $prestamo['equipo_id']);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
$stmt->close();

$nombre_equipo = $equipo ? "{$equipo['tipo']} {$equipo['marca']} {$equipo['modelo']}" : "Desconocido";

// Determinar tipo de usuario y su ID
$tipo_usuario = null;
$id_usuario_prestamo = null;
$nombre_usuario = '';

if (!empty($prestamo['docente_id'])) {
    $tipo_usuario = 'docente';
    $id_usuario_prestamo = $prestamo['docente_id'];
    
    $stmt = $mysqli->prepare("SELECT nombre, apellido FROM docentes WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id_usuario_prestamo);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    $nombre_usuario = $usuario ? $usuario['nombre'].' '.$usuario['apellido'] : 'Desconocido';
    
} elseif (!empty($prestamo['estudiante_id'])) {
    $tipo_usuario = 'estudiante';
    $id_usuario_prestamo = $prestamo['estudiante_id'];
    
    $stmt = $mysqli->prepare("SELECT nombre, apellido FROM estudiantes WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id_usuario_prestamo);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    $nombre_usuario = $usuario ? $usuario['nombre'].' '.$usuario['apellido'] : 'Desconocido';
}

// Aprobar préstamo y asignar usuario actual
$stmt = $mysqli->prepare("UPDATE prestamos SET estado='activo', fecha_entrega=NOW(), usuario_actual_id=? WHERE id=?");
$stmt->bind_param("ii", $id_usuario_prestamo, $id);
$stmt->execute();
$stmt->close();

// Marcar equipo como prestado
$stmt = $mysqli->prepare("UPDATE equipos SET prestado=1, estado='en_uso' WHERE id=?");
$stmt->bind_param("i", $prestamo['equipo_id']);
$stmt->execute();
$stmt->close();

//------------------- AUDITORÍA --------------------
$accion_msg = "Aprobó y registró el préstamo del equipo ID {$prestamo['equipo_id']} ({$nombre_equipo}) al {$tipo_usuario} '{$nombre_usuario}'.";
auditar($accion_msg);
// -------------------------------------------------

echo json_encode(['ok' => "Préstamo aprobado correctamente."]);
