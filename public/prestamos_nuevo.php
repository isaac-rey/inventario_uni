<?php
// public/prestamos_nuevo.php
require __DIR__ . '/../init.php';
require_login();

$equipo_id = intval($_GET['equipo'] ?? 0);
if (!$equipo_id) { die("Equipo no especificado."); }

// Traer equipo
$stmt = $mysqli->prepare("SELECT e.*, a.nombre AS area, s.nombre AS sala
                          FROM equipos e
                          JOIN areas a ON a.id=e.area_id
                          LEFT JOIN salas s ON s.id=e.sala_id
                          WHERE e.id=? LIMIT 1");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) { die("Equipo no encontrado."); }

if ((int)$equipo['prestado'] === 1) {
  // si ya está prestado, redirigir
  header("Location: equipos_index.php");
  exit;
}

$error = '';
$ok = false;

// Guardar préstamo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ci = trim($_POST['ci'] ?? '');
  $obs = trim($_POST['observacion'] ?? '');

  if ($ci === '') {
    $error = "CI del estudiante es obligatorio.";
  } else {
    // Verificar estudiante
    $stmt = $mysqli->prepare("SELECT id, nombre, apellido FROM estudiantes WHERE ci=? LIMIT 1");
    $stmt->bind_param("s", $ci);
    $stmt->execute();
    $est = $stmt->get_result()->fetch_assoc();

    if (!$est) {
      $error = "No existe un estudiante con ese CI. Registralo primero.";
    } else {
      // Revalidar que el equipo no tenga préstamo activo
      $stmt = $mysqli->prepare("SELECT COUNT(*) AS c FROM prestamos WHERE equipo_id=? AND estado='activo'");
      $stmt->bind_param("i", $equipo_id);
      $stmt->execute();
      $c_activo = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);

      if ($c_activo > 0 || (int)$equipo['prestado'] === 1) {
        $error = "El equipo ya está prestado.";
      } else {
        // Insertar préstamo
        //$stmt = $mysqli->prepare("INSERT INTO prestamos (equipo_id, estudiante_id, observacion) VALUES (?,?,?)");
        //$stmt->bind_param("iis", $equipo_id, $est['id'], $obs);
        //$stmt->execute();
        //************************************************* */
        // 1. Insertar préstamo (Usamos $stmt_prestamo)
        $stmt_prestamo = $mysqli->prepare("INSERT INTO prestamos (equipo_id, estudiante_id, observacion) VALUES (?,?,?)");
        $stmt_prestamo->bind_param("iis", $equipo_id, $est['id'], $obs);

        //************************************************** */

        // Marcar equipo como prestado
        //$stmt = $mysqli->prepare("UPDATE equipos SET prestado=1, estado='en_uso' WHERE id=? LIMIT 1");
        //$stmt->bind_param("i", $equipo_id);
        //$stmt->execute();
        //************************************************* */
        // 2. Marcar equipo como prestado (Usamos $stmt_equipo)
        $stmt_equipo = $mysqli->prepare("UPDATE equipos SET prestado=1, estado='en_uso' WHERE id=? LIMIT 1");
        $stmt_equipo->bind_param("i", $equipo_id);

        //---------------------insersion de la auditoria----------------------------
         if ($stmt_prestamo->execute() && 
            $stmt_equipo->execute()) {
          $nombre_estudiante = $est['nombre'] . ' ' . $est['apellido'];
          $equipo_desc = $equipo['tipo'] . ' ' . $equipo['marca'] . ' ' . $equipo['modelo'];

          auditar("Registró el préstamo del equipo ID {$equipo_id} ({$equipo_desc}) al estudiante {$nombre_estudiante} (CI: {$ci}).");
        
        //  $ok = true;
        //header("Location: equipos_index.php");
        exit;
         }else{
          $error = "Ocurrió un error al registrar el préstamo o actualizar el equipo.";
         }
        //---------------------------------------------------------------------------
      }
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo préstamo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/form_prestamos_nuevo.css">
</head>
<body>
  <?php
  include __DIR__ . '/navbar.php';
  ?>

  <div class="container">
    <div class="card">
      <h2>Nuevo préstamo</h2>

      <p class="muted">
        Equipo: <strong><?=htmlspecialchars($equipo['tipo'])?> <?=htmlspecialchars($equipo['marca'])?> <?=htmlspecialchars($equipo['modelo'])?></strong>
        — Área: <?=htmlspecialchars($equipo['area'])?><?= $equipo['sala'] ? ' / '.htmlspecialchars($equipo['sala']) : '' ?>
      </p>

      <?php if ($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>

      <form method="post" autocomplete="off">
        <br>
        <label>CI del estudiante</label>
        <input name="ci" placeholder="Ej.: 8697131"  type="text" required>

        <label>Observación (opcional)</label>
        <textarea name="observacion" placeholder="Motivo, aula, responsable, etc."></textarea>

        <button type="submit">Confirmar préstamo</button>
      </form>

      <p class="muted" style="margin-top:10px">
        ¿El estudiante no existe? <a href="/inventario_uni/public/estudiantes_registro.php" target="_blank">Registrarlo aquí</a>.
      </p>
    </div>
  </div>
</body>
</html>
