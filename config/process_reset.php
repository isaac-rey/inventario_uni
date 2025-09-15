<?php
// public/process_reset.php
session_start();
require __DIR__ . '/../config/db.php';

$token = $_POST['token'] ?? '';
$pass = $_POST['password'] ?? '';
$pass2 = $_POST['password2'] ?? '';

if ($pass !== $pass2 || strlen($pass) < 8) {
  die("Error: contraseñas inválidas o demasiado cortas.");
}

// Buscar token válido
$stmt = $mysqli->prepare("SELECT pr.id, pr.user_id, pr.expires_at, pr.used
                          FROM password_resets pr
                          WHERE pr.token = ? LIMIT 1");
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

// Marcar token usado
$stmt = $mysqli->prepare("UPDATE password_resets SET used = 1 WHERE id = ?");
$stmt->bind_param('i', $row['id']);
$stmt->execute();
$stmt->close();

echo "Contraseña actualizada. <a href='../auth/login.php'>Inicia sesión</a>";
