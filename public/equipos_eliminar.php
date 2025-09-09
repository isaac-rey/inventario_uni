<?php
// public/equipos_eliminar.php
require __DIR__ . '/../init.php';
require_login();

$rol = user()['rol'];
$id = intval($_GET['id'] ?? 0);
if (!$id) { die("ID no especificado."); }

// Traer el equipo (para validar permisos antes de borrar)
$stmt = $mysqli->prepare("
  SELECT e.id, e.area_id, e.serial_interno, e.tipo, e.marca, e.modelo
  FROM equipos e
  WHERE e.id = ?
  LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) {
  // No existe → volver silenciosamente
  header("Location: equipos_index.php");
  exit;
}

// Si no es admin, solo puede borrar equipos de su área (Biblioteca id=1)
if ($rol !== 'admin' && intval($equipo['area_id']) !== 1) {
  http_response_code(403);
  die("Acceso denegado para eliminar equipos fuera de tu área.");
}

// IMPORTANTE: componentes se borran en cascada por FK ON DELETE CASCADE
$del = $mysqli->prepare("DELETE FROM equipos WHERE id=? LIMIT 1");
$del->bind_param("i", $id);
$del->execute();

// Volver al listado
header("Location: equipos_index.php");
exit;
