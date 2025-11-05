<?php
// init.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
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
/******************************************************************** */
function auditar($accion, $tipo_accion = 'general', $override_user_id = null) // <-- 1. ACEPTAR NUEVO PARÁMETRO
{
  global $mysqli;

  $usuario_id = null;

  // Prioridad 1: Usar el ID pasado como override (ideal para forgot_password)
  if ($override_user_id !== null) {
    $usuario_id = intval($override_user_id);
  }
  // Prioridad 2: Usar el ID del usuario logueado
  else {
    $usuario = user();
    if ($usuario && isset($usuario['id'])) {
      $usuario_id = $usuario['id'];
    }
  }
  // 2. Manejo de usuario desconocido/no logueado
  if ($usuario_id === null) {
    // Asumiendo que 0 es un ID reservado para el sistema/invitado. 
    $usuario_id = 0; // ID para "Anónimo" o "Sistema"
  }
  /*********************************** */

  $ip = $_SERVER['REMOTE_ADDR'] ?? null;
  $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? null;

  // 3. Modificar la consulta SQL para incluir tipo_accion (en una sola línea con comillas inversas para delimitar tablas y columnas)
  $stmt = $mysqli->prepare("INSERT INTO `auditoria` (`usuario_id`, `accion`, `tipo_accion`, `ip_usuario`, `user_agent`, `fecha`) VALUES (?, ?, ?, ?, ?, NOW())");


  if ($stmt === false) {
    error_log("Error al preparar la auditoría: " . $mysqli->error);
    return;
  }

  // 4. Modificar el bind_param
  // Original: "isss" (usuario_id, accion, ip, user_agent)
  // Nuevo: "issss" (usuario_id, accion, tipo_accion, ip, user_agent)
  $stmt->bind_param("issss", $usuario_id, $accion, $tipo_accion, $ip, $user_agent); // <-- CLAVE: 's' para tipo_accion

  if (!$stmt->execute()) {
    error_log("Error al ejecutar la auditoría: " . $stmt->error);
  }
  $stmt->close();
}
