<?php
// estudiante_init.php
session_start();
require __DIR__ . '/config/db.php';

function est_logged_in(): bool {
  return isset($_SESSION['est']);
}

function est() {
  return $_SESSION['est'] ?? null;
}

function require_est_login() {
  if (!est_logged_in()) {
    header('Location: /inventario_uni/estudiantes_login.php');
    exit;
  }
}
