<?php
// Archivo: /public/prestamos/prestamo_cancelar_ajax.php
// Versión mejorada para depuración

require __DIR__ . '/../init.php'; // ASEGÚRATE DE QUE ESTA RUTA ES CORRECTA PARA TU ARCHIVO init.php
require_login();

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$id = $data['id'] ?? null;
// El motivo se ignora en esta versión para la depuración
$motivo = $data['motivo'] ?? 'Cancelado por administrador/staff.';

if (!is_numeric($id) || $id <= 0) {
    echo json_encode(['error' => 'ID de préstamo inválido.']);
    exit;
}

global $mysqli;

// --- PASO DE DEPURACIÓN CRÍTICO ---
if (!$mysqli) {
    echo json_encode(['error' => 'ERROR FATAL: La conexión a la base de datos ($mysqli) no está disponible. Verifique que init.php se incluyó correctamente.']);
    exit;
}
// --- FIN PASO DE DEPURACIÓN ---

// 1. QUERY SIMPLIFICADA: Solo cambiamos el estado para reducir puntos de fallo.
// No incluimos 'fecha_cierre' ya que podría no existir.
$sql = "
    UPDATE prestamos 
    SET estado = 'cancelado' 
    WHERE id = ? AND estado = 'pendiente'
";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    // Si la preparación falla (ej: error de sintaxis o columna inexistente)
    echo json_encode(['error' => 'Error al preparar la consulta SQL: ' . $mysqli->error . '. SQL: ' . $sql]);
    exit;
}

try {
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        // Si la ejecución falla (ej: problema de permisos)
        echo json_encode(['error' => 'Error al ejecutar la consulta: ' . $stmt->error]);
        $stmt->close();
        exit;
    }

    if ($stmt->affected_rows > 0) {

        //-------------------------INSERCIÓN DE LA AUDITORÍA---------------------
        $accion_msg = "Canceló/Rechazó la solicitud de Préstamo ID {$id}. Motivo: {$motivo}.";
        // El ID del usuario que realiza la acción se toma de la sesión (user())
        auditar($accion_msg);
        // --------------------------------

        echo json_encode(['ok' => 'La solicitud de préstamo (ID: ' . $id . ') ha sido cancelada correctamente.']);
    } else {
        echo json_encode(['error' => 'No se pudo cancelar la solicitud. Verifique que el estado sea "pendiente".']);
    }

    $stmt->close();
} catch (Exception $e) {
    // El catch genérico
    error_log("Error al cancelar préstamo: " . $e->getMessage());
    echo json_encode(['error' => 'Error interno del servidor al procesar la cancelación. Detalles: ' . $e->getMessage()]);
}
