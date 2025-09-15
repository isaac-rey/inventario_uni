<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '../libraries/PHPMailer/src/PHPMailer.php';
require __DIR__ . '../libraries/PHPMailer/src/SMTP.php';
require __DIR__ . '../libraries/PHPMailer/src/Exception.php';

function getMailer(): PHPMailer {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pmalo2570@gmail.com'; // tu Gmail
        $mail->Password   = 'fsaz fofv wmrx vjyq'; // contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('pmalo2570@gmail.com', 'Inventario Universidad');
        $mail->isHTML(true);

    } catch (Exception $e) {
        die("Error al configurar PHPMailer: {$mail->ErrorInfo}");
    }

    return $mail;
}

