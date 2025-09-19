<?php
// public/prestamos_devolver.php
require __DIR__ . '/../init.php';
require_login();

$equipo_id = intval($_GET['equipo'] ?? 0);
if (!$equipo_id) {
  die("Equipo no especificado.");
}

$stmt = $mysqli->prepare("SELECT p.*, e.tipo, e.marca, e.modelo, est.nombre AS responsable
                          FROM prestamos p
                          JOIN equipos e ON p.equipo_id = e.id
                          JOIN estudiantes est ON p.estudiante_id = est.id
                          WHERE p.equipo_id=? AND p.estado='activo' LIMIT 1");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$prestamo = $stmt->get_result()->fetch_assoc();
if (!$prestamo) {
  die("No hay préstamo activo para este equipo.");
}

$error = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $es_tercero = ($_POST['es_tercero'] ?? 'no') === 'si';
  $nombre_tercero = trim($_POST['nombre_tercero'] ?? '');
  $ci_tercero = trim($_POST['ci_tercero'] ?? '');

  // Asignar variables para bind_param
  $nombre_bd = $es_tercero ? $nombre_tercero : null;
  $ci_bd = $es_tercero ? $ci_tercero : null;

  if ($es_tercero && ($nombre_tercero === '' || $ci_tercero === '')) {
    $error = "Debes ingresar nombre y documento del tercero.";
  } else {
    // Marcar devolución y guardar datos del tercero si corresponde
    $stmt = $mysqli->prepare("UPDATE prestamos SET estado='devuelto', fecha_devolucion=NOW(), devuelto_por_tercero_nombre=?, devuelto_por_tercero_ci=? WHERE id=?");
    $stmt->bind_param("ssi", $nombre_bd, $ci_bd, $prestamo['id']);
    $stmt->execute();

    $stmt = $mysqli->prepare("
    UPDATE equipos 
    SET prestado=0, estado='disponible' 
    WHERE id=?");
    $stmt->bind_param("i", $equipo_id);
    $stmt->execute();

    $ok = true;

    // 🔄 Redirigir automáticamente
    header("Location: equipos_index.php?msg=devolucion_ok");
    exit;
  }
}
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Devolver equipo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: system-ui, Segoe UI, Arial, sans-serif;
      background: #0f172a;
      color: #e2e8f0;
      margin: 0
    }

    .container {
      padding: 24px;
      max-width: 600px;
      margin: auto
    }

    label {
      display: block;
      margin-top: 12px
    }

    input,
    select {
      width: 100%;
      padding: 8px;
      margin-top: 4px;
      border-radius: 6px;
      border: 1px solid #222
    }

    button {
      background: #2563eb;
      color: #fff;
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 16px
    }

    button:hover {
      background: #1d4ed8
    }

    .msg {
      background: #1e293b;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 16px
    }
  </style>
  <script>
    function toggleTercero(val) {
      document.getElementById('datos-tercero').style.display = val === 'si' ? 'block' : 'none';
    }
  </script>
</head>

<body>
  <div class="container">
    <h2>Devolver equipo</h2>
    <p><b>Equipo:</b> <?= htmlspecialchars($prestamo['tipo'] . ' ' . $prestamo['marca'] . ' ' . $prestamo['modelo']) ?></p>
    <p><b>Responsable actual:</b> <?= htmlspecialchars($prestamo['responsable']) ?></p>

    <?php if ($ok): ?>
      <div class="msg">Devolución registrada correctamente.</div>
      <a href="equipos_index.php" style="color:#93c5fd">← Volver a equipos</a>
    <?php else: ?>
      <?php if ($error): ?><div class="msg" style="background:#b91c1c"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="post">
        <label>¿La persona que devuelve el equipo es el responsable original?
          <select name="es_tercero" onchange="toggleTercero(this.value)">
            <option value="no">Sí, soy el responsable</option>
            <option value="si">No, es otra persona</option>
          </select>
        </label>
        <div id="datos-tercero" style="display:none">
          <label>Nombre de quien devuelve:</label>
          <input type="text" name="nombre_tercero" maxlength="120">
          <label>Documento/C.I. de quien devuelve:</label>
          <input type="text" name="ci_tercero" maxlength="20">
        </div>
        <button type="submit">Registrar devolución</button>
      </form>
      <script>
        // Mantener visible si el usuario ya seleccionó "No"
        document.addEventListener('DOMContentLoaded', function() {
          var sel = document.querySelector('select[name=es_tercero]');
          toggleTercero(sel.value);
        });
      </script>
    <?php endif; ?>
    <a href="equipos_index.php" style="color:#93c5fd">← Volver a equipos</a>
  </div>
</body>

</html>