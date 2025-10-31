<?php
require __DIR__ . '/../init.php';
require_login();

// obtener estudiantes
$stmt = $mysqli->query("SELECT id, ci, nombre, apellido, email FROM docentes ORDER BY apellido, nombre");
$rows = $stmt->fetch_all(MYSQLI_ASSOC);

$currentPage = basename(__FILE__);
?>

<head>
    <link rel="stylesheet" href="../css/tabla_docentes_listar.css">
</head>

    
<?php
include __DIR__ . '/navbar.php';
?>
<div class="container">
    <div class="actions">
        <a class="btn" href="/inventario_uni/public/docentes_registro.php">+ Nuevo docente</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>CI</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!$rows): ?>
                <tr>
                    <td colspan="5">No hay docentes registrados.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td data-label="CI"><?= htmlspecialchars($r['ci']) ?></td>
                        <td data-label="Nombre"><?= htmlspecialchars($r['nombre']) ?></td>
                        <td data-label="Apellido"><?= htmlspecialchars($r['apellido']) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($r['email']) ?></td>
                        <td data-label="Acciones">
                            <a class="btn btn-sm" href="/inventario_uni/public/docentes_editar_form.php?id=<?= $r['id'] ?>">Editar</a>
                            <a class="btn btn-sm btn-danger"
                                href="/inventario_uni/public/docentes_eliminar.php?id=<?= $r['id'] ?>"
                                onclick="return confirm('¿Seguro que deseas eliminar este docente?');">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>