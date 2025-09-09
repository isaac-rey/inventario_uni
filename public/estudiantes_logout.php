<?php
// public/estudiantes_logout.php
session_start();
unset($_SESSION['est']);
header('Location: /inventario_uni/public/estudiantes_login.php');
