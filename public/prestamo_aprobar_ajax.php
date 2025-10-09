<?php
require __DIR__ . '/../init.php';
require_login();

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);
if(!$id) { echo json_encode(['error'=>'ID no válido']); exit; }

// Traer préstamo pendiente
$stmt = $mysqli->prepare("SELECT equipo_id, docente_id, estudiante_id FROM prestamos WHERE id=? AND estado='pendiente'");
$stmt->bind_param("i",$id);
$stmt->execute();
$prestamo = $stmt->get_result()->fetch_assoc();
if(!$prestamo){ echo json_encode(['error'=>'Préstamo no encontrado']); exit; }

// Aprobar préstamo y asignar usuario actual
$usuario_id = $prestamo['docente_id'] ?? $prestamo['estudiante_id'];
$stmt = $mysqli->prepare("UPDATE prestamos SET estado='activo', fecha_entrega=NOW(), usuario_actual_id=? WHERE id=?");
$stmt->bind_param("ii",$usuario_id,$id);
$stmt->execute();

// Marcar equipo como prestado
$stmt = $mysqli->prepare("UPDATE equipos SET prestado=1, estado='en_uso' WHERE id=?");
$stmt->bind_param("i",$prestamo['equipo_id']);
$stmt->execute();

echo json_encode(['ok'=>'Préstamo aprobado']);
