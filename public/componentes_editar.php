<?php
// public/componentes_editar.php
require __DIR__ . '/../init.php';
require_login();

$comp_id  = intval($_GET['id'] ?? 0);
$equipo_id = intval($_GET['equipo'] ?? 0);
if (!$comp_id || !$equipo_id) { die("Parámetros insuficientes."); }

// Traer componente + validar que pertenezca al equipo
$stmt = $mysqli->prepare("SELECT * FROM componentes WHERE id=? AND equipo_id=? LIMIT 1");
$stmt->bind_param("ii", $comp_id, $equipo_id);
$stmt->execute();
$comp = $stmt->get_result()->fetch_assoc();
if (!$comp) { die("Componente no encontrado."); }

// Traer equipo (para mostrar encabezado)
$stmt = $mysqli->prepare("SELECT e.*, a.nombre AS area, s.nombre AS sala
                          FROM equipos e
                          JOIN areas a ON a.id=e.area_id
                          LEFT JOIN salas s ON s.id=e.sala_id
                          WHERE e.id=? LIMIT 1");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) { die("Equipo no encontrado."); }

$error = '';
$ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tipo   = trim($_POST['tipo']);
  $marca  = trim($_POST['marca']);
  $modelo = trim($_POST['modelo']);
  $estado = $_POST['estado'];
  $obs    = trim($_POST['observacion']);

  if ($tipo === '') {
    $error = "El campo tipo es obligatorio.";
  } else {
    $stmt = $mysqli->prepare("UPDATE componentes
                              SET tipo=?, marca=?, modelo=?, estado=?, observacion=?
                              WHERE id=? AND equipo_id=? LIMIT 1");
    $stmt->bind_param("sssssii", $tipo, $marca, $modelo, $estado, $obs, $comp_id, $equipo_id);
    //$stmt->execute();
    //$ok = true;

    /* recargar datos
    $stmt = $mysqli->prepare("SELECT * FROM componentes WHERE id=? AND equipo_id=? LIMIT 1");
    $stmt->bind_param("ii", $comp_id, $equipo_id);
    $stmt->execute();
    $comp = $stmt->get_result()->fetch_assoc();*/
     if ($stmt->execute()) {
        // ✅ INSERCIÓN DE LA AUDITORÍA AQUÍ
        $equipo_desc = htmlspecialchars($equipo['tipo'] . ' ' . $equipo['marca'] . ' ' . $equipo['modelo']);
        $componente_desc = htmlspecialchars($tipo . ' ' . $marca . ' ' . $modelo);
        
        auditar("Editó el componente ID {$comp_id} ({$componente_desc}, Estado: {$estado}) del equipo ID {$equipo_id} ({$equipo_desc}).");
        
        $ok = true;
    } else {
        $error = "Error al actualizar el componente: " . $mysqli->error;
    }

    if ($ok) {
        $stmt = $mysqli->prepare("SELECT * FROM componentes WHERE id=? AND equipo_id=? LIMIT 1");
        $stmt->bind_param("ii", $comp_id, $equipo_id);
        $stmt->execute();
        $comp = $stmt->get_result()->fetch_assoc();
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar componente</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/form_componentes_editar.css">
</head>
<body>
  <?php
  include __DIR__ . '/navbar.php';
  ?>

  <div class="container">
    <div class="card">
      <h2>Editar componente</h2>
      <p class="muted">
        Equipo: <strong><?=htmlspecialchars($equipo['tipo'])?> <?=htmlspecialchars($equipo['marca'])?> <?=htmlspecialchars($equipo['modelo'])?></strong>
        — Área: <?=htmlspecialchars($equipo['area'])?><?= $equipo['sala']? ' / '.htmlspecialchars($equipo['sala']) : '' ?>
      </p>

      <?php if ($ok): ?><div class="ok">Cambios guardados.</div><?php endif; ?>
      <?php if ($error): ?><div class="error"><?=$error?></div><?php endif; ?>

      <form method="post">
        <label>Tipo</label>
        <input type="text" name="tipo" value="<?=htmlspecialchars($comp['tipo'])?>" required>

        <label>Marca</label>
        <input type="text" name="marca" value="<?=htmlspecialchars($comp['marca'])?>">

        <label>Modelo</label>
        <input type="text" name="modelo" value="<?=htmlspecialchars($comp['modelo'])?>">

        <label>Estado</label>
        <select name="estado">
          <?php
            $estados = ['bueno','en_uso','dañado','fuera_servicio'];
            foreach ($estados as $e) {
              $sel = $comp['estado']===$e ? 'selected' : '';
              echo "<option value=\"$e\" $sel>$e</option>";
            }
          ?>
        </select>

        <label>Observación</label>
        <textarea name="observacion"><?=htmlspecialchars($comp['observacion'])?></textarea>

        <button type="submit">Guardar cambios</button>
      </form>
    </div>
  </div>
</body>
</html>
