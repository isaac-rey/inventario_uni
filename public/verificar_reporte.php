<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';
require_login();

header('Content-Type: application/json; charset=utf-8');

$reporte_id = intval($_POST['reporte_id'] ?? 0);
if ($reporte_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'ID de reporte inválido']);
    exit;
}

// Obtenemos datos del reporte
$stmt = $mysqli->prepare("SELECT id_equipo, tipo_fallo, descripcion_fallo, reporte_verificar FROM reporte_fallos WHERE id = ?");
$stmt->bind_param("i", $reporte_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Reporte no encontrado']);
    exit;
}
$row = $result->fetch_assoc();
$stmt->close();

if ($row['reporte_verificar'] == 1) {
    echo json_encode(['success' => false, 'error' => 'El reporte ya está verificado']);
    exit;
}

// Actualizamos el reporte
$update = $mysqli->prepare("UPDATE reporte_fallos SET reporte_verificar = 1 WHERE id = ?");
$update->bind_param("i", $reporte_id);

if ($update->execute()) {
    // Auditoría
    $usuario = user();
    $usuario_nombre = $usuario ? $usuario['nombre'].' '.$usuario['apellido'] : 'Anónimo';

    $equipo_data = null;
    if ($row['id_equipo']) {
        $stmt_eq = $mysqli->prepare("SELECT tipo, marca, modelo FROM equipos WHERE id = ?");
        $stmt_eq->bind_param("i", $row['id_equipo']);
        $stmt_eq->execute();
        $equipo_data = $stmt_eq->get_result()->fetch_assoc();
        $stmt_eq->close();
    }

    $equipo_desc = $equipo_data ? $equipo_data['tipo'].' '.$equipo_data['marca'].' '.$equipo_data['modelo'] : 'Equipo desconocido';
    $reporte_desc = "Fallo: {$row['tipo_fallo']}.";

    auditar("Verificó el reporte del equipo ({$equipo_desc}). {$reporte_desc}", 'verificacion');

    ob_clean();
    echo json_encode(['success' => true]);
    exit;

} else {
    echo json_encode(['success' => false, 'error' => 'Error al actualizar: ' . $mysqli->error]);
    exit;
}
