<?php
require __DIR__ . '/../init.php';
require_login();

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);
if(!$id) { echo json_encode(['error'=>'ID no válido']); exit; }

// Traer préstamo pendiente de devolución
$stmt = $mysqli->prepare("SELECT equipo_id FROM prestamos WHERE id=? AND estado='pendiente_devolucion'");
$stmt->bind_param("i",$id);
$stmt->execute();
$prestamo = $stmt->get_result()->fetch_assoc();
if(!$prestamo){ echo json_encode(['error'=>'Préstamo no encontrado o no pendiente de devolución']); exit; }

// Cambiar estado a devuelto
$stmt = $mysqli->prepare("UPDATE prestamos SET estado='devuelto', fecha_devolucion=NOW() WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();

// Marcar equipo como disponible
$stmt = $mysqli->prepare("UPDATE equipos SET prestado=0, estado='bueno' WHERE id=?");
$stmt->bind_param("i",$prestamo['equipo_id']);
$stmt->execute();

echo json_encode(['ok'=>'Devolución aprobada, equipo disponible']);
