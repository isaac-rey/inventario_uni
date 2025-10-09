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
    //$stmt->execute();

    //--------------------------------INSERCION DE LA AUDITORIA------------------------------
    if ($stmt->execute()) {
        // ✅ INSERCIÓN DE LA AUDITORÍA AQUÍ
        $equipo_desc = htmlspecialchars($equipo['tipo'] . ' ' . $equipo['marca'] . ' ' . $equipo['modelo']);
        $componente_desc = htmlspecialchars($tipo . ' ' . $marca . ' ' . $modelo . ' (' . $estado . ')');

        auditar("Agregó el componente: {$componente_desc} al equipo ID {$equipo_id} ({$equipo_desc}).");

        echo "<p class='muted'>Componente agregado correctamente.</p>";
    } else {
        // Manejar error de inserción, si es necesario
        echo "<p class='muted' style='color: red;'>Error al agregar el componente: " . $mysqli->error . "</p>";
    }
    //echo "<p class='muted'>Componente agregado correctamente.</p>";
    //---------------------------------FIN DE LA INSERCION DE LA AUDITORIA---------------------------
}

// listar componentes
$stmt = $mysqli->prepare("SELECT * FROM componentes WHERE equipo_id=? ORDER BY creado_en DESC");
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$componentes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<head>
    <link rel="stylesheet" href="../css/form_componentes_equipos.css">

</head>
<?php
include __DIR__ . '/navbar.php';
?>


<div class="card">

    <h2>Componentes para <?= htmlspecialchars($equipo['tipo']) ?><?= $equipo['marca'] ? ' · ' . htmlspecialchars($equipo['marca']) : '' ?><?= $equipo['modelo'] ? ' ' . htmlspecialchars($equipo['modelo']) : '' ?></h2>
    <p class="muted">Área: <?= htmlspecialchars($equipo['area']) ?><?= $equipo['sala'] ? ' / ' . htmlspecialchars($equipo['sala']) : '' ?></p>
    <h3>Agregar componente</h3>
    <form method="post">
        <div class="row">
            <div class="col">
                <label>Tipo</label>
                <input type="text" name="tipo" placeholder="HDMI, Control, Zapatilla, etc." required>
            </div>
            <div class="col">
                <label>Marca</label>
                <input type="text" name="marca">
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label>Modelo</label>
                <input type="text" name="modelo">
            </div>
            <div class="col">
                <label>Estado</label>
                <select name="estado">
                    <option value="bueno">Bueno</option>
                    <option value="en_uso">En uso</option>
                    <option value="dañado">Dañado</option>
                    <option value="fuera_servicio">Fuera de servicio</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-full">
                <label>Observación</label>
                <textarea name="observacion" placeholder="Comentarios adicionales (opcional)"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Agregar componente</button>
    </form>
</div>