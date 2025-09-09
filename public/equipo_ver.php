<?php
// public/equipo_ver.php (público, SIN login)
require __DIR__ . '/../config/db.php';

$serial = trim($_GET['serial'] ?? '');
if ($serial === '') { http_response_code(400); die('Falta parámetro serial.'); }

// Buscar equipo por serial_interno
$stmt = $mysqli->prepare("
  SELECT e.*, a.nombre AS area, s.nombre AS sala
  FROM equipos e
  JOIN areas a ON a.id=e.area_id
  LEFT JOIN salas s ON s.id=e.sala_id
  WHERE e.serial_interno = ?
  LIMIT 1
");
$stmt->bind_param('s', $serial);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) { http_response_code(404); die('Equipo no encontrado.'); }

// Componentes del equipo
$stmt = $mysqli->prepare("SELECT tipo, marca, modelo, estado, observacion FROM componentes WHERE equipo_id=? ORDER BY tipo");
$stmt->bind_param('i', $equipo['id']);
$stmt->execute();
$componentes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// URL pública (esta misma) para el QR
$base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
      . "://".$_SERVER['HTTP_HOST'];
$public_url = $base . "/inventario_uni/public/equipo_ver.php?serial=" . urlencode($serial);

// QR (usamos un generador público para el MVP)
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=" . urlencode($public_url);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Equipo — Vista pública</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    .container{max-width:900px;margin:auto;padding:24px}
    .grid{display:grid;gap:16px;grid-template-columns:1fr 240px}
    .card{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:16px}
    h1{margin:0 0 6px}
    .sub{color:#93c5fd;margin:0 0 16px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #1f2937;text-align:left}
    th{color:#93c5fd}
    .badge{padding:4px 8px;border-radius:9999px;font-size:12px}
    .ok{background:#052e16;color:#bbf7d0}
    .warn{background:#1f2937;color:#fef08a}
    .bad{background:#450a0a;color:#fecaca}
    .muted{color:#9ca3af}
    .actions{margin-top:10px}
    .btn{display:inline-block;padding:8px 12px;border-radius:8px;background:#2563eb;color:#fff;text-decoration:none}
    @media(max-width:800px){.grid{grid-template-columns:1fr}}
  </style>
</head>
<body>
  <div class="container">
    <div class="grid">
      <div class="card">
        <h1><?=htmlspecialchars($equipo['tipo'])?><?= $equipo['marca']?' · '.htmlspecialchars($equipo['marca']):'' ?><?= $equipo['modelo']?' '.htmlspecialchars($equipo['modelo']):'' ?></h1>
        <p class="sub">Área: <?=htmlspecialchars($equipo['area'])?><?= $equipo['sala']?' / '.htmlspecialchars($equipo['sala']):'' ?></p>

        <p><strong>Serial interno:</strong> <?=htmlspecialchars($equipo['serial_interno'])?></p>
        <p><strong>Estado:</strong>
          <?php
            $cls='ok';
            if ($equipo['estado']==='dañado' || $equipo['estado']==='fuera_servicio') $cls='bad';
            elseif ($equipo['estado']==='en_uso') $cls='warn';
          ?>
          <span class="badge <?=$cls?>"><?=htmlspecialchars($equipo['estado'])?></span>
          · <strong>Prestado:</strong> <?=$equipo['prestado']?'Sí':'No'?>
        </p>

        <h3>“Lo que trae”</h3>
        <table>
          <thead><tr><th>Tipo</th><th>Marca</th><th>Modelo</th><th>Estado</th><th>Obs</th></tr></thead>
          <tbody>
          <?php if (!$componentes): ?>
            <tr><td colspan="5" class="muted">Sin componentes cargados.</td></tr>
          <?php else: foreach($componentes as $c): ?>
            <tr>
              <td><?=htmlspecialchars($c['tipo'])?></td>
              <td><?=htmlspecialchars($c['marca'])?></td>
              <td><?=htmlspecialchars($c['modelo'])?></td>
              <td><?=htmlspecialchars($c['estado'])?></td>
              <td><?=htmlspecialchars($c['observacion'])?></td>
            </tr>
          <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <div class="card" style="text-align:center">
        <img src="<?=$qr_url?>" alt="QR del equipo" width="240" height="240">
        <div class="actions">
          <a class="btn" href="<?=$public_url?>" target="_blank">Abrir enlace</a>
          <div class="muted" style="margin-top:8px;word-break:break-all"><?=$public_url?></div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
