<?php
require __DIR__ . '/../init.php';
require_login();

// Solo el admin puede generar reportes
$rol = user()['rol'];
if ($rol !== 'admin') {
    die("Acceso denegado.");
}

// 1. OBTENER LOS MISMOS FILTROS QUE LA PÁGINA DE AUDITORÍA
// Esta sección es IDÉNTICA a la de tu página original
$search = trim($_GET['q'] ?? '');
$fecha_inicio = trim($_GET['fecha_inicio'] ?? '');
$fecha_fin = trim($_GET['fecha_fin'] ?? '');
$tipo_accion = trim($_GET['tipo'] ?? ''); // <--- Filtro nuevo

$params = [];
$types = '';
$where_clauses = [];

if (!empty($search)) {
    $where_clauses[] = "(a.accion LIKE ? OR u.nombre LIKE ?)";
    $like_search = "%" . $search . "%";
    $params[] = $like_search;
    $params[] = $like_search;
    $types .= 'ss';
}
if (!empty($fecha_inicio)) {
    $where_clauses[] = "a.fecha >= ?";
    $params[] = $fecha_inicio . ' 00:00:00';
    $types .= 's';
}
if (!empty($fecha_fin)) {
    $where_clauses[] = "a.fecha <= ?";
    $params[] = $fecha_fin . ' 23:59:59';
    $types .= 's';
}
if (!empty($tipo_accion)) {
    $where_clauses[] = "a.tipo_accion = ?"; // ¡Igualdad exacta!
    $params[] = $tipo_accion;
    $types .= 's';
}

$where = '';
if (!empty($where_clauses)) {
    $where = " WHERE " . implode(" AND ", $where_clauses);
}

// 2. EJECUTAR LA MISMA CONSULTA (OJO: SIN LIMIT)
// Quitamos el LIMIT 100 para que el reporte sea completo
$sql = "SELECT a.id, a.accion, a.fecha, u.nombre
        FROM auditoria a
        JOIN usuarios u ON a.usuario_id = u.id 
        " . $where . "
        ORDER BY a.fecha DESC";

if (!empty($params)) {
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        die("Error al preparar la consulta: " . $mysqli->error);
    }
} else {
    $result = $mysqli->query($sql);
}

// 3. DECIDIR EL FORMATO DE SALIDA
$formato = $_GET['formato'] ?? 'csv';

// Diccionario de nombres descriptivos para el archivo
$nombre_tipo = [
    'préstamo' => 'Prestamos',
    'devolución' => 'Devoluciones',
    'mantenimiento' => 'Mantenimiento',
    'registro_estudiante' => 'Registro_Estudiantes',
    'registro_docente' => 'Registro_Docentes', // Asume que tienes este también
    'sesión' => 'Sesiones'
];

// Generar la parte del nombre
$nombre_base = 'auditoria';
if (!empty($tipo_accion) && isset($nombre_tipo[$tipo_accion])) {
    // Si hay un tipo de acción seleccionado y está en el diccionario, lo usamos
    $nombre_base = $nombre_tipo[$tipo_accion];
}

// Construir el nombre final del archivo
$filename = "Reporte_{$nombre_base}_" . date('Y-m-d') . "." . $formato;

if ($formato == 'csv') {
    // --- GENERAR CSV (FÁCIL Y COMPATIBLE CON EXCEL) ---

    // 💡 IMPORTANTE: El nombre del archivo ahora usa la variable $filename
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w'); // Escribir directamente a la salida

    // 💡 CAMBIO 2: Escribir la marca de Byte Order (BOM) para que Excel reconozca UTF-8
    fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Escribir la cabecera (usamos 'Nro' en lugar de 'ID')
    fputcsv($output, ['Nro', 'Usuario', 'Acción realizada', 'Fecha']);

    // Escribir los datos
    $contador = 0; // Inicializar contador
    while ($row = $result->fetch_assoc()) {
        $contador++; // Aumentar contador
        fputcsv($output, [
            $contador, // Usar el contador
            $row['nombre'],
            $row['accion'],
            $row['fecha']
        ]);
    }
    fclose($output);
    exit;
} elseif ($formato == 'pdf') {
    // --- GENERAR PDF (REQUIERE UNA LIBRERÍA) ---

    require('../fpdf/fpdf.php'); // <-- Asegúrate de tener la librería

    // --- INICIO DE LA FUNCIÓN AUXILIAR ---
    // Creamos una función para dibujar la cabecera
    // así podemos llamarla en cada nueva página
    function dibujarCabecera($pdf)
    {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(230, 230, 230); // Un gris claro para el fondo
        // 💡 CAMBIO 3: Cambiar la cabecera de la columna a 'Nro'
        $pdf->Cell(15, 7, 'Nro', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Usuario', 1, 0, 'C', true);
        $pdf->Cell(95, 7, utf8_decode('Acción realizada'), 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Fecha', 1, 0, 'C', true);
        $pdf->Ln();
    }
    // --- FIN DE LA FUNCIÓN AUXILIAR ---

    $pdf = new FPDF();
    $pdf->AddPage();
    // Establecer márgenes (izquierdo, superior, derecho)
    // El margen inferior (bottom margin) se establece aquí: 10
    $pdf->SetMargins(10, 10, 10);
    // Usamos el método SetAutoPageBreak para establecer explícitamente el margen inferior (10mm)
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->SetFont('Arial', 'B', 16);
    // 💡 CAMBIO 4: Corregir acento en el título
    $pdf->Cell(190, 10, utf8_decode('Reporte de Auditoría'), 0, 1, 'C');
    $pdf->Ln(10);

    // Dibujar la primera cabecera
    dibujarCabecera($pdf);

    // Datos
    $pdf->SetFont('Arial', '', 9);
    $line_height = 6; // Altura de cada línea de texto

    // Anchos de las columnas (los mismos que en la cabecera)
    $w_id = 15;
    $w_nombre = 40;
    $w_accion = 95;
    $w_fecha = 40;

    // Margen inferior que estableciste en FPDF
    $margen_inferior_fpdf = 10;

    // Espacio de seguridad (cuánto queremos dejar de colchón antes del margen final)
    // Si ponemos 10, significa que forzaremos el salto cuando queden 30mm antes de la página.
    $espacio_seguro = 20; // Reduce este valor para acercarte más al borde.

    // El límite de Y es la Altura Total de la Página (ej. 297mm) - Margen Inferior - Espacio Seguro
    $limite_y_seguro = $pdf->GetPageHeight() - $margen_inferior_fpdf - $espacio_seguro;

    $contador = 0; // Inicializar contador para PDF

    while ($row = $result->fetch_assoc()) {
        $contador++; // Aumentar contador
        // --- INICIO DE LA FILA ---

        // !! COMPROBACIÓN DE SALTO DE PÁGINA MANUAL
        // Si la posición Y actual supera nuestro límite seguro...
        if ($pdf->GetY() > $limite_y_seguro) {
            $pdf->AddPage(); // Añadimos una página
            dibujarCabecera($pdf); // Y volvemos a dibujar la cabecera
            $pdf->SetFont('Arial', '', 9); // Reseteamos la fuente para los datos
        }

        // 1. Guardar la posición X e Y del inicio de esta fila
        $y_pos_inicio_fila = $pdf->GetY();
        $x_pos_inicio_fila = $pdf->GetX();

        // --- PASO 1: Dibujar el texto SIN BORDES para calcular la altura ---

        // 💡 CAMBIO 5: Usar el contador y utf8_decode() en la celda ID/Contador
        $pdf->MultiCell($w_id, $line_height, $contador, 0, 'L');
        $y_altura_celda1 = $pdf->GetY();

        // Celda Nombre
        $pdf->SetXY($x_pos_inicio_fila + $w_id, $y_pos_inicio_fila);
        $pdf->MultiCell($w_nombre, $line_height, utf8_decode($row['nombre']), 0, 'L');
        $y_altura_celda2 = $pdf->GetY();

        // Celda Acción
        $pdf->SetXY($x_pos_inicio_fila + $w_id + $w_nombre, $y_pos_inicio_fila);
        $pdf->MultiCell($w_accion, $line_height, utf8_decode($row['accion']), 0, 'L');
        $y_altura_celda3 = $pdf->GetY();

        // Celda Fecha
        $pdf->SetXY($x_pos_inicio_fila + $w_id + $w_nombre + $w_accion, $y_pos_inicio_fila);
        $pdf->MultiCell($w_fecha, $line_height, $row['fecha'], 0, 'L');
        $y_altura_celda4 = $pdf->GetY();

        // 2. CALCULAR LA ALTURA MÁXIMA
        $y_final_fila = max($y_altura_celda1, $y_altura_celda2, $y_altura_celda3, $y_altura_celda4);
        $altura_fila = $y_final_fila - $y_pos_inicio_fila;

        // --- PASO 2: Dibujar los BORDES (Rectángulos) ---
        $pdf->Rect($x_pos_inicio_fila, $y_pos_inicio_fila, $w_id, $altura_fila);
        $pdf->Rect($x_pos_inicio_fila + $w_id, $y_pos_inicio_fila, $w_nombre, $altura_fila);
        $pdf->Rect($x_pos_inicio_fila + $w_id + $w_nombre, $y_pos_inicio_fila, $w_accion, $altura_fila);
        $pdf->Rect($x_pos_inicio_fila + $w_id + $w_nombre + $w_accion, $y_pos_inicio_fila, $w_fecha, $altura_fila);

        // 3. Mover el cursor para la siguiente fila
        $pdf->SetY($y_final_fila);
        // --- FIN DE LA FILA ---
    }
    // 💡 IMPORTANTE: El nombre del archivo ahora usa la variable $filename
    $pdf->Output('D', $filename); // 'D' fuerza la descarga
    exit;
}
