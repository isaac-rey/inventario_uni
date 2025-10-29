<?php
require __DIR__ . '/../init.php';
require_login();

$rol = user()['rol'];
$id = intval($_GET['id'] ?? 0);
if (!$id) die("ID no especificado.");

// Traer el equipo
//$stmt = $mysqli->prepare("SELECT e.id, e.area_id FROM equipos e WHERE e.id=? LIMIT 1");
$stmt = $mysqli->prepare("SELECT e.id, e.area_id, e.tipo, e.marca, e.modelo, e.serial_interno FROM equipos e WHERE e.id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) {
    header("Location: equipos_index.php");
    exit;
}

// Permisos
if ($rol !== 'admin' && intval($equipo['area_id']) !== 1) {
    http_response_code(403);
    die("Acceso denegado para eliminar equipos fuera de tu área.");
}

// 1️⃣ Borrar reportes asociados
$stmt = $mysqli->prepare("DELETE FROM reporte_fallos WHERE id_equipo=?");
$stmt->bind_param("i", $id);
$stmt->execute();

// 2️⃣ Borrar mantenimientos asociados
$stmt = $mysqli->prepare("DELETE FROM mantenimientos WHERE equipo_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

// 3️⃣ Borrar préstamos asociados
$stmt = $mysqli->prepare("DELETE FROM prestamos WHERE equipo_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

// 4️⃣ Borrar el equipo
$del = $mysqli->prepare("DELETE FROM equipos WHERE id=? LIMIT 1");
$del->bind_param("i", $id);

//------------------insersion de la auditoria------------------
if ($del->execute()) {
    $descripcion = trim($equipo['tipo'] . ' ' . $equipo['marca'] . ' ' . $equipo['modelo']);

    $accion_msg = "Eliminó el equipo ID {$id} (Serial: {$equipo['serial_interno']}): '{$descripcion}'.";

    // CLAVE: Usamos el tipo de acción 'acción_equipo'
    auditar($accion_msg, 'acción_equipo');
}
//-------------------------------------------------------------

// Volver al listado
header("Location: equipos_index.php");
exit;