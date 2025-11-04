<?php
require __DIR__ . '/../init.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    $error = "No se especificó un docente válido.";
} else {
    // Obtener datos del docente
    $stmt = $mysqli->prepare("SELECT * FROM docentes WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $docente = $stmt->get_result()->fetch_assoc();

    if (!$docente) {
        $error = "Docente no encontrado.";
    }
}

$ok = false;
$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    try {
        // --- Eliminar cesiones relacionadas ---
        $stmt_del_cesiones = $mysqli->prepare("DELETE FROM cesiones WHERE cedente_id = ?");
        $stmt_del_cesiones->bind_param("i", $id);
        $stmt_del_cesiones->execute();

        // --- Eliminar docente ---
        $stmt = $mysqli->prepare("DELETE FROM docentes WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $ok = true;

            // Registrar en auditoría
            $docente_nombre = htmlspecialchars($docente['nombre'] . ' ' . $docente['apellido']);
            $docente_ci = htmlspecialchars($docente['ci']);
            $accion_msg = "Eliminó el docente ID {$id}: {$docente_nombre} (CI: {$docente_ci}), junto con sus cesiones asociadas.";
            auditar($accion_msg, 'acción_docentes');
        } else {
            $alert = "⚠️ No se pudo eliminar el docente. Inténtelo nuevamente.";
        }
    } catch (Exception $e) {
        $alert = "⚠️ Error inesperado al intentar eliminar. Contacte al administrador.";
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

    <style>

        .card {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 25px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .alert {
            background: #ffe6e6;
            color: #b30000;
            border-left: 5px solid #ff4d4d;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .ok {
            background: #e6ffed;
            color: #0a7a1e;
            border-left: 5px solid #00c853;
            padding: 15px;
            border-radius: 8px;
        }

        button {
            background: #e53935;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.2s;
        }

        button:hover {
            background: #c62828;
        }

        .secondary {
            display: inline-block;
            text-decoration: none;
            color: #555;
            margin-left: 10px;
            border: 1px solid #ccc;
            padding: 10px 15px;
            border-radius: 6px;
            transition: 0.2s;
        }

        .secondary:hover {
            background: #f1f1f1;
        }

        .muted {
            text-align: center;
            margin-top: 15px;
            color: #666;
        }

        .muted a {
            color: #1976d2;
            text-decoration: none;
        }

        .muted a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>

<div class="card">
    <h1>Eliminar docente</h1>

    <?php if ($ok): ?>
        <div class="ok">✅ Docente y sus cesiones asociadas fueron eliminados correctamente.</div>
        <div class="muted"><a href="/inventario_uni/public/docentes_listar.php">Volver a la lista</a></div>
    <?php elseif (!empty($error)): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
        <div class="muted"><a href="/inventario_uni/public/docentes_listar.php">Volver</a></div>
    <?php else: ?>
        <?php if (!empty($alert)): ?><div class="alert"><?= htmlspecialchars($alert) ?></div><?php endif; ?>
        <p>¿Estás seguro que deseas eliminar al docente <strong><?= htmlspecialchars($docente['nombre'] . ' ' . $docente['apellido']) ?></strong> (CI: <?= htmlspecialchars($docente['ci']) ?>)?</p>
        <p style="color:#b30000; font-weight:bold;">⚠️ Esta acción también eliminará todas las cesiones asociadas a este docente.</p>

        <form method="post">
            <button type="submit">Sí, eliminar</button>
            <a href="/inventario_uni/public/docentes_listar.php" class="secondary">Cancelar</a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
