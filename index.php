<?php
require __DIR__ . '/init.php';
require_login();

$rol = user()['rol'];

// Consultas rápidas
$totEstudiantes = $mysqli->query("SELECT COUNT(*) AS total FROM estudiantes")->fetch_assoc()['total'];
$totDocentes = $mysqli->query("SELECT COUNT(*) AS total FROM docentes")->fetch_assoc()['total'];
$totEquipos = $mysqli->query("SELECT COUNT(*) AS total FROM equipos")->fetch_assoc()['total'];
$totPrestamos = $mysqli->query("SELECT COUNT(*) AS total FROM prestamos")->fetch_assoc()['total'];
$totPrestamosActivos = $mysqli->query("SELECT COUNT(*) AS total FROM prestamos WHERE estado='activo'")->fetch_assoc()['total'];
$totReportes = $mysqli->query("SELECT COUNT(*) AS total FROM reporte_fallos")->fetch_assoc()['total'];
$totMantenimiento = $mysqli->query("SELECT COUNT(*) AS total FROM equipos WHERE en_mantenimiento=1")->fetch_assoc()['total'];
$totComponentes = $mysqli->query("SELECT COUNT(*) AS total FROM componentes")->fetch_assoc()['total'];
$totAreas = $mysqli->query("SELECT COUNT(*) AS total FROM areas")->fetch_assoc()['total'];
$totSalas = $mysqli->query("SELECT COUNT(*) AS total FROM salas")->fetch_assoc()['total'];
$totUsuarios = $mysqli->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel — Inventario Universidad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="dashboard.css">
</head>
<?php include __DIR__ . '/public/navbar.php'; ?>
<body>

  <main class="dashboard">
    <h1>Panel de Control</h1>
    <div class="cards">
      <div class="card">
        <h2><?= $totEstudiantes ?></h2>
        <p>Estudiantes</p>
      </div>
      <div class="card">
        <h2><?= $totDocentes ?></h2>
        <p>Docentes</p>
      </div>
      <div class="card">
        <h2><?= $totEquipos ?></h2>
        <p>Equipos</p>
      </div>
      <div class="card">
        <h2><?= $totPrestamos ?></h2>
        <p>Préstamos Totales</p>
      </div>
      <div class="card">
        <h2><?= $totPrestamosActivos ?></h2>
        <p>Préstamos Activos</p>
      </div>
      <div class="card">
        <h2><?= $totReportes ?></h2>
        <p>Reportes</p>
      </div>
      <div class="card">
        <h2><?= $totMantenimiento ?></h2>
        <p>En Mantenimiento</p>
      </div>
      <div class="card">
        <h2><?= $totComponentes ?></h2>
        <p>Componentes</p>
      </div>
      <div class="card">
        <h2><?= $totAreas ?></h2>
        <p>Áreas</p>
      </div>
      <div class="card">
        <h2><?= $totSalas ?></h2>
        <p>Salas</p>
      </div>
      <div class="card">
        <h2><?= $totUsuarios ?></h2>
        <p>Usuarios</p>
      </div>
    </div>
  </main>
</body>
</html>
