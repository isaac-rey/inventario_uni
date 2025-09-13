<?php
require __DIR__ . '/../config/db.php'; // ✅ conexión a la BD
require __DIR__ . '/../libraries/PHPMailer/src/Exception.php';
require __DIR__ . '/../libraries/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../libraries/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function enviarCorreoRecuperacion($ci, $asunto, $mensajeHtml, $mysqli) {
    // Buscar el email en la base de datos
    $stmt = $mysqli->prepare("SELECT email, nombre FROM usuarios WHERE ci = ? LIMIT 1");
    $stmt->bind_param('s', $ci);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if (!$user) {
        return ['exito' => false, 'mensaje' => 'No se encontró un usuario con ese CI.'];
    }

    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tu_correo@gmail.com';
        $mail->Password   = 'tu_clave_o_token_app';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('tu_correo@gmail.com', 'Inventario Universidad');
        $mail->addAddress($user['email'], $user['nombre']);

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $mensajeHtml;

        $mail->send();
        return ['exito' => true, 'mensaje' => "Correo enviado a {$user['email']}"];
    } catch (Exception $e) {
        return ['exito' => false, 'mensaje' => "Error al enviar correo: {$mail->ErrorInfo}"];
    }
}
