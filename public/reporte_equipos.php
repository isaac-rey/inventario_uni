<?php
// public/form_reporte_equipo.php
require __DIR__ . '/../config/db.php';

$ok = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario_reportador = trim($_POST['nombre_usuario_reportador'] ?? '');
    $id_equipo    = intval($_POST['id_equipo'] ?? 0);
    $fecha_reporte   = $_POST['fecha_reporte'] ?? '';
    $tipo_fallo  = trim($_POST['tipo_fallo'] ?? '');
    $descripcion_fallo  = trim($_POST['descripcion_fallo'] ?? '');

    if ($nombre_usuario_reportador === '' || $id_equipo === 0 || $fecha_reporte === '' || $tipo_fallo === '' || $descripcion_fallo === '') {
        $error = 'Todos los campos son obligatorios.';
    } else {
        // Convierte de YYYY-MM-DD a DD-MM-YYYY
        $fecha_reporte_formateada = date("d-m-Y", strtotime($fecha_reporte));

        $stmt = $mysqli->prepare("
          INSERT INTO reporte_fallos(fecha, tipo_fallo, descripcion_fallo, id_equipo, nombre_usuario_reportante)
          VALUES (?,?,?,?,?)
        ");
        if ($stmt === false) {
            $error = "Error en prepare: " . $mysqli->error;
        } else {
            $stmt->bind_param("sssis", $fecha_reporte_formateada, $tipo_fallo, $descripcion_fallo, $id_equipo, $nombre_usuario_reportador);
            if ($stmt->execute()) {
                $ok = true;
            } else {
                $error = "No se pudo registrar el reporte.";
            }
        }
    }
}

// Para cargar el select de equipos siempre
$equipos = [];
$res = $mysqli->query("SELECT * FROM equipos");
if ($res) {
    $equipos = $res->fetch_all(MYSQLI_ASSOC);
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Reporte de fallos de equipos</title>
  <link rel="stylesheet" href="../css/estudiantes_registro.css">
</head>
<body>
  <?php include __DIR__ . '/navbar.php'; ?>

  <div class="card">
    <h1>Formulario para registrar fallos en equipos</h1>

    <?php if ($ok): ?>
      <div class="ok">✔ Reporte registrado correctamente.</div>
      <div class="muted"><a href="/inventario_uni/public/form_reporte_equipo.php">Registrar otro</a></div>
    <?php else: ?>
      <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post" autocomplete="off">
        <input type="hidden" name="nombre_usuario_reportador" value="<?= htmlspecialchars($nombre_usuario_reportador ?? user()['nombre']) ?>">

        <label for="id_equipo">Selecciona un equipo *</label>
        <select name="id_equipo" id="id_equipo" required>
          <option value="">Selecciona un equipo</option>
          <?php foreach ($equipos as $equipo): ?>
            <option value="<?= $equipo['id'] ?>"><?= htmlspecialchars($equipo['marca']) ?></option>
          <?php endforeach; ?>
        </select>

        <label for="fecha_reporte">Fecha *</label>
        <input id="fecha_reporte" type="date" name="fecha_reporte" required>

        <label for="tipo_fallo">Tipo de fallo *</label>
        <input id="tipo_fallo" type="text" name="tipo_fallo" required>

        <label for="descripcion_fallo">Descripción del fallo *</label>
        <textarea id="descripcion_fallo" name="descripcion_fallo" required style="height:200px;"></textarea>

        <button type="submit">Registrar reporte</button>
        <button type="reset" class="secondary">Vaciar formulario</button>
      </form>

      <div class="muted">
        Los campos marcados con * son obligatorios.
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
