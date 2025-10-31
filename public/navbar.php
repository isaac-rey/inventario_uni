<?php
// init.php solo si no está incluido
if (!function_exists('user')) {
    require_once __DIR__ . '/../init.php';
}

date_default_timezone_set('America/Asuncion');

if (!isset($rol)) {
    $rol = user()['rol'];
}

function isActive($currentPage, $targetPage)
{
    return basename($_SERVER['PHP_SELF']) === $targetPage ? 'active' : '';
}

$currentPage = basename($_SERVER['PHP_SELF']);

// Hora de cierre
$cierre = new DateTime('17:30');
$cierreStr = $cierre->format('Y-m-d H:i:s');

// Consultar préstamos activos
$prestamos = $mysqli->query("SELECT COUNT(*) AS total FROM prestamos WHERE estado='activo'");
$totalActivos = $prestamos->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
    <link rel="stylesheet" href="../css/navbar.css">
    <style>
         
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-brand">
                <a href="/inventario_uni/" class="brand-link">Inventario</a>
                <span class="badge"><?= htmlspecialchars($rol) ?></span>
            </div>
            <div class="nav-toggle" id="nav-toggle"><span></span><span></span><span></span></div>
            <ul class="nav-menu" id="nav-menu">
                <li class="nav-item"><a href="/inventario_uni/public/equipos_index.php" class="nav-link <?= isActive($currentPage, 'equipos_index.php') ?>">Equipos</a></li>
                <li class="nav-item"><a href="/inventario_uni/public/prestamos_index.php" class="nav-link <?= isActive($currentPage, 'prestamos_index.php') ?>">Préstamos</a></li>
                <?php if ($rol === 'bibliotecaria'): ?>
                    <li class="nav-item"><a href="/inventario_uni/public/reportes.php" class="nav-link <?= isActive($currentPage, 'reportes.php') ?>">Reportes</a></li>
                    <li class="nav-item"><a href="/inventario_uni/public/mantenimientos.php" class="nav-link <?= isActive($currentPage, 'mantenimientos.php') ?>">Mantenimientos</a></li>
                <?php endif; ?>
                <?php if ($rol === 'admin'): ?>
                    <li class="nav-item"><a href="/inventario_uni/public/reportes.php" class="nav-link <?= isActive($currentPage, 'reportes.php') ?>">Reportes</a></li>
                    <li class="nav-item"><a href="/inventario_uni/public/mantenimientos.php" class="nav-link <?= isActive($currentPage, 'mantenimientos.php') ?>">Mantenimientos</a></li>
                    <li class="nav-item"><a href="/inventario_uni/public/usuarios_index.php" class="nav-link <?= isActive($currentPage, 'usuarios_index.php') ?>">Usuarios</a></li>
                <?php endif; ?>
                <li class="nav-item"><a href="/inventario_uni/public/docentes_listar.php" class="nav-link <?= isActive($currentPage, 'docentes_listar.php') ?>">Docentes</a></li>
                <li class="nav-item"><a href="/inventario_uni/public/estudiantes_listar.php" class="nav-link <?= isActive($currentPage, 'estudiantes_listar.php') ?>">Estudiantes</a></li>
                <?php if ($rol === 'admin'): ?>
                    <li class="nav-item"><a href="/inventario_uni/public/salas.php" class="nav-link <?= isActive($currentPage, 'salas.php') ?>">Salas</a></li>
                    <li class="nav-item"><a href="/inventario_uni/public/auditoria.php" class="nav-link <?= isActive($currentPage, 'auditoria.php') ?>">Auditoría</a></li>
                <?php endif; ?>
                <!-- -->
                <li class="nav-item nav-user">
                    <div class="user-dropdown">
                        <button class="user-btn">
                            <img src="/inventario_uni/img/user-icon.png" alt="Usuario" class="user-icon">
                            <span><?= htmlspecialchars(user()['nombre']) ?></span>
                            <i class="arrow"></i>
                        </button>
                        <div class="user-menu">
                            <a href="/inventario_uni/auth/logout.php" class="logout-btn">Cerrar sesión</a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div id="notificacion" class="notificacion-fija"></div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('nav-toggle'); // Botón de hamburguesa
        const menu = document.getElementById('nav-menu'); // El UL del menú
        const body = document.body; // Referencia al body
        
        // --- Lógica del Menú Principal ---
        if (toggle && menu) {
            toggle.addEventListener('click', () => {
                toggle.classList.toggle('active');
                menu.classList.toggle('active');
                
                // 🔑 CLAVE: Bloquea/desbloquea el scroll del body
                body.classList.toggle('menu-open');
            });
        }

        // --- Lógica del Menú de Usuario ---
        const userDropdown = document.querySelector('.user-dropdown');
        const userBtn = document.querySelector('.user-btn');

        if (userBtn) {
            userBtn.addEventListener('click', (e) => {
                e.stopPropagation(); 
                userDropdown.classList.toggle('active');
            });
        }

        // --- Cierre Global ---
        document.addEventListener('click', (e) => {
            // Cierra el dropdown de usuario si se hace clic fuera
            if (userDropdown && !userDropdown.contains(e.target) && userDropdown.classList.contains('active')) {
                userDropdown.classList.remove('active');
            }
            
            // Cierra el menú principal si se hace clic en un enlace DENTRO del menú
            if (e.target.closest('.nav-item a') && menu.classList.contains('active')) {
                 toggle.classList.remove('active');
                 menu.classList.remove('active');
                 body.classList.remove('menu-open'); // Desbloquea el scroll
            }
        });
    });
</script>
</body>

</html>