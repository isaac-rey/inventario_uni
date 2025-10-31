<?php
require __DIR__ . '/../init.php';
require_login();

$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// ✅ Consulta SQL explícita (sin ambigüedades ni omisiones)
$sql = "SELECT 
          e.id, e.area_id, e.sala_id, e.tipo, e.marca, e.modelo, 
          e.nro_serie, e.serial_interno, e.estado, 
          e.prestado, e.con_reporte, e.en_mantenimiento, e.con_fallos,
          e.detalles, e.creado_en, e.actualizado_en,
          a.nombre AS area, s.nombre AS sala,
          (SELECT COUNT(*) FROM prestamos p 
             WHERE p.equipo_id = e.id AND p.fecha_devolucion IS NULL) AS prestado_activo
        FROM equipos e
        LEFT JOIN areas a ON a.id = e.area_id
        LEFT JOIN salas s ON s.id = e.sala_id";

if ($search !== '') {
  $search_param = '%' . $search . '%';
  $sql .= " WHERE e.tipo LIKE ? 
            OR e.marca LIKE ? 
            OR e.modelo LIKE ? 
            OR e.serial_interno LIKE ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
} else {
  $stmt = $mysqli->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

// ✅ Normalizamos valores nulos o inexistentes
foreach ($rows as &$r) {
  $r['en_mantenimiento'] = (int)($r['en_mantenimiento'] ?? 0);
  $r['con_reporte'] = (int)($r['con_reporte'] ?? 0);
  $r['con_fallos'] = (int)($r['con_fallos'] ?? 0);
  $r['prestado_activo'] = (int)($r['prestado_activo'] ?? 0);
}
unset($r);

function highlight($text, $search)
{
  if (!$search) return htmlspecialchars($text);
  return preg_replace("/(" . preg_quote($search, '/') . ")/i", '<mark>$1</mark>', htmlspecialchars($text));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inventario de equipos</title>
  <link rel="stylesheet" href="../css/tabla_equipos_index.css">
  <style>
    .badge.danger {
      background-color: #c03934ff; /* rojo fuerte */
      color: #fff;
    }
  </style>
</head>

<body>
  <?php include __DIR__ . '/navbar.php'; ?>

  <div class="container">
    <div class="actions">
      <a class="btn" href="/inventario_uni/public/equipos_nuevo.php">+ Nuevo equipo</a>
      <form method="get">
        <input type="text" name="q" placeholder="Buscar equipo..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn">Buscar</button>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>Tipo</th>
          <th>Marca / Modelo</th>
          <th>Área / Sala</th>
          <th>Estado</th>
          <th>Acciones</th>
          <th>Serial interno</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$rows): ?>
          <tr>
            <td colspan="7" class="muted">Sin equipos cargados aún.</td>
          </tr>
        <?php else: foreach ($rows as $r): ?>
          <tr>
            <td data-label="Tipo"><?= highlight($r['tipo'], $search) ?></td>
            <td data-label="Marca / Modelo"><?= highlight(trim(($r['marca'] ?? '') . ' ' . ($r['modelo'] ?? '')), $search) ?></td>
            <td data-label="Área / Sala"><?= htmlspecialchars($r['area']) ?><?= $r['sala'] ? ' / ' . htmlspecialchars($r['sala']) : '' ?></td>

            <td data-label="Estado">
              <?php
              if ($r['en_mantenimiento'] > 0) {
                echo '<span class="badge warn">En mantenimiento</span>';
              } elseif ($r['con_fallos'] > 0) {
                echo '<span class="badge danger">Con fallos</span>';
              } elseif ($r['con_reporte'] > 0) {
                echo '<span class="badge warn">Con reporte</span>';
              } else {
                $cls = 'ok';
                if ($r['estado'] === 'dañado' || $r['estado'] === 'fuera_servicio') $cls = 'bad';
                elseif ($r['estado'] === 'En Uso') $cls = 'warn';
                echo '<span class="badge ' . $cls . '">' . htmlspecialchars($r['estado']) . '</span>';
              }
              ?>
            </td>

            <td data-label="Acciones">
              <a href="equipos_editar.php?id=<?= $r['id'] ?>">Editar</a><br>
              <a href="equipos_eliminar.php?id=<?= $r['id'] ?>" onclick="return confirm('¿Eliminar este equipo?');">Eliminar</a><br>

              <?php if ($r['en_mantenimiento'] && $r['con_reporte'] && $r['con_fallos']): ?>
                <a href="mantenimiento_volver.php?id_equipo=<?= $r['id'] ?>">Finalizar mantenimiento</a><br>

              <?php elseif ($r['prestado_activo'] > 0): ?>
                <a href="prestamos_devolver.php?equipo=<?= $r['id'] ?>" onclick="return confirm('¿Marcar devolución de este equipo?');">Devolver</a><br>
              <?php else: ?>
                <a href="prestamos_nuevo.php?equipo=<?= $r['id'] ?>">Prestar</a><br>
              <?php endif; ?>

              <a href="#" onclick="openModal('equipos_mantenimiento.php?id=<?= $r['id'] ?>&ajax=1'); return false;">Ver historial</a><br>

              <?php
              if ($r['con_reporte'] == 0 && $r['con_fallos'] == 0 && !$r['en_mantenimiento']) {
                echo '<a href="form_reporte_equipo.php?id_equipo=' . $r['id'] . '">Reportar fallo</a><br>';
              } elseif ($r['con_fallos'] == 1 && !$r['en_mantenimiento']) {
                echo '<a href="mantenimiento_enviar.php?id_equipo=' . $r['id'] . '">Enviar a mantenimiento</a><br>';
              }
              ?>
            </td>

            <td data-label="Serial interno">
              <div class="serial-section">
                <div class="qr-mini" title="Ver QR" onclick="openModal('equipo_qr.php?serial=<?= urlencode($r['serial_interno']) ?>&ajax=1')"></div>
                <div class="serial-code" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($r['serial_interno']) ?>')" title="Click para copiar">
                  <?= highlight($r['serial_interno'], $search) ?>
                </div>
                <div class="serial-actions">
                  <a href="equipos_componentes.php?id=<?= $r['id'] ?>" class="serial-btn btn-components">Componentes</a>
                  <a href="#" class="serial-btn btn-public" onclick="openModal('equipo_ver.php?serial=<?= urlencode($r['serial_interno']) ?>&ajax=1'); return false;">Ficha pública</a>
                </div>
              </div>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Modal global -->
  <div id="qrModal" class="modal-overlay" style="display:none;">
    <div class="modal-content">
      <span class="close-btn" onclick="closeModal()">&times;</span>
      <div id="qrModalBody">Cargando...</div>
    </div>
  </div>

  <script>
    function openModal(url) {
      const modal = document.getElementById('qrModal');
      const body = document.getElementById('qrModalBody');
      body.innerHTML = 'Cargando...';
      fetch(url)
        .then(r => r.text())
        .then(html => body.innerHTML = html)
        .catch(err => body.innerHTML = '<p class="muted">Error al cargar contenido.</p>');
      modal.style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('qrModal').style.display = 'none';
    }

    window.onclick = function(event) {
      const modal = document.getElementById('qrModal');
      if (event.target === modal) closeModal();
    }
  </script>

</body>
</html>
