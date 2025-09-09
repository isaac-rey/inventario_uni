<?php
// public/prestamos_index.php
require __DIR__ . '/../init.php';
require_login();

// Préstamos activos
$act = $mysqli->query("
  SELECT p.id, p.equipo_id, p.fecha_entrega, p.observacion,
         e.tipo, e.marca, e.modelo, e.serial_interno,
         est.ci, est.nombre, est.apellido
  FROM prestamos p
  JOIN equipos e     ON e.id = p.equipo_id
  JOIN estudiantes est ON est.id = p.estudiante_id
  WHERE p.estado = 'activo'
  ORDER BY p.fecha_entrega DESC
")->fetch_all(MYSQLI_ASSOC);

// Historial (devueltos)
$hist = $mysqli->query("
  SELECT p.id, p.equipo_id, p.fecha_entrega, p.fecha_devolucion, p.observacion,
         e.tipo, e.marca, e.modelo, e.serial_interno,
         est.ci, est.nombre, est.apellido
  FROM prestamos p
  JOIN equipos e     ON e.id = p.equipo_id
  JOIN estudiantes est ON est.id = p.estudiante_id
  WHERE p.estado = 'devuelto'
  ORDER BY p.fecha_devolucion DESC
  LIMIT 20
")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Préstamos — Inventario</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px;background:#111827}
    a{color:#93c5fd;text-decoration:none}
    .container{padding:24px}
    .card{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:16px;margin-bottom:24px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #1f2937;text-align:left}
    th{color:#93c5fd;background:#0b1220}
    .muted{color:#9ca3af}
    .btn{display:inline-block;padding:6px 10px;border-radius:8px;background:#2563eb;color:#fff}
    .pill{padding:2px 8px;border-radius:9999px;background:#1f2937;color:#93c5fd;font-size:12px}
  </style>
</head>
<body>
  <header>
    <div><a href="/inventario_uni/index.php">← Panel</a></div>
    <div><?=htmlspecialchars(user()['nombre'])?> (<?=htmlspecialchars(user()['rol'])?>)</div>
  </header>

  <div class="container">
    <div class="card">
      <h2>Préstamos activos <span class="pill"><?=count($act)?></span></h2>
      <table>
        <thead>
          <tr>
            <th>Equipo</th>
            <th>Serial</th>
            <th>Estudiante</th>
            <th>Entregado</th>
            <th>Obs</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$act): ?>
            <tr><td colspan="6" class="muted">No hay préstamos activos.</td></tr>
          <?php else: foreach ($act as $p): ?>
            <tr>
              <td><?=htmlspecialchars($p['tipo'].' '.$p['marca'].' '.$p['modelo'])?></td>
              <td><a href="equipo_ver.php?serial=<?=urlencode($p['serial_interno'])?>" target="_blank"><?=htmlspecialchars($p['serial_interno'])?></a></td>
              <td><?=htmlspecialchars($p['nombre'].' '.$p['apellido'])?> (CI: <?=htmlspecialchars($p['ci'])?>)</td>
              <td><?=htmlspecialchars($p['fecha_entrega'])?></td>
              <td><?=htmlspecialchars($p['observacion'] ?? '')?></td>
              <td>
                <a class="btn" href="prestamos_devolver.php?equipo=<?=$p['equipo_id']?>"
                   onclick="return confirm('¿Marcar devolución de este equipo?');">Devolver</a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>

    <div class="card">
      <h2>Historial reciente (devueltos)</h2>
      <table>
        <thead>
          <tr>
            <th>Equipo</th>
            <th>Serial</th>
            <th>Estudiante</th>
            <th>Entregado</th>
            <th>Devuelto</th>
            <th>Obs</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$hist): ?>
            <tr><td colspan="6" class="muted">Sin devoluciones aún.</td></tr>
          <?php else: foreach ($hist as $p): ?>
            <tr>
              <td><?=htmlspecialchars($p['tipo'].' '.$p['marca'].' '.$p['modelo'])?></td>
              <td><?=htmlspecialchars($p['serial_interno'])?></td>
              <td><?=htmlspecialchars($p['nombre'].' '.$p['apellido'])?> (CI: <?=htmlspecialchars($p['ci'])?>)</td>
              <td><?=htmlspecialchars($p['fecha_entrega'])?></td>
              <td><?=htmlspecialchars($p['fecha_devolucion'])?></td>
              <td><?=htmlspecialchars($p['observacion'] ?? '')?></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
