<?php
// public/equipos_index.php
require __DIR__ . '/../init.php';
require_login();

$rol = user()['rol'];

// Filtro por área según rol (temporal: bibliotecaria => area_id=1)
$where = "1=1";
$params = [];
$types  = "";

if ($rol !== 'admin') {
  $where = "e.area_id = ?";
  $params[] = 1;       // Biblioteca
  $types   .= "i";
}

// Query
$sql = "
  SELECT e.id, e.tipo, e.marca, e.modelo, e.estado, e.prestado, e.serial_interno,
         a.nombre AS area, s.nombre AS sala
  FROM equipos e
  JOIN areas a ON a.id = e.area_id
  LEFT JOIN salas s ON s.id = e.sala_id
  WHERE $where
  ORDER BY e.creado_en DESC
";
$stmt = $mysqli->prepare($sql);
if ($types) { $stmt->bind_param($types, ...$params); }
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Equipos — Inventario</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px;background:#111827}
    a{color:#93c5fd;text-decoration:none}
    .container{padding:24px}
    .actions{margin-bottom:16px}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:#2563eb;color:#fff}
    table{width:100%;border-collapse:collapse;background:#111827;border-radius:12px;overflow:hidden}
    th,td{padding:12px;border-bottom:1px solid #1f2937;text-align:left}
    th{background:#0b1220;color:#93c5fd}
    .badge{padding:4px 8px;border-radius:9999px;font-size:12px}
    .ok{background:#052e16;color:#bbf7d0}
    .warn{background:#1f2937;color:#fef08a}
    .bad{background:#450a0a;color:#fecaca}
    .muted{color:#9ca3af}
  </style>
</head>
<body>
  <header>
    <div><a href="/inventario_uni/index.php">← Panel</a></div>
    <div>Inventario — <span class="badge"><?=htmlspecialchars($rol)?></span></div>
    <div><?=htmlspecialchars(user()['nombre'])?> <a href="/inventario_uni/auth/logout.php">Salir</a></div>
  </header>

  <div class="container">
    <div class="actions">
      <a class="btn" href="/inventario_uni/public/equipos_nuevo.php">+ Nuevo equipo</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>Tipo</th>
          <th>Marca / Modelo</th>
          <th>Área / Sala</th>
          <th>Estado</th>
          <th>Prestado</th>
          <th>Acciones</th>
          <th>Serial interno</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$rows): ?>
          <tr><td colspan="7" class="muted">Sin equipos cargados aún.</td></tr>
        <?php else: foreach ($rows as $r): ?>
          <tr>
            <td><?=htmlspecialchars($r['tipo'])?></td>
            <td><?=htmlspecialchars(trim(($r['marca']??'') . ' ' . ($r['modelo']??'')))?></td>
            <td><?=htmlspecialchars($r['area'])?><?php if($r['sala']):?> / <?=htmlspecialchars($r['sala'])?><?php endif;?></td>
            <td>
              <?php
                $cls = 'ok';
                if ($r['estado']==='dañado' || $r['estado']==='fuera_servicio') $cls = 'bad';
                elseif ($r['estado']==='en_uso') $cls = 'warn';
              ?>
              <span class="badge <?=$cls?>"><?=htmlspecialchars($r['estado'])?></span>
            </td>
            <td><?= $r['prestado'] ? 'Sí' : 'No' ?></td>
            <td>
              <a href="equipos_editar.php?id=<?=$r['id']?>">Editar</a><br>
              <a href="equipos_eliminar.php?id=<?=$r['id']?>"
                 onclick="return confirm('¿Eliminar este equipo? También se borrarán sus componentes.');">
                 Eliminar
              </a><br>
              <?php if (!$r['prestado']): ?>
                <a href="prestamos_nuevo.php?equipo=<?=$r['id']?>">Prestar</a><br>
              <?php else: ?>
                <a href="prestamos_devolver.php?equipo=<?=$r['id']?>"
                   onclick="return confirm('¿Marcar devolución de este equipo?');">Devolver</a><br>
              <?php endif; ?>
            </td>
            <td>
              <a href="equipos_componentes.php?id=<?=$r['id']?>">Ver componentes</a><br>
              <a href="equipo_ver.php?serial=<?=urlencode($r['serial_interno'])?>" target="_blank">QR / Vista pública</a><br>
              <a href="equipo_qr.php?serial=<?=urlencode($r['serial_interno'])?>" target="_blank">Imprimir QR</a><br>
              <?=htmlspecialchars($r['serial_interno'])?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
