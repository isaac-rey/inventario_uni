<?php
require __DIR__ . '/../init.php';
require_login();

$equipo_id = intval($_GET['id'] ?? 0);
if (!$equipo_id) {
  die("<p class='muted'>Equipo no especificado.</p>");
}

// obtener equipo
$stmt = $mysqli->prepare("SELECT e.*, a.nombre AS area, s.nombre AS sala 
                          FROM equipos e
                          JOIN areas a ON a.id=e.area_id
                          LEFT JOIN salas s ON s.id=e.sala_id
                          WHERE e.id=?");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$equipo = $stmt->get_result()->fetch_assoc();
if (!$equipo) {
  die("<p class='muted'>Equipo no encontrado.</p>");
}

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



  echo "<p class='muted'>Componente agregado correctamente.</p>";
}

// listar componentes
$stmt = $mysqli->prepare("SELECT * FROM componentes WHERE equipo_id=? ORDER BY creado_en DESC");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$componentes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<head>
  <link rel="stylesheet" href="../css/tabla_equipos_componentes.css">

</head>
<?php
include __DIR__ . '/navbar.php';
?>
<div class="card">
  <h2>Componentes de <?= htmlspecialchars($equipo['tipo']) ?><?= $equipo['marca'] ? ' · ' . htmlspecialchars($equipo['marca']) : '' ?><?= $equipo['modelo'] ? ' ' . htmlspecialchars($equipo['modelo']) : '' ?></h2>
  <p class="muted">Área: <?= htmlspecialchars($equipo['area']) ?><?= $equipo['sala'] ? ' / ' . htmlspecialchars($equipo['sala']) : '' ?></p>

  <div class="actions">
    <a class="btn" href="/inventario_uni/public/equipos_componentes_form.php?id=<?= $equipo_id ?>">+ Agregar componente</a>
  </div>
  <div class="card">
    <h3>Listado de componentes</h3>
    <table>
      <thead>
        <tr>
          <th>Tipo</th>
          <th>Marca</th>
          <th>Modelo</th>
          <th>Estado</th>
          <th>Obs</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$componentes): ?>
          <tr>
            <td colspan="6" class="muted">Sin componentes.</td>
          </tr>
          <?php else: foreach ($componentes as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['tipo']) ?></td>
              <td><?= htmlspecialchars($c['marca']) ?></td>
              <td><?= htmlspecialchars($c['modelo']) ?></td>
              <td>
                <?php
                $cls = 'ok';
                if ($c['estado'] === 'dañado' || $c['estado'] === 'fuera_servicio') $cls = 'bad';
                elseif ($c['estado'] === 'en_uso') $cls = 'warn';
                ?>
                <span class="badge <?= $cls ?>"><?= htmlspecialchars($c['estado']) ?></span>
              </td>
              <td><?= htmlspecialchars($c['observacion']) ?></td>
              <td>
                <a href="componentes_editar.php?id=<?= $c['id'] ?>&equipo=<?= $equipo_id ?>" class="serial-btn btn-components">Editar</a>
                <a href="componentes_eliminar.php?id=<?= $c['id'] ?>&equipo=<?= $equipo_id ?>" class="serial-btn btn-public"
                  onclick="return confirm('¿Eliminar este componente?');">Eliminar</a>
              </td>
            </tr>
        <?php endforeach;
        endif; ?>
      </tbody>
    </table>
  </div>
</div>