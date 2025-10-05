<?php
// public/estudiantes_eliminar.php
//require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("Estudiante no especificado.");
}

// Obtener datos del estudiante
$stmt = $mysqli->prepare("SELECT * FROM estudiantes WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$estudiante = $stmt->get_result()->fetch_assoc();

if (!$estudiante) {
    die("Estudiante no encontrado.");
}

$ok = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aquí se podría agregar validación extra, como chequear préstamos activos
    $stmt = $mysqli->prepare("DELETE FROM estudiantes WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        //-------------------Auditar la eliminación------------------------
        $nombre_completo = $estudiante['nombre'] . ' ' . $estudiante['apellido'];
        auditar("Eliminó al estudiante ID {$id} ({$nombre_completo}).");
        
        $ok = true;
    } else {
        $error = "No se pudo eliminar el estudiante.";
    }
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Eliminar estudiante</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/estudiantes_registro.css">
</head>

<body>
<?php include __DIR__ . '/navbar.php'; ?>

<div class="card">
    <h1>Eliminar estudiante</h1>

    <?php if ($ok): ?>
        <div class="ok">Estudiante eliminado correctamente.</div>
        <div class="muted"><a href="/inventario_uni/public/estudiantes_listar.php">Volver a la lista</a></div>
    <?php else: ?>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <p>¿Estás seguro que deseas eliminar al estudiante <strong><?= htmlspecialchars($estudiante['nombre'] . ' ' . $estudiante['apellido']) ?></strong> (CI: <?= htmlspecialchars($estudiante['ci']) ?>)?</p>

        <form method="post">
            <button type="submit" style="background:red; color:white;">Sí, eliminar</button>
            <a href="/inventario_uni/public/estudiantes_listar.php" class="secondary">Cancelar</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>