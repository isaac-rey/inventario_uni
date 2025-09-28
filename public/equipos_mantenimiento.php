<?php
require __DIR__ . '/../init.php';
require_login();

$equipo_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$equipo_id) {
    echo '<p class="muted">Equipo no válido.</p>';
    exit;
}

// Obtener mantenimientos del equipo
$query = "SELECT * FROM mantenimientos WHERE equipo_id = ? ORDER BY fecha_envio DESC";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $equipo_id);
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="card" style="margin:0;">
    <h2>
        Historial de Mantenimientos
        <span class="pill"><?= count($rows) ?></span>
    </h2>

    <?php if (!$rows): ?>
        <p class="muted">No hay mantenimientos registrados para este equipo.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario ID</th>
                    <th>Destino</th>
                    <th>Motivo</th>
                    <th>Fecha Envío</th>
                    <th>Fecha Devolución</th>
                    <th>Solucionado</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id']) ?></td>
                        <td><?= $r['usuario_id'] ?? '<span class="muted">N/A</span>' ?></td>
                        <td><?= htmlspecialchars($r['destino']) ?></td>
                        <td><?= htmlspecialchars($r['motivo']) ?></td>
                        <td><?= htmlspecialchars($r['fecha_envio']) ?></td>
                        <td><?= $r['fecha_devolucion'] ?? '<span class="muted">No devuelto</span>' ?></td>
                        <td class="<?= $r['solucionado'] ? 'status-active' : 'status-returned' ?>">
                            <?= $r['solucionado'] ? 'Sí' : 'No' ?>
                        </td>
                        <td><?= $r['observaciones'] ?? '<span class="muted">-</span>' ?></td>
                        <td>
                            <a href="mantenimientos_editar.php?id=<?= $r['id'] ?>" class="btn">Editar</a>
                            <a href="mantenimientos_eliminar.php?id=<?= $r['id'] ?>" class="btn btn-danger" onclick="return confirm('¿Eliminar este mantenimiento?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
