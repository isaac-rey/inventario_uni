<?php
// public/estudiantes_eliminar.php
//require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("Docente no especificado.");
}

// Obtener datos del docente
$stmt = $mysqli->prepare("SELECT * FROM docentes WHERE id=? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$docente = $stmt->get_result()->fetch_assoc();

if (!$docente) {
    die("Docente no encontrado.");
}

$ok = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aquí se podría agregar validación extra, como chequear préstamos activos
    $stmt = $mysqli->prepare("DELETE FROM docentes WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $ok = true;
        //---------INSERCIÓN DE LA AUDITORÍA-------------
        $docente_nombre = htmlspecialchars($docente['nombre'] . ' ' . $docente['apellido']);
        $docente_ci = htmlspecialchars($docente['ci']);
        
        $accion_msg = "Eliminó el docente ID {$id}: {$docente_nombre} (CI: {$docente_ci}).";
        // El ID del usuario logueado que realiza la acción se toma de la sesión (user())
        // CLAVE: Añadir el tipo de acción 'accion_docentes'
auditar($accion_msg, 'acción_docentes');;
        // ----------------------------------------------
    } else {
        $error = "No se pudo eliminar el docente.";
    }
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Eliminar docente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/docentes_registro.css">
</head>

<body>
<?php include __DIR__ . '/navbar.php'; ?>

<div class="card">
    <h1>Eliminar docente</h1>

    <?php if ($ok): ?>
        <div class="ok">Docente eliminado correctamente.</div>
        <div class="muted"><a href="/inventario_uni/public/docentes_listar.php">Volver a la lista</a></div>
    <?php else: ?>
        <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <p>¿Estás seguro que deseas eliminar al docente <strong><?= htmlspecialchars($docente['nombre'] . ' ' . $docente['apellido']) ?></strong> (CI: <?= htmlspecialchars($docente['ci']) ?>)?</p>

        <form method="post">
            <button type="submit" style="background:red; color:white;">Sí, eliminar</button>
            <a href="/inventario_uni/public/docentes_listar.php" class="secondary">Cancelar</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>