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
    <style>
        /* ======== NAVBAR ESTILO AZUL MODERNO ======== */
        body {
            margin: 0;
            font-family: 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f6fa;
        }

        .navbar {
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            color: #fff;
            padding: 12px 25px;
            display: flex;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .nav-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand a {
            color: #fff;
            text-decoration: none;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .badge {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            margin-left: 10px;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 20px;
            margin: 0;
        }

        .nav-item a {
            text-decoration: none;
            color: #fff;
            font-weight: 500;
            transition: color 0.3s, transform 0.2s;
        }

        .nav-item a:hover,
        .nav-item a.active {
            color: #ffd166;
            transform: scale(1.05);
        }

        /* ======== MENÚ USUARIO ======== */
        .nav-user {
            position: relative;
        }

        .user-dropdown {
            position: relative;
        }

        .user-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 25px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            cursor: pointer;
            padding: 8px 14px;
            transition: background 0.3s;
        }

        .user-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* 🔹 Nuevo estilo de imagen de usuario */
        .user-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: radial-gradient(circle at top left, #ffffff, #cfd9ff);
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .user-icon:hover {
            transform: scale(1.08);
            box-shadow: 0 0 12px rgba(255, 255, 255, 0.8), 0 0 20px rgba(42, 82, 152, 0.7);
        }

        .arrow {
            border: solid #fff;
            border-width: 0 2px 2px 0;
            padding: 3px;
            transform: rotate(45deg);
            margin-left: 5px;
        }

        .user-menu {
            display: none;
            position: absolute;
            top: 45px;
            right: 0;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            padding: 10px 0;
            min-width: 160px;
            z-index: 100;
        }

        .user-menu a {
            display: block;
            padding: 10px 15px;
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s;
        }

        .user-menu a:hover {
            background: #f2f2f2;
        }

        .user-dropdown.active .user-menu {
            display: block;
        }

        .logout-btn {
            color: #e74c3c !important;
            font-weight: bold;
        }

        /* ======== MENÚ RESPONSIVO ======== */
        .nav-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
        }

        .nav-toggle span {
            background: #fff;
            width: 25px;
            height: 3px;
            border-radius: 2px;
        }

        @media (max-width: 768px) {
            .nav-menu {
                position: absolute;
                top: 60px;
                right: 0;
                flex-direction: column;
                background: #1e3c72;
                width: 100%;
                text-align: center;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.4s ease;
            }

            .nav-menu.active {
                max-height: 400px;
                padding: 15px 0;
            }

            .nav-toggle {
                display: flex;
            }
        }

        /* ======== NOTIFICACIÓN ======== */
        .notificacion-fija {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #f39c12;
            color: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            z-index: 9999;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            font-weight: bold;
            opacity: 0;
            transition: opacity 1s ease, transform 0.5s ease;
            transform: translateY(-20px);
        }

        .notificacion-fija.show {
            opacity: 1;
            transform: translateY(0);
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
                    <li class="nav-item"><a href="/inventario_uni/public/auditoria.php" class="nav-link <?= isActive($currentPage, 'auditoria.php') ?>">Auditoria</a></li>
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
            const toggle = document.getElementById('nav-toggle');
            const menu = document.getElementById('nav-menu');
            toggle.addEventListener('click', () => {
                toggle.classList.toggle('active');
                menu.classList.toggle('active');
            });

            const userDropdown = document.querySelector('.user-dropdown');
            const userBtn = document.querySelector('.user-btn');

            userBtn.addEventListener('click', () => {
                userDropdown.classList.toggle('active');
            });

            document.addEventListener('click', (e) => {
                if (!userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('active');
                }
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

                    // Desvanecer después de 6 segundos
                    setTimeout(() => {
                        notificacionDiv.style.opacity = 0; // desaparece suavemente
                    }, 6000);
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