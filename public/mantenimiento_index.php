<?php
require_once '../init.php';
require_login();

$db = db();
$user = user();
$rol = $user['rol'];

// Solo admin o titular de área pueden gestionar mantenimiento
if ($rol !== 'admin' && $rol !== 'titular_area') {
    header('Location: ../index.php');
    exit;
}

// Marcar equipo en mantenimiento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['equipo_id'], $_POST['destino'], $_POST['motivo'])) {
    $equipo_id = intval($_POST['equipo_id']);
    $destino = trim($_POST['destino']);
    $motivo = trim($_POST['motivo']);
    $fecha = date('Y-m-d H:i:s');
    $db->prepare("UPDATE equipos SET en_mantenimiento=1, destino_mantenimiento=?, fecha_envio_mantenimiento=?, motivo_mantenimiento=? WHERE id=?")
        ->execute([$destino, $fecha, $motivo, $equipo_id]);
    $msg = "Equipo marcado en mantenimiento.";
}

// Marcar equipo como disponible (retorno de mantenimiento)
if (isset($_GET['retornar'])) {
    $equipo_id = intval($_GET['retornar']);
    $db->prepare("UPDATE equipos SET en_mantenimiento=0, destino_mantenimiento=NULL, fecha_envio_mantenimiento=NULL, motivo_mantenimiento=NULL WHERE id=?")
        ->execute([$equipo_id]);
    $msg = "Equipo marcado como disponible.";
}

// Listar equipos
$equipos = $db->query("SELECT * FROM equipos")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mantenimiento de Equipos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
        .container{max-width:900px;margin:32px auto;padding:24px;background:#111827;border-radius:12px}
        table{width:100%;border-collapse:collapse;margin-bottom:24px}
        th,td{padding:8px;border-bottom:1px solid #222}
        th{background:#1f2937}
        .msg{background:#1e293b;padding:12px;border-radius:8px;margin-bottom:16px}
        form.inline{display:inline}
        input,textarea{width:100%;padding:6px;margin:2px 0;border-radius:6px;border:1px solid #222}
        button{background:#2563eb;color:#fff;padding:8px 16px;border:none;border-radius:6px;cursor:pointer}
        button:hover{background:#1d4ed8}
    </style>
</head>
<body>
<div class="container">
    <h2>Mantenimiento de Equipos</h2>
    <?php if (!empty($msg)): ?>
        <div class="msg"><?=htmlspecialchars($msg)?></div>
    <?php endif; ?>
    <h3>Marcar equipo en mantenimiento</h3>
    <form method="post">
        <label>Equipo:
            <select name="equipo_id" required>
                <option value="">Seleccione</option>
                <?php foreach ($equipos as $e): if (!$e['en_mantenimiento']): ?>
                    <option value="<?=$e['id']?>"><?=htmlspecialchars($e['nombre'].' ('.$e['tipo'].')')?></option>
                <?php endif; endforeach; ?>
            </select>
        </label>
        <label>Destino:
            <input type="text" name="destino" required>
        </label>
        <label>Motivo:
            <textarea name="motivo" required></textarea>
        </label>
        <button type="submit">Marcar en mantenimiento</button>
    </form>

    <h3>Equipos en mantenimiento</h3>
    <table>
        <tr>
            <th>Equipo</th>
            <th>Destino</th>
            <th>Fecha envío</th>
            <th>Motivo</th>
            <th>Acción</th>
        </tr>
        <?php foreach ($equipos as $e): if ($e['en_mantenimiento']): ?>
            <tr>
                <td><?=htmlspecialchars($e['nombre'].' ('.$e['tipo'].')')?></td>
                <td><?=htmlspecialchars($e['destino_mantenimiento'])?></td>
                <td><?=htmlspecialchars($e['fecha_envio_mantenimiento'])?></td>
                <td><?=htmlspecialchars($e['motivo_mantenimiento'])?></td>
                <td>
                    <a href="?retornar=<?=$e['id']?>" onclick="return confirm('¿Marcar equipo como disponible?')">Retornar</a>
                </td>
            </tr>
        <?php endif; endforeach; ?>
    </table>
    <a href="../index.php" style="color:#93c5fd">← Volver al panel</a>
</div>
</body>
</html>
?>