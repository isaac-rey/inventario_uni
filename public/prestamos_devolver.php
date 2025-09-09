<?php
// public/prestamos_devolver.php
require __DIR__ . '/../init.php';
require_login();

$equipo_id = intval($_GET['equipo'] ?? 0);
if (!$equipo_id) { die("Equipo no especificado."); }

// Traer equipo (para validar que exista)
$stmt = $mysqli->prepare("SELECT id, prestado FROM equipos WHERE id=? LIMIT 1");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) {
  header("Location: equipos_index.php");
  exit;
}

// Buscar préstamo activo de ese equipo
$stmt = $mysqli->prepare("SELECT id FROM prestamos WHERE equipo_id=? AND estado='activo' ORDER BY fecha_entrega DESC LIMIT 1");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$prestamo = $stmt->get_result()->fetch_assoc();

if (!$prestamo) {
  // No hay préstamo activo → simplemente volver
  header("Location: equipos_index.php");
  exit;
}

// Cerrar préstamo: poner devuelto y fecha de devolución
$stmt = $mysqli->prepare("UPDATE prestamos SET estado='devuelto', fecha_devolucion=NOW() WHERE id=? LIMIT 1");
$stmt->bind_param("i", $prestamo['id']);
$stmt->execute();

// Marcar equipo como disponible nuevamente
// (por defecto lo devolvemos a estado 'bueno'; si querés, podés cambiarlo a lo que tenía antes)
$stmt = $mysqli->prepare("UPDATE equipos SET prestado=0, estado='bueno' WHERE id=? LIMIT 1");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();

header("Location: equipos_index.php");
exit;
