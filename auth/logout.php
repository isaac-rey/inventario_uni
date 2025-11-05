<?php
// auth/logout.php

require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';

// Asegurar que la sesión esté activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si existe un usuario logueado, registrar auditoría antes de cerrar la sesión
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $user_desc = htmlspecialchars($user['nombre'] . ' (Rol: ' . $user['rol'] . ')');
    auditar("Cierre de sesión exitoso. Usuario: {$user_desc}", 'cierre_sesión');
}

// Limpiar todos los datos de sesión
$_SESSION = [];

// Destruir la cookie de sesión (por seguridad)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruir la sesión
session_destroy();

// Redirigir al login
header('Location: /inventario_uni/auth/login.php');
exit;