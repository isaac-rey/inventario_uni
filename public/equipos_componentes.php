<?php
// public/equipos_componentes.php
require __DIR__ . '/../init.php';
require_login();

$equipo_id = intval($_GET['id'] ?? 0);
if (!$equipo_id) { die("Equipo no especificado."); }

// obtener equipo
$stmt = $mysqli->prepare("SELECT e.*, a.nombre AS area, s.nombre AS sala 
                          FROM equipos e
                          JOIN areas a ON a.id=e.area_id
                          LEFT JOIN salas s ON s.id=e.sala_id
                          WHERE e.id=?");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) { die("Equipo no encontrado."); }

// insertar componente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tipo = trim($_POST['tipo']);
  $estado = $_POST['estado'];
  $marca = trim($_POST['marca']);
  $modelo = trim($_POST['modelo']);
  $obs = trim($_POST['observacion']);

  $stmt = $mysqli->prepare("INSERT INTO componentes (equipo_id, tipo, marca, modelo, estado, observacion) VALUES (?,?,?,?,?,?)");
  $stmt->bind_param("isssss", $equipo_id, $tipo, $marca, $modelo, $estado, $obs);
  $stmt->execute();

  header("Location: equipos_componentes.php?id=$equipo_id");
  exit;
}

// listar componentes
$stmt = $mysqli->prepare("SELECT * FROM componentes WHERE equipo_id=? ORDER BY creado_en DESC");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$componentes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Componentes del equipo</title>
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px;background:#111827}
    a{color:#93c5fd;text-decoration:none}
    .container{padding:24px}
    .card{background:#111827;padding:16px;border-radius:12px;margin-bottom:20px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:8px;border-bottom:1px solid #1f2937;text-align:left}
    th{color:#93c5fd}
    input,select,textarea{width:100%;padding:8px;margin:4px 0 10px;background:#0b1220;color:#e5e7eb;border:1px solid #374151;border-radius:6px}
    button{padding:10px 16px;border:0;background:#2563eb;color:white;border-radius:8px;cursor:pointer}
  </style>
</head>
<body>
  <header>
    <div><a href="equipos_index.php">← Volver a equipos</a></div>
    <div><?=htmlspecialchars(user()['nombre'])?> (<?=htmlspecialchars(user()['rol'])?>)</div>
  </header>

  <div class="container">
    <h2>Componentes del equipo</h2>
    <p><strong><?=htmlspecialchars($equipo['tipo'])?> <?=htmlspecialchars($equipo['marca'])?> <?=htmlspecialchars($equipo['modelo'])?></strong> — Área: <?=htmlspecialchars($equipo['area'])?><?php if($equipo['sala']):?> / <?=htmlspecialchars($equipo['sala'])?><?php endif;?></p>

    <div class="card">
      <h3>Agregar componente</h3>
      <form method="post">
        <label>Tipo</label>
        <input name="tipo" placeholder="HDMI, Control, Zapatilla, etc." required>

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

        <label>Observación</label>
        <textarea name="observacion"></textarea>

        <button type="submit">Agregar</button>
      </form>
    </div>

    <div class="card">
      <h3>Listado</h3>
      <table>
        <thead>
          <tr>
            <th>Tipo</th><th>Marca</th><th>Modelo</th><th>Estado</th><th>Obs</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!$componentes): ?>
          <tr><td colspan="6">Sin componentes.</td></tr>
        <?php else: foreach($componentes as $c): ?>
          <tr>
            <td><?=htmlspecialchars($c['tipo'])?></td>
            <td><?=htmlspecialchars($c['marca'])?></td>
            <td><?=htmlspecialchars($c['modelo'])?></td>
            <td><?=htmlspecialchars($c['estado'])?></td>
            <td><?=htmlspecialchars($c['observacion'])?></td>
            <td>
              <a href="componentes_editar.php?id=<?=$c['id']?>&equipo=<?=$equipo_id?>">Editar</a> |
              <a href="componentes_eliminar.php?id=<?=$c['id']?>&equipo=<?=$equipo_id?>"
                 onclick="return confirm('¿Eliminar este componente?');">Eliminar</a>
            </td>
          </tr>
        <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
