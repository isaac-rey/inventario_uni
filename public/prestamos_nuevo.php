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
        $stmt = $mysqli->prepare("INSERT INTO prestamos (equipo_id, estudiante_id, observacion) VALUES (?,?,?)");
        $stmt->bind_param("iis", $equipo_id, $est['id'], $obs);
        $stmt->execute();

        // Marcar equipo como prestado
        $stmt = $mysqli->prepare("UPDATE equipos SET prestado=1, estado='en_uso' WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $equipo_id);
        $stmt->execute();

        $ok = true;
        header("Location: equipos_index.php");
        exit;
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
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px;background:#111827}
    a{color:#93c5fd;text-decoration:none}
    .container{padding:24px;max-width:700px;margin:auto}
    .card{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:16px}
    label{display:block;margin:12px 0 6px}
    input,textarea{width:100%;padding:10px;border-radius:8px;border:1px solid #374151;background:#0b1220;color:#e5e7eb}
    button{padding:10px 16px;margin-top:16px;border-radius:8px;border:0;background:#2563eb;color:#fff;cursor:pointer;font-weight:600}
    .muted{color:#9ca3af}
    .error{background:#7f1d1d;color:#fecaca;padding:10px;border-radius:8px;margin-bottom:12px}
  </style>
</head>
<body>
  <header>
    <div><a href="equipos_index.php">← Volver a equipos</a></div>
    <div><?=htmlspecialchars(user()['nombre'])?> (<?=htmlspecialchars(user()['rol'])?>)</div>
  </header>

  <div class="container">
    <div class="card">
      <h2>Nuevo préstamo</h2>

      <p class="muted">
        Equipo: <strong><?=htmlspecialchars($equipo['tipo'])?> <?=htmlspecialchars($equipo['marca'])?> <?=htmlspecialchars($equipo['modelo'])?></strong>
        — Área: <?=htmlspecialchars($equipo['area'])?><?= $equipo['sala'] ? ' / '.htmlspecialchars($equipo['sala']) : '' ?>
      </p>

      <?php if ($error): ?><div class="error"><?=htmlspecialchars($error)?></div><?php endif; ?>

      <form method="post" autocomplete="off">
        <label>CI del estudiante</label>
        <input name="ci" placeholder="Ej.: 8697131" required>

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
