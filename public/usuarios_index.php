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
        $delete_sql = "DELETE FROM usuarios WHERE id = ?";
        $delete_stmt = $mysqli->prepare($delete_sql);
        $delete_stmt->bind_param("i", $user_id);

        if ($delete_stmt->execute()) {
            header("Location: usuarios_index.php?deleted=1");
            exit;
        } else {
            $error = "Error al eliminar usuario: " . $mysqli->error;
        }
    } else {
        // -------- ACTUALIZAR USUARIO --------
        $nombre = $_POST['nombre'];
        $ci = $_POST['ci'];
        $role_id = $_POST['role_id']; // Cambiado de $roles_id a $role_id
        
        $update_sql = "UPDATE usuarios SET nombre = ?, ci = ?, $rol_field = ? WHERE id = ?";
        $update_stmt = $mysqli->prepare($update_sql);
        $update_stmt->bind_param("ssii", $nombre, $ci, $role_id, $user_id);
        
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
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px;background:#111827}
    a{color:#93c5fd;text-decoration:none}
    .container{padding:24px}
    table{width:100%;border-collapse:collapse;margin-top:16px}
    th,td{padding:12px;text-align:left;border-bottom:1px solid #1f2937}
    th{background:#1f2937}
    input,select,button{padding:6px 10px;border-radius:6px;border:none}
    input,select{background:#111827;color:#e2e8f0}
    button{background:#2563eb;color:white;cursor:pointer}
    button:hover{background:#1d4ed8}
    .ok{background:#16a34a;color:white;padding:8px 12px;border-radius:8px;display:inline-block;margin-bottom:12px}
    .deleted{background:#f59e0b;color:white;padding:8px 12px;border-radius:8px;display:inline-block;margin-bottom:12px}
    .error{background:#dc2626;color:white;padding:8px 12px;border-radius:8px;display:inline-block;margin-bottom:12px}
    .badge{background:#3b82f6;padding:4px 8px;border-radius:4px;font-size:0.8em}
  </style>
</head>
<body>
  <header>
    <div><a href="/inventario_uni/index.php">← Panel</a></div>
    <div>Inventario — <span class="badge"><?=htmlspecialchars($rol)?></span></div>
    <div><?=htmlspecialchars(user()['nombre'])?> · <a href="/inventario_uni/auth/logout.php">Salir</a></div>
  </header>

  <div class="container">
    <h2>Gestión de Usuarios</h2>

    <?php if (isset($_GET['ok'])): ?>
      <div class="ok">Usuario actualizado correctamente ✅</div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
      <div class="deleted">Usuario eliminado correctamente 🗑️</div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
      <div class="error"><?=$error?></div>
    <?php endif; ?>

    <table>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>CI</th>
        <th>Rol</th>
        <th>Acciones</th>
      </tr>
      
      <?php foreach ($usuarios as $usuario): ?>
      <tr>
        <form method="post">
          <td><?=htmlspecialchars($usuario['id'])?></td>
          <td><input type="text" name="nombre" value="<?=htmlspecialchars($usuario['nombre'])?>"></td>
          <td><input type="text" name="ci" value="<?=htmlspecialchars($usuario['ci'])?>"></td>
          <td>
            <select name="role_id">
              <?php foreach ($roles as $rol_option): ?>
                <option value="<?=$rol_option['id']?>" <?=($usuario[$rol_field] == $rol_option['id']) ? 'selected' : ''?>>
                  <?=htmlspecialchars($rol_option['rol'])?>
                </option>
              <?php endforeach; ?>
            </select>
          </td>
          <td>
            <input type="hidden" name="user_id" value="<?=$usuario['id']?>">
            <button type="submit" name="update">Guardar</button>          
            <button type="submit" name="delete" onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">Eliminar</button>
          </td>
        </form>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
</body>
</html>
