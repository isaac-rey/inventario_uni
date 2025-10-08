<?php
//require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';

$ok = false;
$error = '';

// Obtenemos id_equipo si viene desde la URL
$id_equipo_get = isset($_GET['id_equipo']) ? intval($_GET['id_equipo']) : 0;

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
        $mysqli->query("UPDATE equipos SET con_reporte = 1 WHERE id = $id_equipo");
        //----------------------------------insersion de la auditoria----------------------------
        // 2. RECUPERAR DATOS DEL EQUIPO PARA LA AUDITORÍA (¡PASO CLAVE!)
        $stmt_audit = $mysqli->prepare("SELECT tipo, marca, modelo FROM equipos WHERE id = ?");
        $stmt_audit->bind_param("i", $id_equipo);
        $stmt_audit->execute();
        $equipo_data = $stmt_audit->get_result()->fetch_assoc();

        if ($equipo_data) {
          // 3. Insertar Auditoría
          $equipo_desc = $equipo_data['tipo'] . ' ' . $equipo_data['marca'] . ' ' . $equipo_data['modelo'];
          $reporte_desc = "Fallo: {$tipo_fallo}. Descripción: " . substr($descripcion_fallo, 0, 50) . "...";

          auditar("Reportó un fallo para el equipo ID {$id_equipo} ({$equipo_desc}). {$reporte_desc}");
        }



        /* Nota: La información descriptiva del equipo ($equipo_info) ya se cargó para el formulario, la usamos.
        $equipo_desc = $equipo_info['tipo'] . ' ' . $equipo_info['marca'] . ' ' . $equipo_info['modelo'];
        $reporte_desc = "Fallo: {$tipo_fallo}. Descripción: " . substr($descripcion_fallo, 0, 50) . "...";

        auditar("Reportó un fallo para el equipo ID {$id_equipo} ({$equipo_desc}). {$reporte_desc}");

        //-------------------------------------------------*/


        $ok = true;
      } else {
        $error = "No se pudo registrar el reporte.";
      }
    }
  }
}

// Obtener info del equipo
$equipo_info = null;
if ($id_equipo_get) {
  $stmt = $mysqli->prepare("SELECT * FROM equipos WHERE id = ?");
  $stmt->bind_param("i", $id_equipo_get);
  $stmt->execute();
  $res = $stmt->get_result();
  $equipo_info = $res->fetch_assoc();
}
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Reporte de fallos de equipos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/form_prestamos_nuevo.css">
</head>

<body>
  <?php include __DIR__ . '/navbar.php'; ?>

  <div class="container">
    <div class="card">
      <h2>Registrar fallo de equipo</h2>

      <?php if ($ok): ?>
        <div class="ok">✔ Reporte registrado correctamente.</div>
        <div class="muted"><a href="/inventario_uni/public/equipos_index.php">Volver a equipos</a></div>
      <?php else: ?>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <?php if ($equipo_info): ?>
          <p class="muted">
            <strong>Equipo:</strong> <?= htmlspecialchars($equipo_info['tipo']) ?> —
            <?= htmlspecialchars($equipo_info['marca'] . " " . $equipo_info['modelo']) ?><br>
            <strong>Serial:</strong> <?= htmlspecialchars($equipo_info['serial_interno']) ?>
          </p>
        <?php else: ?>
          <p class="muted">Equipo no seleccionado.</p>
        <?php endif; ?>

        <form method="post" autocomplete="off">
          <input type="hidden" name="nombre_usuario_reportador" value="<?= htmlspecialchars(user()['nombre']) ?>">
          <input type="hidden" name="id_equipo" value="<?= $id_equipo_get ?>">
          <br>

          <label for="fecha_reporte">Fecha del reporte *</label>
          <input id="fecha_reporte" type="date" name="fecha_reporte" required>

          <label for="tipo_fallo">Tipo de fallo *</label>
          <input id="tipo_fallo" type="text" name="tipo_fallo" placeholder="Ej.: Daño físico, software, etc." required>

          <label for="descripcion_fallo">Descripción del fallo *</label>
          <textarea id="descripcion_fallo" name="descripcion_fallo" required style="height:180px;" placeholder="Describa detalladamente el problema"></textarea>

          <button type="submit">Registrar reporte</button>
          <button type="reset" class="secondary">Vaciar formulario</button>
        </form>

        <p class="muted" style="margin-top:10px">
          Todos los campos marcados con * son obligatorios.
        </p>
      <?php endif; ?>
    </div>
  </div>

</body>

</html>