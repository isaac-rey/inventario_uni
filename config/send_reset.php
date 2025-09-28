<?php
// public/send_reset.php
session_start();
require __DIR__ . '/../config/db.php';

$email = trim($_POST['email'] ?? '');
if ($email === '') {
  $_SESSION['flash'] = 'Introduce tu correo.';
  header('Location: forgot.php');
  exit;
}

// Buscar usuario
$stmt = $mysqli->prepare("SELECT id, nombre FROM usuarios WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$mensajeGenerico = 'Si existe una cuenta con ese correo, se enviará un enlace de restablecimiento. Revisa tu bandeja y SPAM.';

if (!$user) {
  $_SESSION['flash'] = $mensajeGenerico;
  header('Location: forgot.php');
  exit;
}

// Crear token
$token = bin2hex(random_bytes(32));
$expira = date('Y-m-d H:i:s', time() + 3600);

$stmt = $mysqli->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)");
$stmt->bind_param('iss', $user['id'], $token, $expira);
$stmt->execute();
$stmt->close();

// Enlace (ajusta tu dominio/proyecto)
$reset_link = "http://localhost/inventario_uni/public/reset.php?token=" . urlencode($token);

// --- ENVÍO DE CORREO ---
// Opción rápida con mail() (en XAMPP casi nunca funciona sin configurar sendmail)
$subject = "Restablecer contraseña";
$body = "
  Hola {$user['nombre']}<br><br>
  Haz clic en el siguiente enlace para crear una nueva contraseña:<br>
  <a href='$reset_link'>$reset_link</a><br><br>
  Este enlace vence en 1 hora.
";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: no-reply@inventario_uni\r\n";

@mail($email, $subject, $body, $headers);

$_SESSION['flash'] = $mensajeGenerico;
header('Location: forgot.php');
exit;
