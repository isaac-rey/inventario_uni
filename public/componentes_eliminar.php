<?php
// public/componentes_eliminar.php
require __DIR__ . '/../init.php';
require_login();

$comp_id   = intval($_GET['id'] ?? 0);
$equipo_id = intval($_GET['equipo'] ?? 0);
if (!$comp_id || !$equipo_id) { die("Parámetros insuficientes."); }

// Verificar que el componente exista y pertenezca al equipo
$stmt = $mysqli->prepare("SELECT id FROM componentes WHERE id=? AND equipo_id=? LIMIT 1");
$stmt->bind_param("ii", $comp_id, $equipo_id);
$stmt->execute();
$exists = $stmt->get_result()->fetch_assoc();
if (!$exists) {
  header("Location: equipos_componentes.php?id=$equipo_id");
  exit;
}

// Borrar
$stmt = $mysqli->prepare("DELETE FROM componentes WHERE id=? AND equipo_id=? LIMIT 1");
$stmt->bind_param("ii", $comp_id, $equipo_id);
$stmt->execute();

// Volver a la lista
header("Location: equipos_componentes.php?id=$equipo_id");
exit;
