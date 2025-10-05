<?php
// init.php
session_start();
require __DIR__ . '/config/db.php';

function is_logged_in(): bool
{
  return isset($_SESSION['user']);
}

function user()
{
  return $_SESSION['user'] ?? null;
}

function require_login()
{
  if (!is_logged_in()) {
    header('Location: /inventario_uni/auth/login.php');
    exit;
  }
}

function require_role(string $role_name)
{
  if (!is_logged_in() || ($_SESSION['user']['rol'] ?? '') !== $role_name) {
    http_response_code(403);
    echo "Acceso denegado";
    exit;
  }
}


// Función para auditar acciones de usuarios
function auditar($accion)
{
  global $mysqli;
  $usuario = user();
  //Agrega esta verificación
  if (!$usuario || !isset($usuario['id'])) {
    error_log("AUDITORIA FALLIDA: No se pudo obtener el ID del usuario.");
    return; // Salir de la función si no hay ID de usuario.
  }

  $usuario_id = $usuario['id'];

  $ip = $_SERVER['REMOTE_ADDR'] ?? null;
  $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;

  $stmt = $mysqli->prepare("INSERT INTO auditoria (usuario_id, accion, ip_usuario, user_agent, fecha) VALUES (?, ?, ?, ?, NOW())");


  if ($stmt === false) {
    error_log("Error al preparar la auditoría: " . $mysqli->error);
    return;
  }

  $stmt->bind_param("isss", $usuario_id, $accion, $ip, $user_agent);

  if (!$stmt->execute()) {
    error_log("Error al ejecutar la auditoría: " . $stmt->error);
  }
  $stmt->close();
}
