<?php
require_once '../init.php';
require_login();

$db = db();
$user = user();
$rol = $user['rol'];

// Solo titular de área o admin pueden devolver manualmente
if ($rol !== 'admin' && $rol !== 'titular_area') {
    header('Location: ../index.php');
    exit;
}

// Obtener préstamos activos (prestados) para devolución manual
$stmt = $db->query("SELECT p.id, e.nombre AS equipo, e.tipo, e.marca, e.modelo, u.nombre AS responsable
                    FROM prestamos p
                    JOIN equipos e ON p.equipo_id = e.id
                    JOIN usuarios u ON p.usuario_id = u.id
                    WHERE p.estado = 'activo'");
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prestamo_id'])) {
    $prestamo_id = intval($_POST['prestamo_id']);
    $nombre_tercero = trim($_POST['nombre_tercero'] ?? '');
    $ci_tercero = trim($_POST['ci_tercero'] ?? '');

    /* Marcar préstamo como devuelto y registrar datos del tercero
    $db->prepare("UPDATE prestamos 
        SET estado = 'devuelto', fecha_devolucion = NOW(), 
            devuelto_por_tercero_nombre = ?, devuelto_por_tercero_ci = ?
        WHERE id = ?")
        ->execute([$nombre_tercero, $ci_tercero, $prestamo_id]);
    $db->prepare("UPDATE equipos SET prestado = 0 WHERE id = (SELECT equipo_id FROM prestamos WHERE id = ?)
        ")->execute([$prestamo_id]);*/


    //-************************BUSCAR DATOS PARA AUDITORÍA******************-
    // 💡 Necesitamos obtener el equipo_id y el responsable para la auditoría
    // Ejecutamos una consulta para obtener el equipo_id y los nombres
    $stmt_info = $db->prepare("
        SELECT p.equipo_id, e.serial_interno, u.nombre AS responsable_nombre
        FROM prestamos p
        JOIN equipos e ON e.id = p.equipo_id
        JOIN usuarios u ON u.id = p.usuario_id
        WHERE p.id = ?
    ");
    $stmt_info->execute([$prestamo_id]);
    $info = $stmt_info->fetch(PDO::FETCH_ASSOC);

    if ($info) {
        $equipo_id = $info['equipo_id'];
        $equipo_serial = htmlspecialchars($info['serial_interno']);
        $responsable_original = htmlspecialchars($info['responsable_nombre']);

        // Marcar préstamo como devuelto y registrar datos del tercero
        $db->prepare("UPDATE prestamos 
            SET estado = 'devuelto', fecha_devolucion = NOW(), 
                devuelto_por_tercero_nombre = ?, devuelto_por_tercero_ci = ?
            WHERE id = ?")
            ->execute([$nombre_tercero, $ci_tercero, $prestamo_id]);

        // Marcar equipo como no prestado
        $db->prepare("UPDATE equipos SET prestado = 0 WHERE id = ?")
            ->execute([$equipo_id]);

        //-----------------INSERCIÓN DE LA AUDITORÍA---------------
        $accion_msg = "Devolución MANUAL del Préstamo ID {$prestamo_id} (Equipo Serial: {$equipo_serial}, Resp. Original: {$responsable_original}). Entregado por tercero: {$nombre_tercero} (CI: {$ci_tercero}).";
        // La función auditar() ya toma el ID del usuario logueado
        auditar($accion_msg);
        // --------------------------------

        $msg = "Devolución registrada correctamente.";
        /****************************************************** */
    } else {
        $msg = "Error: Préstamo no encontrado para la auditoría.";
    }
    //***********  ************************************ */
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Devolución Manual — Inventario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: system-ui, Segoe UI, Arial, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            margin: 0
        }

        .container {
            max-width: 700px;
            margin: 32px auto;
            padding: 24px;
            background: #111827;
            border-radius: 12px
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px
        }

        th,
        td {
            padding: 8px;
            border-bottom: 1px solid #222
        }

        th {
            background: #1f2937
        }

        button {
            background: #2563eb;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer
        }

        button:hover {
            background: #1d4ed8
        }

        .msg {
            background: #1e293b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px
        }

        input {
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #222;
            width: 90%
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Devolución Manual de Equipo (por tercero)</h2>
        <?php if ($msg): ?>
            <div class="msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>
        <?php if ($prestamos): ?>
            <table>
                <tr>
                    <th>Equipo</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Responsable</th>
                    <th>Devolución</th>
                </tr>
                <?php foreach ($prestamos as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['equipo']) ?></td>
                        <td><?= htmlspecialchars($p['tipo']) ?></td>
                        <td><?= htmlspecialchars($p['marca']) ?></td>
                        <td><?= htmlspecialchars($p['modelo']) ?></td>
                        <td><?= htmlspecialchars($p['responsable']) ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('¿Registrar devolución de este equipo por un tercero?');">
                                <input type="hidden" name="prestamo_id" value="<?= $p['id'] ?>">
                                <input type="text" name="nombre_tercero" required placeholder="Nombre del tercero">
                                <input type="text" name="ci_tercero" required placeholder="Documento/C.I.">
                                <button type="submit">Registrar devolución</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No hay préstamos activos para devolución manual.</p>
        <?php endif; ?>
        <a href="../index.php" style="color:#93c5fd">← Volver al panel</a>
    </div>
</body>

</html>