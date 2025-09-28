<?php
require __DIR__ . '/../estudiante_init.php';
require_est_login();
$e = est();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Escanear QR — Estudiante</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Arial,sans-serif;background:#0f172a;color:#e2e8f0;margin:0}
    header{display:flex;justify-content:space-between;align-items:center;padding:16px;background:#111827}
    a{color:#93c5fd;text-decoration:none}
    .container{padding:24px}
    #reader{width:100%;max-width:400px;margin:auto}
  </style>
  <!-- CDN de html5-qrcode -->
  <script src="https://unpkg.com/html5-qrcode"></script>
</head>
<body>
  <header>
    <div><a href="/inventario_uni/public/estudiante_panel.php">← Panel</a></div>
    <div>Inventario — Estudiante</div>
    <div><?=htmlspecialchars($e['nombre'].' '.$e['apellido'])?> · 
      <a href="/inventario_uni/public/estudiantes_logout.php">Salir</a>
    </div>
  </header>

  <div class="container">
    <h2>Escanear QR de un equipo</h2>
    <div id="reader"></div>
  </div>

  <script>
    window.addEventListener('load', () => {
      if (!window.Html5QrcodeScanner) return;

      const scanner = new Html5QrcodeScanner("reader", {
        fps: 10,
        qrbox: 300,
        rememberLastUsedCamera: true,
        formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ],
        supportedScanTypes: [ Html5QrcodeScanType.SCAN_TYPE_CAMERA ]
      });

      const gotoEquipo = async (serial) => {
        try { await scanner.clear(); } catch (_) {}
        setTimeout(() => {
          window.location.assign(`/inventario_uni/public/estudiante_equipo.php?serial=${encodeURIComponent(serial)}`);
        }, 60);
      };

      const onScanSuccess = async (decodedText) => {
        let serial = '';
        try {
          const u = new URL(decodedText, window.location.origin);
          serial = u.searchParams.get('serial') || '';
        } catch (_) {
          serial = decodedText;
        }
        serial = (serial || '').trim();
        if (!serial) {
          alert("No se detectó un serial válido en el QR.");
          return;
        }
        await gotoEquipo(serial);
      };

      scanner.render(onScanSuccess);
    });
  </script>
</body>
</html>
