<?php
require __DIR__ . '/../config/db.php';

$serial = trim($_GET['serial'] ?? '');
if ($serial === '') { http_response_code(400); die('Falta parámetro serial.'); }

$stmt = $mysqli->prepare("SELECT e.*, a.nombre AS area, s.nombre AS sala,
        (SELECT COUNT(*) FROM prestamos p WHERE p.equipo_id=e.id AND p.fecha_devolucion IS NULL) as prestado
        FROM equipos e
        JOIN areas a ON a.id=e.area_id
        LEFT JOIN salas s ON s.id=e.sala_id
        WHERE e.serial_interno = ? LIMIT 1");
$stmt->bind_param('s', $serial);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) { http_response_code(404); die('Equipo no encontrado.'); }

$stmt = $mysqli->prepare("SELECT tipo, marca, modelo, estado, observacion FROM componentes WHERE equipo_id=? ORDER BY tipo");
$stmt->bind_param('i', $equipo['id']);
$stmt->execute();
$componentes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<div class="card">
  <h2><?=htmlspecialchars($equipo['tipo'])?><?= $equipo['marca']?' · '.htmlspecialchars($equipo['marca']):'' ?><?= $equipo['modelo']?' '.htmlspecialchars($equipo['modelo']):'' ?></h2>
  <p class="muted">Área: <?=htmlspecialchars($equipo['area'])?><?= $equipo['sala']?' / '.htmlspecialchars($equipo['sala']):'' ?></p>
  <p><strong>Serial interno:</strong> <?=htmlspecialchars($equipo['serial_interno'])?></p>
  <p><strong>Estado:</strong>
    <?php $cls='ok'; if($equipo['estado']==='dañado'||$equipo['estado']==='fuera_servicio')$cls='bad'; elseif($equipo['estado']==='en_uso')$cls='warn'; ?>
    <span class="badge <?=$cls?>"><?=htmlspecialchars($equipo['estado'])?></span> · <strong>Prestado:</strong> <?=$equipo['prestado']?'Sí':'No'?>
  </p>

  <h3>Componentes</h3>
  <table class="styled-table">
    <thead><tr><th>Tipo</th><th>Marca</th><th>Modelo</th><th>Estado</th><th>Obs</th></tr></thead>
    <tbody>
      <?php if(!$componentes): ?>
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
