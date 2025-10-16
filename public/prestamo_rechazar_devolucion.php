<?php
require __DIR__ . '/../init.php';
require_login();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);
$motivo = trim($data['motivo'] ?? '');

if ($id <= 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

// Buscar devolución pendiente
$stmt = $mysqli->prepare("
    SELECT d.id, d.prestamo_id
    FROM devoluciones d
    JOIN prestamos p ON p.id = d.prestamo_id
    WHERE p.id=? AND d.estado='pendiente' LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$dev = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$dev) {
    echo json_encode(['error' => 'No se encontró la solicitud pendiente.']);
    exit;
}

try {
    $stmt = $mysqli->prepare("UPDATE devoluciones SET estado='rechazada', observacion=CONCAT(IFNULL(observacion,''), '\nMotivo rechazo: ', ?) WHERE id=?");
    $stmt->bind_param("si", $motivo, $dev['id']);
    $stmt->execute();
    $stmt->close();
    echo json_encode(['ok' => '❌ Solicitud de devolución rechazada.']);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error al rechazar devolución: ' . $e->getMessage()]);
}
exit;
