<?php
// public/componentes_eliminar.php
require __DIR__ . '/../init.php';
require_login();

$comp_id   = intval($_GET['id'] ?? 0);
$equipo_id = intval($_GET['equipo'] ?? 0);
if (!$comp_id || !$equipo_id) {
  die("Parámetros insuficientes.");
}
//-----------------------insercion de AUDITORIA-----------------------

// Verificar que el componente exista y pertenezca al equipo
//$stmt = $mysqli->prepare("SELECT id FROM componentes WHERE id=? AND equipo_id=? LIMIT 1");
$stmt = $mysqli->prepare("SELECT * FROM componentes WHERE id=? AND equipo_id=? LIMIT 1");
$stmt->bind_param("ii", $comp_id, $equipo_id);
$stmt->execute();
$comp_data = $stmt->get_result()->fetch_assoc();

//$exists = $stmt->get_result()->fetch_assoc();
//if (!$exists) {
if (!$comp_data) {
  header("Location: equipos_componentes.php?id=$equipo_id");
  exit;
}

// Borrar
$stmt = $mysqli->prepare("DELETE FROM componentes WHERE id=? AND equipo_id=? LIMIT 1");
$stmt->bind_param("ii", $comp_id, $equipo_id);

if ($stmt->execute()) {
  // ✅ INSERCIÓN DE LA AUDITORÍA AQUÍ
  $componente_desc = htmlspecialchars($comp_data['tipo'] . ' ' . $comp_data['marca'] . ' ' . $comp_data['modelo']);

  // Si tienes acceso a la información del equipo (como el serial o el tipo), podrías hacer un segundo SELECT
  // para un mensaje más detallado. Aquí usamos solo el ID del equipo.
  auditar("Eliminó el componente ID {$comp_id} ({$componente_desc}) del equipo ID {$equipo_id}.", 'acción_componente');
}
//$stmt->execute();

// Volver a la lista
header("Location: equipos_componentes.php?id=$equipo_id");
exit;