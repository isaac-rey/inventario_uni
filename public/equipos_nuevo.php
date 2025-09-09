<?php
// public/equipos_nuevo.php
require __DIR__ . '/../init.php';
require_login();

// Solo admin puede crear en cualquier área, bibliotecaria solo en Biblioteca (id=1)
$rol = user()['rol'];

// --- Obtener áreas
if ($rol === 'admin') {
  $areas = $mysqli->query("SELECT id, nombre FROM areas ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
} else {
  $areas = $mysqli->query("SELECT id, nombre FROM areas WHERE id=1")->fetch_all(MYSQLI_ASSOC);
}

// --- Insertar equipo
$error = '';
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
    // generar serial interno único
    $serial = bin2hex(random_bytes(6));

    $stmt = $mysqli->prepare("INSERT INTO equipos (area_id, sala_id, tipo, marca, modelo, serial_interno, estado) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("iisssss", $area_id, $sala_id, $tipo, $marca, $modelo, $serial, $estado);
    $stmt->execute();
    header("Location: /inventario_uni/public/equipos_index.php");
    exit;
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo equipo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px;background:#111827}
    a{color:#93c5fd;text-decoration:none}
    .container{padding:24px;max-width:600px;margin:auto}
    .card{background:#111827;padding:24px;border-radius:12px}
    label{display:block;margin:12px 0 4px}
    input,select{width:100%;padding:10px;border-radius:8px;border:1px solid #374151;background:#0b1220;color:#e5e7eb}
    button{padding:10px 16px;margin-top:16px;border-radius:8px;border:0;background:#2563eb;color:white;font-weight:600;cursor:pointer}
    .error{background:#7f1d1d;color:#fecaca;padding:10px;border-radius:8px;margin-bottom:12px}
  </style>
</head>
<body>
  <header>
    <div><a href="/inventario_uni/public/equipos_index.php">← Volver</a></div>
    <div><?=htmlspecialchars(user()['nombre'])?> (<?=htmlspecialchars($rol)?>)</div>
  </header>

  <div class="container">
    <div class="card">
      <h2>Nuevo equipo</h2>
      <?php if ($error): ?><div class="error"><?=$error?></div><?php endif; ?>
      <form method="post">
        <label>Área</label>
        <select name="area_id" required>
          <?php foreach($areas as $a): ?>
            <option value="<?=$a['id']?>"><?=$a['nombre']?></option>
          <?php endforeach; ?>
        </select>

        <label>Sala (opcional)</label>
        <select name="sala_id">
          <option value="">-- Ninguna --</option>
          <?php
          $salas = $mysqli->query("SELECT id, nombre FROM salas ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
          foreach($salas as $s): ?>
            <option value="<?=$s['id']?>"><?=$s['nombre']?></option>
          <?php endforeach; ?>
        </select>

        <label>Tipo de equipo</label>
        <input name="tipo" placeholder="Proyector, PC, TV, etc." required>

        <label>Marca</label>
        <input name="marca">

        <label>Modelo</label>
        <input name="modelo">

        <label>Estado</label>
        <select name="estado">
          <option value="bueno">Bueno</option>
          <option value="en_uso">En uso</option>
          <option value="dañado">Dañado</option>
          <option value="fuera_servicio">Fuera de servicio</option>
        </select>

        <button type="submit">Guardar equipo</button>
      </form>
    </div>
  </div>
</body>
</html>
