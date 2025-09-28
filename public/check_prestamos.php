<?php
// public/check_prestamos.php
require __DIR__ . '/../init.php';
require_login();

// ----------------------
// 🔔 Validación hora de cierre
// ----------------------
$cierre = new DateTime('19:09'); // hora de cierre de la universidad
$ahora = new DateTime();
$minutos_restantes = ($cierre->getTimestamp() - $ahora->getTimestamp()) / 60;

// Verificar préstamos activos
$prestamos = $mysqli->query("SELECT COUNT(*) AS total FROM prestamos WHERE estado='activo'")->fetch_assoc();
$totalActivos = $prestamos['total'] ?? 0;

$alerta = null;
if ($minutos_restantes <= 30 && $minutos_restantes > 0 && $totalActivos > 0) {
    $alerta = "⚠️ Faltan $minutos_restantes minutos para el cierre. Hay $totalActivos equipos prestados aún.";
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Verificación de préstamos</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0;display:flex;justify-content:center;align-items:center;height:100vh}
    .alerta{padding:20px;background:#facc15;color:#000;border-radius:12px;font-weight:bold;box-shadow:0 4px 10px rgba(0,0,0,0.5);max-width:600px;text-align:center}
    .ok{padding:20px;background:#22c55e;color:#fff;border-radius:12px;font-weight:bold;box-shadow:0 4px 10px rgba(0,0,0,0.5);max-width:600px;text-align:center}
  </style>
</head>
<body>
  <?php if ($alerta): ?>
    <div class="alerta">
      <?= htmlspecialchars($alerta) ?>
    </div>
    <script>
      alert("<?= htmlspecialchars($alerta) ?>");
    </script>
  <?php else: ?>
    <div class="ok">
      ✅ Todo en orden. No hay préstamos pendientes cercanos al cierre.
    </div>
  <?php endif; ?>
</body>
</html>
