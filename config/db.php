<?php
// config/db.php
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'inventario_uni';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
  die('Error de conexión MySQL: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
