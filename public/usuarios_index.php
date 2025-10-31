<?php
// public/usuarios_index.php
require __DIR__ . '/../init.php';
require_login();

$rol = user()['rol'];

// Primero, verifiquemos la estructura de la tabla usuarios
$check_table_sql = "DESCRIBE usuarios";
$table_result = $mysqli->query($check_table_sql);
$table_structure = $table_result->fetch_all(MYSQLI_ASSOC);

// Obtener todos los roles disponibles para el formulario
$roles_sql = "SELECT id, nombre as rol FROM roles";
$roles_result = $mysqli->query($roles_sql);
$roles = $roles_result->fetch_all(MYSQLI_ASSOC);

// Determinar el nombre correcto del campo de rol en la tabla usuarios
$rol_field = 'role_id'; // valor por defecto
foreach ($table_structure as $field) {
  if (in_array($field['Field'], ['role_id', 'roles_id', 'rol_id', 'rol'])) {
    $rol_field = $field['Field'];
    break;
  }
}

// Procesar actualización o eliminación si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
  $user_id = intval($_POST['user_id']);

  if (isset($_POST['delete'])) {
    // -------- ELIMINAR USUARIO --------
    $nombre = $_POST['nombre'];
    $ci = $_POST['ci'];
    $delete_sql = "DELETE FROM usuarios WHERE id = ?";
    $delete_stmt = $mysqli->prepare($delete_sql);
    $delete_stmt->bind_param("i", $user_id);

    //--------insertar auditoria de la accion --------
    auditar("Eliminó al usuario '{$nombre}' (C.I: {$ci}) con ID {$user_id}", 'acción_usuario'); // <-- Modificado con tipo
    //----------------------

    if ($delete_stmt->execute()) {
      header("Location: usuarios_index.php?deleted=1");
      exit;
    } else {
      $error = "Error al eliminar usuario: " . $mysqli->error;
    }
  } else {
    // -------- ACTUALIZAR USUARIO --------
 $nombre_nuevo = $_POST['nombre'];
 $ci_nuevo = $_POST['ci'];
 $role_id_nuevo = $_POST['role_id'];
// 1. OBTENER DATOS ACTUALES DEL USUARIO ANTES DE LA EDICIÓN
    $stmt_prev = $mysqli->prepare("
        SELECT u.nombre, u.ci, u.$rol_field, r.nombre AS rol_nombre_prev 
        FROM usuarios u
        JOIN roles r ON r.id = u.$rol_field
        WHERE u.id = ?
    ");
    $stmt_prev->bind_param("i", $user_id);
    $stmt_prev->execute();
    $usuario_prev = $stmt_prev->get_result()->fetch_assoc();
    $stmt_prev->close();

    $cambios = [];
    $rol_cambiado = false;

    // 2. DETECTAR CAMBIOS
    if ($usuario_prev['nombre'] !== $nombre_nuevo) {
        $cambios[] = "Nombre ('{$usuario_prev['nombre']}' -> '{$nombre_nuevo}')";
    }
    if ($usuario_prev['ci'] !== $ci_nuevo) {
        $cambios[] = "C.I. ('{$usuario_prev['ci']}' -> '{$ci_nuevo}')";
    }
    if ($usuario_prev[$rol_field] != $role_id_nuevo) {
        $rol_cambiado = true;
        
        // Obtener el nombre del nuevo rol
        $stmt_rol = $mysqli->prepare("SELECT nombre FROM roles WHERE id = ?");
        $stmt_rol->bind_param("i", $role_id_nuevo);
        $stmt_rol->execute();
        $rol_nombre_nuevo = $stmt_rol->get_result()->fetch_assoc()['nombre'] ?? 'Desconocido';
        $stmt_rol->close();

        $cambios[] = "Rol ('{$usuario_prev['rol_nombre_prev']}' -> '{$rol_nombre_nuevo}')";
    }
    
    // Si no hubo cambios en los datos, forzamos un mensaje simple o evitamos la auditoría.
    if (empty($cambios)) {
        $accion_msg = "Intentó editar al usuario ID {$user_id} '{$nombre_nuevo}' pero no se detectaron cambios.";
    } elseif ($rol_cambiado && count($cambios) === 1) {
        // Opción A: Solo cambió el rol
        $accion_msg = "Editó al usuario ID {$user_id} '{$nombre_nuevo}' y le cambió el rol a '{$rol_nombre_nuevo}'.";
    } else {
        // Opción B: Cambió otros campos (o varios, incluido el rol)
        $cambios_str = implode(', ', $cambios);
        $accion_msg = "Editó al usuario ID {$user_id} '{$nombre_nuevo}'. Campos modificados: {$cambios_str}.";
    }


 // 3. Ejecutar la actualización (se ejecuta siempre si no hay error de lógica anterior)
 $update_sql = "UPDATE usuarios SET nombre = ?, ci = ?, $rol_field = ? WHERE id = ?";
 $update_stmt = $mysqli->prepare($update_sql);
 $update_stmt->bind_param("ssii", $nombre_nuevo, $ci_nuevo, $role_id_nuevo, $user_id);

 // 4. Insertar auditoria (se hace antes de la redirección)
 auditar($accion_msg, 'edicion_usuario'); // Usamos el tipo 'edicion_usuario'

 if ($update_stmt->execute()) {
 header("Location: usuarios_index.php?ok=1");
 exit;
 } else {
 $error = "Error al actualizar usuario: " . $mysqli->error;
 }
 }
}

// Cargar usuarios después de procesar acción
$sql = "
  SELECT u.id, u.nombre, u.ci, u.$rol_field, r.nombre as rol_nombre
  FROM usuarios u
  JOIN roles r ON r.id = u.$rol_field
  ORDER BY u.creado_en DESC
";

try {
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  $usuarios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
  $error = "Error al cargar usuarios: " . $e->getMessage();
  $usuarios = [];
}
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Gestión de Usuarios</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/tabla_usuarios.css">
</head>

<body>
  <?php include __DIR__ . '/navbar.php'; ?>

  <div class="container">
    <div class="actions">
      <a class="btn" href="../auth/register.php">+ Registrar Usuario</a>
    </div>
    <h2>Gestión de Usuarios</h2>

    <?php if (isset($_GET['ok'])): ?>
      <div class="ok">Usuario actualizado correctamente ✅</div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
      <div class="deleted">Usuario eliminado correctamente 🗑️</div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>CI</th>
          <th>Rol</th>
          <th>Acciones</th>
        </tr>
      </thead>


      <?php foreach ($usuarios as $usuario): ?>
       <tr>
    <form method="post">
        <td data-label="ID"><?= htmlspecialchars($usuario['id']) ?></td>
        
        <td data-label="NOMBRE">
            <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>">
        </td>
        
        <td data-label="CI">
            <input type="text" name="ci" value="<?= htmlspecialchars($usuario['ci']) ?>">
        </td>
        
        <td data-label="ROL">
            <select name="role_id">
                <?php foreach ($roles as $rol_option): ?>
                    <option value="<?= $rol_option['id'] ?>" <?= ($usuario[$rol_field] == $rol_option['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($rol_option['rol']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        
        <td data-label="ACCIONES">
            <input type="hidden" name="user_id" value="<?= $usuario['id'] ?>">
            <button type="submit" name="update" class="btn btn-primary">Guardar</button>
            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">Eliminar</button>
        </td>
    </form>
</tr>
      <?php endforeach; ?>
    </table>
  </div>
</body>

</html>