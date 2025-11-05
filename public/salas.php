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
  <style>
    /* === Estilo Global de Body para la fuente (Asegurando la fuente base) === */
    body {
        font-family: 'Segoe UI', Roboto, sans-serif; /* Fuente base */
        background: #f4f6f9;
        margin: 0;
        padding: 0;
    }

    /* Contenedor de acciones para usar Flexbox y espaciar los botones */
    td[data-label="Acciones"] {
        white-space: nowrap; /* Evita que los botones se envuelvan si hay espacio */
    }
    
    td[data-label="Acciones"] > a {
        margin-right: 8px; /* Espacio entre los botones 'Editar' y 'Eliminar' */
        display: inline-block; /* Asegura que los márgenes funcionen */
    }
    
    /* Estilo base para los botones de acción (.btn, .btn-sm) */
    .btn, .btn-sm {
        /* Dimensiones y forma */
        padding: 8px 18px; /* Relleno amplio */
        border: none;
        border-radius: 8px; /* Bordes redondeados */
        font-size: 1rem; /* Buen tamaño de fuente */
        font-weight: 600; /* Texto audaz */
        color: #fff !important; /* Texto blanco forzado */
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.2s;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Sombra suave */
        
        /* Aseguramos que los botones de acción dentro de la tabla tengan el mismo estilo */
        margin-bottom: 0; 
        line-height: 1.5;

        /* === AJUSTE DE TIPOGRAFÍA (INHERIT) === */
        /* Hereda la fuente del body para la tipografía limpia deseada */
        font-family: inherit; 
    }

    /* Estilo para el botón primario (Editar/Guardar) */
    .btn:not(.btn-danger), 
    .btn-sm:not(.btn-danger) {
        background: #2a5298; /* Azul primario fuerte */
    }

    .btn:not(.btn-danger):hover, 
    .btn-sm:not(.btn-danger):hover {
        background: #1e3c72; /* Azul más oscuro al pasar el ratón */
    }

    /* Estilo para el botón de peligro (Eliminar) */
    .btn-danger, 
    .btn-sm.btn-danger {
        background: #dc2626; /* Rojo de peligro fuerte */
    }

    .btn-danger:hover, 
    .btn-sm.btn-danger:hover {
        background: #b91c1c; /* Rojo más oscuro al pasar el ratón */
    }
    
    /* Pequeño ajuste para el botón '+ Nueva Sala' fuera de la tabla */
    .actions .btn {
        padding: 10px 20px; /* Un poco más grande para el botón principal */
    }

    /* Estilos para que el badge ok se vea bien si es necesario */
    .badge.ok {
        background-color: #48bb78; /* Verde para 'ok' */
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 600;
    }
  </style>
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