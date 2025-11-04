<?php
/**
 * Sistema de Autenticación - Login
 * Versión mejorada con mejores prácticas de seguridad y UX
 */

// Inicialización de dependencias
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../init.php';

// Aseguramos que la sesión esté activa (sin mostrar notice si ya lo está)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Variables de control
$error = '';

// === PROCESAMIENTO DEL FORMULARIO ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Sanitización de entradas
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validación básica
    if (empty($login)) {
        $error = 'El correo o CI es obligatorio.';
    } elseif (empty($password)) {
        $error = 'La contraseña es obligatoria.';
    } else {
        try {
            // Preparar consulta segura con prepared statements
            $stmt = $mysqli->prepare("
                SELECT 
                    u.id, 
                    u.ci, 
                    u.nombre, 
                    u.email, 
                    u.password_hash, 
                    r.nombre AS rol
                FROM usuarios u
                INNER JOIN roles r ON r.id = u.role_id
                WHERE (u.ci = ? OR u.email = ?)
                LIMIT 1
            ");
            
            if (!$stmt) {
                throw new Exception('Error en la preparación de la consulta.');
            }
            
            $stmt->bind_param('ss', $login, $login);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            
            // Verificar si el usuario existe y la contraseña es correcta
            if (!$user) {
                $error = 'Credenciales incorrectas.';
                error_log("Intento de login fallido para: $login desde IP: " . $_SERVER['REMOTE_ADDR']);
            } elseif (!password_verify($password, $user['password_hash'])) {
                $error = 'Credenciales incorrectas.';
            } else {
                // Regenerar ID de sesión para prevenir session fixation
                session_regenerate_id(true);
                
                // Establecer datos de sesión
                $_SESSION['user'] = [
                    'id'     => $user['id'],
                    'ci'     => $user['ci'],
                    'email'  => $user['email'],
                    'nombre' => $user['nombre'],
                    'rol'    => $user['rol'],
                ];
                
                // Auditar login exitoso
                $user_desc = htmlspecialchars($user['nombre'] . ' (Rol: ' . $user['rol'] . ')');
                auditar("Inicio de sesión exitoso. Usuario: {$user_desc}. IP: " . $_SERVER['REMOTE_ADDR'], 'sesión');
                
                // Redireccionar
                header('Location: /inventario_uni/index.php');
                exit;
            }
            
        } catch (Exception $e) {
            error_log("Error en login.php: " . $e->getMessage());
            $error = 'Error del sistema. Intenta más tarde.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de inventario universitario - Inicio de sesión">
    <meta name="theme-color" content="#667eea">
    
    <title>Iniciar Sesión • Inventario Universidad</title>
    
    <!-- Estilos -->
    <link rel="stylesheet" href="../css/form_login.css">
</head>
<body>
    <div class="card">
        <!-- Título -->
        <h1>Inventario Universidad</h1>
        
        <!-- Mensaje de error -->
        <?php if ($error): ?>
            <div class="error" role="alert">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        
        <!-- Formulario de login -->
        <form method="POST" action="" id="loginForm" autocomplete="on">
            
            <!-- Campo: Correo o CI -->
            <label for="login">Correo electrónico o CI</label>
            <input 
                type="text" 
                id="login"
                name="login" 
                autocomplete="username" 
                placeholder="Ej: juan@universidad.edu"
                value="<?= isset($_POST['login']) ? htmlspecialchars($_POST['login']) : '' ?>"
                required
                autofocus
            >
            
            <!-- Campo: Contraseña -->
            <label for="password">Contraseña</label>
            <input 
                type="password" 
                id="password"
                name="password" 
                autocomplete="current-password"
                placeholder="Ingresa tu contraseña"
                required
            >
            
            <!-- Enlace: Olvidé mi contraseña -->
            <div class="forgot-password">
                <a href="../config/forgot_password.php">¿Olvidaste tu contraseña?</a>
            </div>
            
            <!-- Botón de envío -->
            <button type="submit" id="submitBtn">
                Iniciar Sesión
            </button>
        </form>
    </div>

    <!-- Script para mejorar UX -->
    <script>
        // Prevenir doble envío del formulario
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        
        form.addEventListener('submit', function(e) {
            // Deshabilitar botón y mostrar estado de carga
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            submitBtn.textContent = 'Iniciando...';
            
            // Si hay error de validación, rehabilitar botón
            if (!form.checkValidity()) {
                e.preventDefault();
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                submitBtn.textContent = 'Iniciar Sesión';
            }
        });
        
        // Limpiar estado de carga si volvemos con botón atrás
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                submitBtn.textContent = 'Iniciar Sesión';
            }
        });
    </script>
</body>
</html>
