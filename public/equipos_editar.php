<?php
// public/equipos_editar.php
require __DIR__ . '/../init.php';
require_login();

$rol = user()['rol'];
$id = intval($_GET['id'] ?? 0);
if (!$id) { die("ID no especificado."); }

// Cargar equipo
$stmt = $mysqli->prepare("
  SELECT e.*, a.nombre AS area_nombre, s.nombre AS sala_nombre
  FROM equipos e
  JOIN areas a ON a.id = e.area_id
  LEFT JOIN salas s ON s.id = e.sala_id
  WHERE e.id = ?
  LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) { die("Equipo no encontrado."); }

// Si no es admin, limitar edición a su área (Biblioteca id=1)
if ($rol !== 'admin' && intval($equipo['area_id']) !== 1) {
  http_response_code(403);
  die("Acceso denegado para editar equipos fuera de tu área.");
}

// Listar áreas (admin ve todas; bibliotecaria solo Biblioteca)
if ($rol === 'admin') {
  $areas = $mysqli->query("SELECT id, nombre FROM areas ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
} else {
  $areas = $mysqli->query("SELECT id, nombre FROM areas WHERE id=1")->fetch_all(MYSQLI_ASSOC);
}

// Listar salas (todas para elegir; en mejora futura podemos filtrar por área elegida vía JS)
$salas = $mysqli->query("SELECT id, nombre FROM salas ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);

$error = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $area_id = intval($_POST['area_id']);
  $sala_id = $_POST['sala_id'] !== '' ? intval($_POST['sala_id']) : null;
  $tipo    = trim($_POST['tipo']);
  $marca   = trim($_POST['marca']);
  $modelo  = trim($_POST['modelo']);
  $estado  = $_POST['estado'];

  if ($tipo === '') {
    $error = "El campo tipo es obligatorio.";
  } else {
    // Si no es admin, forzar área=1 por seguridad
    if ($rol !== 'admin') { $area_id = 1; }

    $stmt = $mysqli->prepare("
      UPDATE equipos
      SET area_id=?, sala_id=?, tipo=?, marca=?, modelo=?, estado=?
      WHERE id=?
      LIMIT 1
    ");
    // sala_id puede ser NULL -> usar 'i' y pasar null con bind_param requiere cuidado:
    // truco: si $sala_id es null, usar null; mysqli lo envía como 0 si no seteamos types-> usamos set de types y values normal.
    $stmt->bind_param("iissssi", $area_id, $sala_id, $tipo, $marca, $modelo, $estado, $id);
    $stmt->execute();

    $ok = true;

    // Recargar datos actualizados
    $stmt = $mysqli->prepare("
      SELECT e.*, a.nombre AS area_nombre, s.nombre AS sala_nombre
      FROM equipos e
      JOIN areas a ON a.id = e.area_id
      LEFT JOIN salas s ON s.id = e.sala_id
      WHERE e.id = ?
      LIMIT 1
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc();
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar equipo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px;background:#111827}
    a{color:#93c5fd;text-decoration:none}
    .container{padding:24px;max-width:700px;margin:auto}
    .card{background:#111827;padding:24px;border-radius:12px;border:1px solid #1f2937}
    label{display:block;margin:12px 0 6px}
    input,select{width:100%;padding:10px;border-radius:8px;border:1px solid #374151;background:#0b1220;color:#e5e7eb}
    .row{display:grid;gap:12px;grid-template-columns:1fr 1fr}
    button{padding:10px 16px;margin-top:16px;border-radius:8px;border:0;background:#2563eb;color:white;font-weight:600;cursor:pointer}
    .error{background:#7f1d1d;color:#fecaca;padding:10px;border-radius:8px;margin-bottom:12px}
    .ok{background:#052e16;color:#bbf7d0;padding:10px;border-radius:8px;margin-bottom:12px}
    .muted{color:#9ca3af}
  </style>
</head>
<body>
  <header>
    <div><a href="equipos_index.php">← Volver a equipos</a></div>
    <div><?=htmlspecialchars(user()['nombre'])?> (<?=htmlspecialchars($rol)?>)</div>
  </header>

  <div class="container">
    <div class="card">
      <h2>Editar equipo</h2>
      <?php if ($ok): ?><div class="ok">Cambios guardados.</div><?php endif; ?>
      <?php if ($error): ?><div class="error"><?=$error?></div><?php endif; ?>

      <form method="post">
        <div class="row">
          <div>
            <label>Área</label>
            <select name="area_id" required <?= $rol!=='admin' ? 'disabled' : '' ?>>
              <?php foreach($areas as $a): ?>
                <option value="<?=$a['id']?>" <?= $a['id']==$equipo['area_id']?'selected':'' ?>>
                  <?=$a['nombre']?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if ($rol!=='admin'): ?>
              <!-- si está deshabilitado, mandamos el valor real con hidden -->
              <input type="hidden" name="area_id" value="<?=$equipo['area_id']?>">
            <?php endif; ?>
          </div>

          <div>
            <label>Sala (opcional)</label>
            <select name="sala_id">
              <option value="">-- Ninguna --</option>
              <?php foreach($salas as $s): ?>
                <option value="<?=$s['id']?>" <?= ($equipo['sala_id'] ?? null)==$s['id']?'selected':'' ?>>
                  <?=$s['nombre']?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="row">
          <div>
            <label>Tipo</label>
            <input name="tipo" value="<?=htmlspecialchars($equipo['tipo'])?>" required>
          </div>
          <div>
            <label>Estado</label>
            <select name="estado">
              <?php
                $estados = ['bueno','en_uso','dañado','fuera_servicio'];
                foreach ($estados as $e) {
                  $sel = $equipo['estado']===$e ? 'selected' : '';
                  echo "<option value=\"$e\" $sel>$e</option>";
                }
              ?>
            </select>
          </div>
        </div>

        <div class="row">
          <div>
            <label>Marca</label>
            <input name="marca" value="<?=htmlspecialchars($equipo['marca'])?>">
          </div>
          <div>
            <label>Modelo</label>
            <input name="modelo" value="<?=htmlspecialchars($equipo['modelo'])?>">
          </div>
        </div>

        <label class="muted">Serial interno (solo lectura)</label>
        <input value="<?=htmlspecialchars($equipo['serial_interno'])?>" disabled>

        <button type="submit">Guardar cambios</button>
      </form>
    </div>
  </div>
</body>
</html>
