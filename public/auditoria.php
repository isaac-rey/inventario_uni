<?php
require __DIR__ . '/../init.php';
require_login();

$rol = user()['rol'];

// Solo el admin puede ver la auditoría
if ($rol !== 'admin') {
    header("Location: /inventario_uni/");
    exit;
}

$sql = "SELECT a.id, a.accion, a.fecha, u.nombre
        FROM auditoria a
        JOIN usuarios u ON a.usuario_id = u.id
        ORDER BY a.fecha DESC
        LIMIT 100";

$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Auditoría - Inventario</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>

    <main class="dashboard">
        <h1>Registro de Auditoría</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acción</th>
                <th>Fecha</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['accion']) ?></td>
                    <td><?= $row['fecha'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
</body>
</html>
