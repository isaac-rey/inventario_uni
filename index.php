<?php
require __DIR__ . '/init.php';
require_login();

// Ejemplo: mostrar menú distinto si es admin o bibliotecaria
$rol = user()['rol'];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel — Inventario Universidad</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px;background:#111827}
    a{color:#93c5fd;text-decoration:none}
    .container{padding:24px}
    .badge{display:inline-block;padding:4px 8px;border-radius:9999px;background:#1f2937;color:#93c5fd;font-size:12px}
    .grid{display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}
    .card{background:#111827;border:1px solid #1f2937;border-radius:12px;padding:16px}
    .card a.block{display:block;color:#e2e8f0;text-decoration:none}
    .card a.block:hover h3{color:#93c5fd}
    h3{margin:0 0 8px}
    p{margin:0}
  </style>
</head>
<body>
  <header>
    <div>Inventario — <span class="badge"><?=htmlspecialchars($rol)?></span></div>
    <div><?=htmlspecialchars(user()['nombre'])?> · <a href="/inventario_uni/auth/logout.php">Salir</a></div>
  </header>

  <div class="container">
    <div class="grid">
      <!-- Equipos -->
      <div class="card">
        <a class="block" href="/inventario_uni/public/equipos_index.php">
          <h3>Equipos</h3>
          <p>CRUD de equipos y componentes.</p>
        </a>
      </div>

      <!-- Préstamos -->
      <div class="card">
        <a class="block" href="/inventario_uni/public/prestamos_index.php">
          <h3>Préstamos</h3>
          <p>Registrar préstamos/devoluciones y ver el historial.</p>
        </a>
      </div>

      <!-- QR público -->
      <div class="card">
        <a class="block" href="/inventario_uni/public/equipos_index.php">
          <h3>QR público</h3>
          <p>Escaneo y ficha de “lo que trae” (desde cada equipo → QR / Vista pública).</p>
        </a>
      </div>

      <?php if ($rol === 'admin'): ?>
      <div class="card">
        <a class="block" href="/inventario_uni/public/usuarios_index.php">
          <h3>Usuarios / Roles</h3>
          <p>Gestión solo para admin (pendiente).</p>
        </a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
