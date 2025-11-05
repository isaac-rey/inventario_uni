<?php
// C:\xampp\htdocs\inventario_uni-main\config\process_reset.php

ob_start(); // Evita cualquier salida previa que bloquee el header()

require __DIR__ . '/../init.php';

$token = $_POST['token'] ?? '';
$pass  = $_POST['password'] ?? '';
$pass2 = $_POST['password2'] ?? '';

if ($pass !== $pass2 || strlen($pass) < 8) {
    die("Error: contraseñas inválidas o demasiado cortas.");
}

// Buscar token válido
$stmt = $mysqli->prepare("
    SELECT pr.id, pr.user_id, pr.expires_at, pr.used
    FROM password_resets pr
    WHERE pr.token = ? 
    LIMIT 1
");
$stmt->bind_param('s', $token);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row || $row['used'] || strtotime($row['expires_at']) < time()) {
    die("Token inválido o expirado.");
}

// Hashear contraseña y actualizar
$hash = password_hash($pass, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("UPDATE usuarios SET password_hash = ? WHERE id = ?");
$stmt->bind_param('si', $hash, $row['user_id']);
$stmt->execute();
$stmt->close();

// Marcar token como usado
$stmt = $mysqli->prepare("UPDATE password_resets SET used = 1 WHERE id = ?");
$stmt->bind_param('i', $row['id']);
$stmt->execute();
$stmt->close();

// Registrar auditoría
$user_id_to_audit = $row['user_id']; 
auditar("Contraseña restablecida exitosamente.", 'contra_restablecimiento', $user_id_to_audit);

// Redirigir al login
$login_url = '../auth/login.php';
header("Location: $login_url");
exit;

// Si por alguna razón header() falla, mostrar enlace manual
ob_end_flush();
echo "Contraseña actualizada correctamente. <a href=\"$login_url\">Inicia sesión</a>.";