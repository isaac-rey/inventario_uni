<?php
// public/estudiante_equipo.php
require __DIR__ . '/../estudiante_init.php';
require_est_login();
$e = est();

$serial = trim($_GET['serial'] ?? '');
if ($serial === '') { die("Serial no especificado."); }

// Traer equipo por serial + sus componentes
$stmt = $mysqli->prepare("
  SELECT e.*, a.nombre AS area, s.nombre AS sala
  FROM equipos e
  JOIN areas a ON a.id = e.area_id
  LEFT JOIN salas s ON s.id = e.sala_id
  WHERE e.serial_interno = ?
  LIMIT 1
");
$stmt->bind_param("s", $serial);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) { die("Equipo no encontrado."); }

$equipo_id = intval($equipo['id']);

// Componentes del equipo
$stmt = $mysqli->prepare("SELECT * FROM componentes WHERE equipo_id=? ORDER BY creado_en DESC");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$componentes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// ¿Tiene préstamo activo? ¿De quién?
$stmt = $mysqli->prepare("
  SELECT p.*, est.ci, est.nombre, est.apellido
  FROM prestamos p
  JOIN estudiantes est ON est.id = p.estudiante_id
  WHERE p.equipo_id=? AND p.estado='activo'
  ORDER BY p.fecha_entrega DESC
  LIMIT 1
");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$prestamo_act = $stmt->get_result()->fetch_assoc();

$yo_lo_tengo = $prestamo_act && intval($prestamo_act['estudiante_id']) === intval($e['id']);

$error = '';
$ok = false;

// Acciones del estudiante: pedir préstamo o devolver
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $accion = $_POST['accion'] ?? '';
  if ($accion === 'prestar') {
    $obs = trim($_POST['observacion'] ?? '');

    // Validaciones
    if ($prestamo_act) {
      $error = "Este equipo ya está prestado.";
    } else {
      // Crear préstamo
      $stmt = $mysqli->prepare("INSERT INTO prestamos (equipo_id, estudiante_id, observacion) VALUES (?,?,?)");
      $stmt->bind_param("iis", $equipo_id, $e['id'], $obs);
      $stmt->execute();

      // Marcar equipo en uso
      $stmt = $mysqli->prepare("UPDATE equipos SET prestado=1, estado='en_uso' WHERE id=? LIMIT 1");
      $stmt->bind_param("i", $equipo_id);
      $stmt->execute();

      // Refrescar para ver estado
      header("Location: estudiante_equipo.php?serial=".urlencode($serial));
      exit;
    }
  } elseif ($accion === 'devolver') {
    if (!$prestamo_act) {
      $error = "No hay préstamo activo para este equipo.";
    } elseif (!$yo_lo_tengo) {
      $error = "Solo quien tiene el préstamo puede devolverlo.";
    } else {
      // Cerrar préstamo
      $stmt = $mysqli->prepare("UPDATE prestamos SET estado='devuelto', fecha_devolucion=NOW() WHERE id=? LIMIT 1");
      $stmt->bind_param("i", $prestamo_act['id']);
      $stmt->execute();

      // Marcar equipo como disponible (podés ajustar a otro estado si querés)
      $stmt = $mysqli->prepare("UPDATE equipos SET prestado=0, estado='bueno' WHERE id=? LIMIT 1");
      $stmt->bind_param("i", $equipo_id);
      $stmt->execute();

      header("Location: estudiante_equipo.php?serial=".urlencode($serial));
      exit;
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Equipo — Estudiante</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px;background:#111827}
    a{color:#93c5fd;text-decoration:none}
    .container{padding:24px;max-width:1000px;margin:auto}
    .grid{display:grid;gap:24px;grid-template-columns:1.2fr .8fr}
    .card{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:16px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #1f2937;text-align:left}
    th{color:#93c5fd;background:#0b1220}
    .badge{padding:4px 8px;border-radius:9999px;font-size:12px}
    .ok{background:#052e16;color:#bbf7d0}
    .warn{background:#1f2937;color:#fef08a}
    .bad{background:#450a0a;color:#fecaca}
    input,textarea{width:100%;padding:10px;border-radius:8px;border:1px solid #374151;background:#0b1220;color:#e5e7eb}
    button{padding:10px 14px;border:0;border-radius:8px;background:#2563eb;color:#fff;cursor:pointer;font-weight:600}
    .muted{color:#9ca3af}
    .error{background:#7f1d1d;color:#fecaca;padding:10px;border-radius:8px;margin-bottom:12px}
  </style>
</head>
<body>
  <header>
    <div><a href="/inventario_uni/public/estudiante_panel.php">← Panel estudiante</a></div>
    <div><?=htmlspecialchars($e['nombre'].' '.$e['apellido'])?> · <a href="/inventario_uni/public/estudiantes_logout.php">Salir</a></div>
  </header>

  <div class="container">
    <div class="grid">
      <div class="card">
        <h2><?=htmlspecialchars($equipo['tipo'].' · '.$equipo['marca'].' '.$equipo['modelo'])?></h2>
        <p class="muted">Área: <?=htmlspecialchars($equipo['area'])?><?= $equipo['sala'] ? ' / '.htmlspecialchars($equipo['sala']) : '' ?></p>
        <p class="muted">Serial: <?=htmlspecialchars($equipo['serial_interno'])?></p>
        <p>
          Estado:
          <?php
            $cls = 'ok';
            if ($equipo['estado']==='dañado' || $equipo['estado']==='fuera_servicio') $cls='bad';
            elseif ($equipo['estado']==='en_uso') $cls='warn';
          ?>
          <span class="badge <?=$cls?>"><?=htmlspecialchars($equipo['estado'])?></span>
          · Prestado: <?= $equipo['prestado'] ? 'Sí' : 'No' ?>
        </p>

        <?php if ($prestamo_act): ?>
          <p class="muted">Prestado a: <strong><?=htmlspecialchars($prestamo_act['nombre'].' '.$prestamo_act['apellido'])?></strong> (CI: <?=htmlspecialchars($prestamo_act['ci'])?>) — desde <?=htmlspecialchars($prestamo_act['fecha_entrega'])?></p>
        <?php endif; ?>

        <?php if ($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>

        <?php if (!$prestamo_act): ?>
          <!-- Pedir préstamo -->
          <form method="post" onsubmit="return confirm('¿Confirmar préstamo de este equipo?');">
            <input type="hidden" name="accion" value="prestar">
            <label>Destino / Observación (opcional)</label>
            <textarea name="observacion" placeholder="Ej.: Sala 203, uso de clase, etc."></textarea>
            <button type="submit">Pedir préstamo</button>
          </form>
        <?php elseif ($yo_lo_tengo): ?>
          <!-- Devolver -->
          <form method="post" onsubmit="return confirm('¿Marcar devolución?');" style="margin-top:10px">
            <input type="hidden" name="accion" value="devolver">
            <button type="submit">Devolver equipo</button>
          </form>
        <?php else: ?>
          <p class="muted">Este equipo está actualmente prestado a otra persona.</p>
        <?php endif; ?>
      </div>

      <div class="card">
        <h3>“Lo que trae”</h3>
        <table>
          <thead>
            <tr><th>Tipo</th><th>Marca</th><th>Modelo</th><th>Estado</th></tr>
          </thead>
          <tbody>
            <?php if (!$componentes): ?>
              <tr><td colspan="4" class="muted">Sin componentes.</td></tr>
            <?php else: foreach($componentes as $c): ?>
              <tr>
                <td><?=htmlspecialchars($c['tipo'])?></td>
                <td><?=htmlspecialchars($c['marca'])?></td>
                <td><?=htmlspecialchars($c['modelo'])?></td>
                <td><?=htmlspecialchars($c['estado'])?></td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
