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
        .notificacion-fija {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #f39c12;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            z-index: 9999;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            font-weight: bold;
            opacity: 0;
            transition: opacity 1s ease;
            /* transición suave de 1 segundo */
        }
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
                <li class="nav-item"><a href="/inventario_uni/public/estudiantes_listar.php" class="nav-link <?= isActive($currentPage, 'estudiantes_listar.php') ?>">Estudiantes</a></li>
                <?php if ($rol === 'bibliotecaria'): ?>
                    <li class="nav-item"><a href="/inventario_uni/public/reportes.php" class="nav-link <?= isActive($currentPage, 'reportes.php') ?>">Reportes</a></li>
                <?php endif; ?>
                <li class="nav-item"><a href="/inventario_uni/public/mantenimientos.php" class="nav-link <?= isActive($currentPage, 'mantenimientos.php') ?>">Mantenimientos</a></li>
                <?php if ($rol === 'admin'): ?>
                    <li class="nav-item"><a href="/inventario_uni/public/usuarios_index.php" class="nav-link <?= isActive($currentPage, 'usuarios_index.php') ?>">Usuarios</a></li>
                <?php endif; ?>
                <!-- -->
                <li class="nav-item"><a href="/inventario_uni/public/auditoria.php" class="nav-link <?= isActive($currentPage, 'auditoria.php') ?>">Auditoria</a></li>
                <!-- -->
                <li class="nav-item nav-user">
                    <span class="user-name"><?= htmlspecialchars(user()['nombre']) ?></span>
                    <a href="/inventario_uni/auth/logout.php" class="logout-btn">Salir</a>
                </li>
            </ul>
        </div>
    </nav>

    <div id="notificacion" class="notificacion-fija"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('nav-toggle');
            const menu = document.getElementById('nav-menu');
            toggle.addEventListener('click', () => {
                toggle.classList.toggle('active');
                menu.classList.toggle('active');
            });
        });

        const cierre = new Date("<?= $cierreStr ?>");
        const totalActivos = <?= $totalActivos ?>;
        const notificacionDiv = document.getElementById('notificacion');

        let ultimaNotificacion = 0;

        function actualizarAlerta() {
            const ahora = new Date();
            const diffMs = cierre - ahora;
            const diffMin = Math.ceil(diffMs / 60000);

            if (diffMin <= 30 && diffMin > 0 && totalActivos > 0) {
                const ahoraTimestamp = Date.now();
                if (ahoraTimestamp - ultimaNotificacion > 5 * 60 * 1000) { // cada 5 min
                    const mensaje = `⚠️ Faltan ${diffMin} min para el cierre. ${totalActivos} equipos prestados.`;
                    notificacionDiv.textContent = mensaje;
                    notificacionDiv.style.opacity = 1; // mostrar suavemente
                    ultimaNotificacion = ahoraTimestamp;

                    // Desvanecer después de 7 segundos
                    setTimeout(() => {
                        notificacionDiv.style.opacity = 0; // desaparece suavemente
                    }, 7000);
                }
            } else {
                notificacionDiv.style.opacity = 0;
            }
        }

        // Ejecutar al cargar y luego cada minuto
        actualizarAlerta();
        setInterval(actualizarAlerta, 60000);
    </script>
</body>

</html>