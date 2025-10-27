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
//function auditar($accion)
  /******************************************************************** */
function auditar($accion, $override_user_id = null)
{
  global $mysqli;
  /*$usuario = user();
  //Agrega esta verificación
  if (!$usuario || !isset($usuario['id'])) {
    error_log("AUDITORIA FALLIDA: No se pudo obtener el ID del usuario.");
    return; // Salir de la función si no hay ID de usuario.
  }

  //$usuario_id = $usuario['id'];*/

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
    // En lugar de salir, usamos un ID predefinido para acciones de "Sistema" o "Anónimo".
    // Asumiendo que 0 es un ID reservado para el sistema/invitado. 
    // Si tu tabla de usuarios no permite 0, usa 1 si 1 es el administrador o elige un ID reservado.
    $usuario_id = 0; // ID para "Anónimo" o "Sistema"
    // error_log("AUDITORIA: Se registró una acción sin ID de sesión (ID: 0).");
  }
  /*********************************** */

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
