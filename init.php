<?php
// init.php
session_start();
require __DIR__ . '/config/db.php';

function is_logged_in(): bool {
  return isset($_SESSION['user']);
}

function user() {
  return $_SESSION['user'] ?? null;
}

function require_login() {
  if (!is_logged_in()) {
    header('Location: /inventario_uni/auth/login.php');
    exit;
  }
}

function require_role(string $role_name) {
  if (!is_logged_in() || ($_SESSION['user']['rol'] ?? '') !== $role_name) {
    http_response_code(403);
    echo "Acceso denegado";
    exit;
  }
}
