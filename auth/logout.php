<?php
// auth/logout.php
session_start();
session_destroy();
header('Location: /inventario_uni/auth/login.php');
