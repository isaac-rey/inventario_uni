<?php
// public/prestamos_devolver.php
require __DIR__ . '/../init.php';
require_login();

$equipo_id = intval($_GET['equipo'] ?? 0);
if (!$equipo_id) die("Equipo no especificado.");

// Buscar préstamo activo de ese equipo
$stmt = $mysqli->prepare("SELECT p.*, e.tipo, e.marca, e.modelo, est.nombre AS responsable
                          FROM prestamos p
                          JOIN equipos e ON p.equipo_id = e.id
                          JOIN estudiantes est ON p.estudiante_id = est.id
                          WHERE p.equipo_id=? AND p.estado='activo' LIMIT 1");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$prestamo = $stmt->get_result()->fetch_assoc();
if (!$prestamo) die("No hay préstamo activo para este equipo.");

$error = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $es_tercero = ($_POST['es_tercero'] ?? 'no') === 'si';
    $nombre_tercero = null;
    $ci_tercero = null;

    if ($es_tercero) {
        $ci_tercero = trim($_POST['ci_tercero'] ?? '');

        if ($ci_tercero === '') {
            $error = "Debes seleccionar al estudiante que devuelve el equipo.";
        } else {
            // Buscar nombre en la base de datos según el CI
            $stmt = $mysqli->prepare("SELECT nombre FROM estudiantes WHERE ci = ? LIMIT 1");
            $stmt->bind_param("s", $ci_tercero);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            $nombre_tercero = $res['nombre'] ?? '';

            if ($nombre_tercero === '') {
                $error = "El estudiante seleccionado no existe en la base de datos.";
            }
        }
    }

    if ($error === '') {
        // Actualizar préstamo
        $stmt = $mysqli->prepare("UPDATE prestamos 
                                  SET estado='devuelto', 
                                      fecha_devolucion=NOW(), 
                                      devuelto_por_tercero_nombre=?, 
                                      devuelto_por_tercero_ci=? 
                                  WHERE id=?");
        $stmt->bind_param("ssi", $nombre_tercero, $ci_tercero, $prestamo['id']);
        $stmt->execute();

        // Liberar equipo
        $stmt = $mysqli->prepare("UPDATE equipos SET prestado=0, estado='disponible' WHERE id=?");
        $stmt->bind_param("i", $equipo_id);
        $stmt->execute();

        $ok = true;
        header("Location: equipos_index.php?msg=devolucion_ok");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Devolver equipo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/form_devolver_prestamos.css">
  <script>
    function toggleTercero(val) {
      document.getElementById('datos-tercero').style.display = val === 'si' ? 'block' : 'none';
    }
    document.addEventListener('DOMContentLoaded', function() {
      var sel = document.querySelector('select[name=es_tercero]');
      toggleTercero(sel.value);
    });
  </script>
</head>

<body>
  <?php
  include __DIR__ . '/navbar.php';
  ?>
  <div class="container">
    <h2>Devolver equipo</h2>
    <p><b>Equipo:</b> <?= htmlspecialchars($prestamo['tipo'] . ' ' . $prestamo['marca'] . ' ' . $prestamo['modelo']) ?></p>
    <p><b>Responsable actual:</b> <?= htmlspecialchars($prestamo['responsable']) ?></p>

    <?php if ($ok): ?>
      <div class="msg">Devolución registrada correctamente.</div>
      <a href="equipos_index.php">← Volver a equipos</a>
    <?php else: ?>
      <?php if ($error): ?>
        <div class="msg" style="background:#fed7d7; color:#c53030; border:1px solid #feb2b2">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="post">
        <label>¿La persona que devuelve el equipo es el responsable original?
          <select name="es_tercero" onchange="toggleTercero(this.value)">
            <option value="no">Sí, soy el responsable</option>
            <option value="si">No, es otra persona</option>
          </select>
        </label>

        <?php
        // Obtener estudiantes para el select
        $estudiantes = $mysqli->query("SELECT id, nombre, ci FROM estudiantes ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
        ?>
        <div id="datos-tercero">
          <label>Estudiante que devuelve (tercero)</label>
          <select name="ci_tercero" id="select-estudiante" style="width:100%">
            <option value="">-- Selecciona un estudiante --</option>
            <?php foreach ($estudiantes as $est): ?>
              <option value="<?= htmlspecialchars($est['ci']) ?>">
                <?= htmlspecialchars($est['nombre'] . ' (' . $est['ci'] . ')') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Incluir jQuery + Select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
          $(document).ready(function() {
            $('#select-estudiante').select2({
              placeholder: "Selecciona un estudiante",
              allowClear: true,
              width: 'resolve'
            });
          });
        </script>

        <button type="submit">Registrar devolución</button>
      </form>
      <a href="equipos_index.php">← Volver a equipos</a>
    <?php endif; ?>
  </div>
</body>
</html>
