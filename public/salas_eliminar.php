<?php
// public/salas_eliminar.php
require __DIR__ . '/../init.php';
require_login();

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("Sala no especificada.");
}

// Obtener datos de la sala
$stmt = $mysqli->prepare("
    SELECT s.*, a.nombre AS area_nombre
    FROM salas s
    INNER JOIN areas a ON a.id = s.area_id
    WHERE s.id=? LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$sala = $stmt->get_result()->fetch_assoc();

if (!$sala) {
    die("Sala no encontrada.");
}

$ok = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verificar si la sala tiene equipos asociados
    $stmt_check = $mysqli->prepare("SELECT COUNT(*) AS total FROM equipos WHERE sala_id=?");
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $total_equipos = $stmt_check->get_result()->fetch_assoc()['total'] ?? 0;

    if ($total_equipos > 0) {
        $error = "No se puede eliminar la sala porque tiene equipos asociados.";
    } else {
        // Eliminar la sala
        $stmt = $mysqli->prepare("DELETE FROM salas WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $ok = true;

            // ------- INSERCIÓN DE AUDITORÍA -------
            $nombre_sala = htmlspecialchars($sala['nombre']);
            $nombre_area = htmlspecialchars($sala['area_nombre']);
            $accion_msg = "Eliminó la Sala ID {$id}: '{$nombre_sala}' del área '{$nombre_area}'.";
            auditar($accion_msg);
            // --------------------------------------
        } else {
            $error = "No se pudo eliminar la sala.";
        }
    }
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Eliminar Sala</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/docentes_registro.css">
</head>

<body>
<?php include __DIR__ . '/navbar.php'; ?>

<div class="card">
    <h1>Eliminar Sala</h1>

    <?php if ($ok): ?>
        <div class="ok">Sala eliminada correctamente.</div>
        <div class="muted"><a href="/inventario_uni/public/salas_index.php">Volver a la lista</a></div>
    <?php else: ?>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <p>¿Estás seguro que deseas eliminar la sala 
            <strong><?= htmlspecialchars($sala['nombre']) ?></strong> 
            del área <strong><?= htmlspecialchars($sala['area_nombre']) ?></strong>?
        </p>

        <form method="post">
            <button type="submit" style="background:red; color:white;">Sí, eliminar</button>
            <a href="/inventario_uni/public/salas_index.php" class="secondary">Cancelar</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
