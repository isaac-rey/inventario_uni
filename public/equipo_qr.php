<?php
// public/equipo_qr.php  (página lista para imprimir)
require __DIR__ . '/../config/db.php';

$serial = trim($_GET['serial'] ?? '');
if ($serial === '') { http_response_code(400); die('Falta parámetro serial.'); }

// Buscar equipo por serial
$stmt = $mysqli->prepare("
  SELECT e.tipo, e.marca, e.modelo, e.serial_interno, a.nombre AS area, s.nombre AS sala
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

// URL pública (la misma que escanea el cliente)
$base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'];
$public_url = $base . "/inventario_uni/public/equipo_ver.php?serial=" . urlencode($serial);

// QR (simple, MVP)
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($public_url);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Etiqueta QR — <?=htmlspecialchars($equipo['tipo'])?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    @media print {
      .noprint { display: none !important; }
      body { margin: 0; }
      .card { box-shadow: none; border: 1px solid #000; }
    }
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0;display:flex;justify-content:center}
    .card{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:12px;margin:16px;max-width:360px;text-align:center}
    .title{font-weight:700;margin:6px 0}
    .muted{color:#9ca3af;font-size:12px}
    img{display:block;margin:12px auto}
    .btn{display:inline-block;margin-top:8px;padding:8px 12px;border-radius:8px;background:#2563eb;color:#fff;text-decoration:none}
  </style>
</head>
<body>
  <div class="card">
    <div class="title"><?=htmlspecialchars($equipo['tipo'])?> <?= $equipo['marca']? '· '.htmlspecialchars($equipo['marca']):'' ?> <?= $equipo['modelo']? htmlspecialchars($equipo['modelo']):'' ?></div>
    <div class="muted">Área: <?=htmlspecialchars($equipo['area'])?><?= $equipo['sala']?' / '.htmlspecialchars($equipo['sala']):'' ?></div>
    <img src="<?=$qr_url?>" alt="QR" width="300" height="300">
    <div class="muted">Serial: <?=htmlspecialchars($equipo['serial_interno'])?></div>
    <a class="btn noprint" href="<?=$public_url?>" target="_blank">Abrir ficha pública</a>
    <a class="btn noprint" href="#" onclick="window.print()">Imprimir</a>
  </div>
</body>
</html>
