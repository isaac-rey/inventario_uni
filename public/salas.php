<?php
require __DIR__ . '/../init.php';
require_login();

// Obtener salas con información del área
$stmt = $mysqli->query("
  SELECT 
    s.id, 
    s.nombre, 
    s.descripcion, 
    s.creado_en,
    a.nombre AS area_nombre
  FROM salas s
  INNER JOIN areas a ON s.area_id = a.id
  ORDER BY a.nombre, s.nombre
");
$rows = $stmt->fetch_all(MYSQLI_ASSOC);

$currentPage = basename(__FILE__);
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Listado de Salas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/tabla_equipos_index.css">
</head>

<body>
  <?php include __DIR__ . '/navbar.php'; ?>
  
  <div class="container">
    <div class="actions">
      <a class="btn" href="/inventario_uni/public/salas_registro.php">+ Nueva Sala</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>Área</th>
          <th>Nombre de Sala</th>
          <th>Descripción</th>
          <th>Fecha Creación</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$rows): ?>
          <tr>
            <td colspan="5" class="muted">No hay salas registradas.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td data-label="Área">
                <span class="badge ok"><?= htmlspecialchars($r['area_nombre']) ?></span>
              </td>
              <td data-label="Nombre de Sala">
                <strong><?= htmlspecialchars($r['nombre']) ?></strong>
              </td>
              <td data-label="Descripción">
                <?= $r['descripcion'] ? htmlspecialchars($r['descripcion']) : '<span style="color: #a0aec0; font-style: italic;">Sin descripción</span>' ?>
              </td>
              <td data-label="Fecha Creación">
                <?= date('d/m/Y H:i', strtotime($r['creado_en'])) ?>
              </td>
              <td data-label="Acciones">
                <a class="btn btn-sm" href="/inventario_uni/public/salas_editar_form.php?id=<?= $r['id'] ?>">Editar</a>
                <a class="btn btn-sm btn-danger"
                   href="/inventario_uni/public/salas_eliminar.php?id=<?= $r['id'] ?>"
                   onclick="return confirm('¿Seguro que deseas eliminar esta sala?\n\nNota: Solo se puede eliminar si no tiene equipos asociados.');">
                  Eliminar
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>

</html>