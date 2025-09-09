<?php
require __DIR__ . '/../estudiante_init.php';
require_est_login();
$e = est();

// Préstamos activos del estudiante
$stmt = $mysqli->prepare("
  SELECT p.id, p.equipo_id, p.fecha_entrega, p.observacion,
         e.tipo, e.marca, e.modelo, e.serial_interno
  FROM prestamos p
  JOIN equipos e ON e.id = p.equipo_id
  WHERE p.estudiante_id = ? AND p.estado = 'activo'
  ORDER BY p.fecha_entrega DESC
");
$stmt->bind_param("i", $e['id']);
$stmt->execute();
$activos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Historial (devueltos) del estudiante
$stmt = $mysqli->prepare("
  SELECT p.id, p.equipo_id, p.fecha_entrega, p.fecha_devolucion, p.observacion,
         e.tipo, e.marca, e.modelo, e.serial_interno
  FROM prestamos p
  JOIN equipos e ON e.id = p.equipo_id
  WHERE p.estudiante_id = ? AND p.estado = 'devuelto'
  ORDER BY p.fecha_devolucion DESC
  LIMIT 15
");
$stmt->bind_param("i", $e['id']);
$stmt->execute();
$historial = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel del estudiante</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px;background:#111827}
    a{color:#93c5fd;text-decoration:none}
    .container{padding:24px;max-width:1100px;margin:auto}
    .grid{display:grid;gap:16px;grid-template-columns:1fr}
    .card{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:16px}
    .muted{color:#9ca3af}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #1f2937;text-align:left}
    th{color:#93c5fd;background:#0b1220}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:#2563eb;color:#fff}
  </style>
</head>
<body>
  <header>
    <div>Inventario — Estudiante</div>
    <div><?=htmlspecialchars($e['nombre'].' '.$e['apellido'])?> · <a href="/inventario_uni/public/estudiantes_logout.php">Salir</a></div>
  </header>

  <div class="container">
    <div class="grid">
      <div class="card">
        <h3>¡Hola, <?=htmlspecialchars($e['nombre'])?>!</h3>
        <p class="muted">Podés escanear el QR de un equipo para pedir préstamo o devolverlo.</p>
        <p style="margin-top:12px">
          <a class="btn" href="/inventario_uni/public/estudiante_scan.php">📷 Escanear QR de un equipo</a>
        </p>
      </div>

      <div class="card">
        <h2>Mis préstamos activos (<?=count($activos)?>)</h2>
        <table>
          <thead>
            <tr>
              <th>Equipo</th>
              <th>Serial</th>
              <th>Entregado</th>
              <th>Obs</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$activos): ?>
              <tr><td colspan="5" class="muted">No tenés préstamos activos.</td></tr>
            <?php else: foreach ($activos as $p): ?>
              <tr>
                <td><?=htmlspecialchars($p['tipo'].' '.$p['marca'].' '.$p['modelo'])?></td>
                <td>
                  <a href="/inventario_uni/public/estudiante_equipo.php?serial=<?=urlencode($p['serial_interno'])?>">
                    <?=htmlspecialchars($p['serial_interno'])?>
                  </a>
                </td>
                <td><?=htmlspecialchars($p['fecha_entrega'])?></td>
                <td><?=htmlspecialchars($p['observacion'] ?? '')?></td>
                <td>
                  <a class="btn" href="/inventario_uni/public/estudiante_equipo.php?serial=<?=urlencode($p['serial_interno'])?>">
                    Ver / Devolver
                  </a>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <div class="card">
        <h2>Historial reciente</h2>
        <table>
          <thead>
            <tr>
              <th>Equipo</th>
              <th>Serial</th>
              <th>Entregado</th>
              <th>Devuelto</th>
              <th>Obs</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$historial): ?>
              <tr><td colspan="5" class="muted">Todavía no hay devoluciones registradas.</td></tr>
            <?php else: foreach ($historial as $p): ?>
              <tr>
                <td><?=htmlspecialchars($p['tipo'].' '.$p['marca'].' '.$p['modelo'])?></td>
                <td><?=htmlspecialchars($p['serial_interno'])?></td>
                <td><?=htmlspecialchars($p['fecha_entrega'])?></td>
                <td><?=htmlspecialchars($p['fecha_devolucion'])?></td>
                <td><?=htmlspecialchars($p['observacion'] ?? '')?></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
