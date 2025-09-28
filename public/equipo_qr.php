<?php
require __DIR__ . '/../config/db.php';

$serial = trim($_GET['serial'] ?? '');
if ($serial === '') { http_response_code(400); die('Falta parámetro serial.'); }

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

$base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']==='on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$public_url = $base . "/inventario_uni/public/equipo_ver.php?serial=" . urlencode($serial);
$qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($public_url);

?>
<div class="card">
  <div class="title"><?= htmlspecialchars($equipo['tipo']) ?> <?= $equipo['marca'] ? '· ' . htmlspecialchars($equipo['marca']) : '' ?> <?= $equipo['modelo'] ?></div>
  <div class="muted">Área: <?= htmlspecialchars($equipo['area']) ?><?= $equipo['sala'] ? ' / '.htmlspecialchars($equipo['sala']) : '' ?></div>
  <img src="<?= $qr_url ?>" alt="QR del equipo">
  <div class="muted">Serial: <?= htmlspecialchars($equipo['serial_interno']) ?></div>
  <a class="btn noprint btn-public" href="#" onclick="openModal('equipo_ver.php?serial=<?= urlencode($equipo['serial_interno']) ?>&ajax=1'); return false;">Abrir ficha pública</a>
  <a class="btn noprint btn-print" href="#" onclick="window.print()">Imprimir</a>
</div>
