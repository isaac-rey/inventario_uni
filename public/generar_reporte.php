<?php
// =================================================================
// REPORTE DE AUDITORÍA - CÓDIGO FINAL COMPLETO Y MEJORADO
// =================================================================
require __DIR__ . '/../init.php';
require_login();

// 💡 NECESARIO: Incluir el cargador automático de Composer
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Solo el admin puede generar reportes
$rol = user()['rol'];
if ($rol !== 'admin') {
    die("Acceso denegado.");
}

// --- CONFIGURACIÓN DE COLORES Y ESTILOS ---
// PDF (RGB)
$COLOR_FONDO_CABECERA = [52, 73, 94]; // Azul oscuro/Gris (Elegante)
$COLOR_FONDO_CEBRA = [245, 245, 245]; // Gris muy claro (para filas alternas)
$COLOR_TEXTO_CABECERA = [255, 255, 255]; // Blanco
$COLOR_TEXTO_DATOS = [30, 30, 30]; // Gris oscuro (Casi negro) para mejor visibilidad
$COLOR_SUBTITULO = [100, 100, 100]; // Gris suave para el subtítulo

// XLSX (HEX)
$COLOR_AZUL_OSCURO_HEX = '34495E'; 
$COLOR_TEXTO_CABECERA_HEX = 'FFFFFF';
$COLOR_FONDO_CEBRA_HEX = 'F5F5F5';
$COLOR_BORDE_TABLA_HEX = 'AAAAAA'; 


// 1. OBTENER LOS FILTROS DE BÚSQUEDA Y FECHA (Se omite 'tipo_accion')
$search = trim($_GET['q'] ?? '');
$fecha_inicio = trim($_GET['fecha_inicio'] ?? '');
$fecha_fin = trim($_GET['fecha_fin'] ?? '');

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

$where = '';
if (!empty($where_clauses)) {
    $where = " WHERE " . implode(" AND ", $where_clauses);
}

// 2. EJECUTAR LA CONSULTA Y RECUPERAR TODOS LOS RESULTADOS
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
        $all_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        die("Error al preparar la consulta: " . $mysqli->error);
    }
} else {
    $result = $mysqli->query($sql);
    $all_results = $result->fetch_all(MYSQLI_ASSOC);
    $result->close();
}


// 3. DECIDIR EL FORMATO DE SALIDA
$formato = $_GET['formato'] ?? 'csv';
$filename = "Reporte_Auditoria_" . date('Y-m-d') . "." . $formato;


// ------------------------------------------------------------------
// --- GENERACIÓN CSV (FECHA LARGA LOCALIZADA) ---
// ------------------------------------------------------------------
if ($formato == 'csv') {
    setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'es'); 
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); 
    $delimitador = ';'; 
    
    fputcsv($output, ['Nro', 'Usuario', utf8_decode('Acción realizada'), 'Fecha'], $delimitador);

    $contador = 0;
    foreach ($all_results as $row) { 
        $contador++;
        $timestamp = strtotime($row['fecha']);
        $fecha_larga = strftime('%A, %d de %B de %Y %H:%M:%S', $timestamp);
        
        fputcsv($output, [
            $contador,
            $row['nombre'],
            $row['accion'],
            utf8_decode($fecha_larga) 
        ], $delimitador);
    }
    fclose($output);
    exit;
} 
// ------------------------------------------------------------------
// --- GENERACIÓN XLSX (PROFESIONAL Y PULIDO) ---
// ------------------------------------------------------------------
elseif ($formato == 'xlsx') {

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Auditoría');

    // --- ESTILOS DEFINITIVOS ---

    $title_style = [
        'font' => ['bold' => true, 'size' => 20, 'color' => ['rgb' => $COLOR_AZUL_OSCURO_HEX]],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    ];

    $subtitle_style = [
        'font' => ['size' => 10, 'color' => ['rgb' => '666666']], 
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    ];

    $header_style = [
        'font' => ['bold' => true, 'color' => ['rgb' => $COLOR_TEXTO_CABECERA_HEX], 'size' => 11],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $COLOR_AZUL_OSCURO_HEX]],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D0D0D0']], 
        ],
    ];
    
    $data_style = [
        'font' => ['color' => ['rgb' => sprintf('%02x%02x%02x', $COLOR_TEXTO_DATOS[0], $COLOR_TEXTO_DATOS[1], $COLOR_TEXTO_DATOS[2])]],
        'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E9E9E9']], 
        ],
    ];

    $zebra_style = [
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $COLOR_FONDO_CEBRA_HEX]],
    ];


    // 1. TÍTULO Y SUBTÍTULO
    $sheet->setCellValue('A1', 'REPORTE DE AUDITORÍA DEL SISTEMA');
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->applyFromArray($title_style);
    $sheet->getRowDimension(1)->setRowHeight(35);

    $sheet->setCellValue('A2', 'Generado el ' . date('d/m/Y') . ' a las ' . date('H:i'));
    $sheet->mergeCells('A2:D2');
    $sheet->getStyle('A2')->applyFromArray($subtitle_style);
    $sheet->getRowDimension(2)->setRowHeight(20);
    
    // Fila de espacio visual
    $start_row = 4;

    // 2. ESCRIBIR CABECERAS DE LA TABLA
    $columnas = ['Nro', 'Usuario', 'Acción realizada', 'Fecha'];
    $sheet->fromArray($columnas, NULL, 'A' . $start_row);
    
    // Aplicar estilo de cabecera
    $header_range = 'A' . $start_row . ':D' . $start_row;
    $sheet->getStyle($header_range)->applyFromArray($header_style);
    $sheet->getRowDimension($start_row)->setRowHeight(28);

    // 3. ESCRIBIR DATOS
    $row_num = $start_row + 1;
    $contador = 0;
    foreach ($all_results as $row) {
        $contador++;
        
        $excel_date = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(strtotime($row['fecha']));

        $data = [
            $contador,
            $row['nombre'],
            $row['accion'],
            $excel_date 
        ];
        
        $current_range = 'A' . $row_num . ':D' . $row_num;
        $sheet->fromArray($data, NULL, 'A' . $row_num);
        
        // Aplicar estilo de datos general
        $sheet->getStyle($current_range)->applyFromArray($data_style);
        
        // Estilo Cebra
        if ($contador % 2 != 0) { 
            $sheet->getStyle($current_range)->applyFromArray($zebra_style);
        }
        
        // Centrar el Nro. (columna A)
        $sheet->getStyle('A' . $row_num)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Ajustar automáticamente la altura de la fila para contenido multilínea
        $sheet->getRowDimension($row_num)->setRowHeight(-1); 

        $row_num++;
    }

    // 4. AUTOAJUSTE DE COLUMNAS Y FORMATO DE FECHA
    $final_data_row = $row_num - 1;
    
    $sheet->getColumnDimension('A')->setWidth(8); 
    $sheet->getColumnDimension('B')->setWidth(25); 
    $sheet->getColumnDimension('C')->setWidth(75); 
    $sheet->getColumnDimension('D')->setWidth(30); 
    
    // Formato de fecha y hora completa para la columna D
    $sheet->getStyle('D' . ($start_row + 1) . ':D' . $final_data_row)->getNumberFormat()
        ->setFormatCode('dd/mm/yyyy hh:mm:ss');
    
    // Borde exterior visible a toda la tabla
    $total_table_range = 'A' . $start_row . ':D' . $final_data_row;
    $sheet->getStyle($total_table_range)->getBorders()->getOutline()
        ->setBorderStyle(Border::BORDER_MEDIUM) 
        ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($COLOR_AZUL_OSCURO_HEX)); 

    // 5. DESCARGA
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
// ------------------------------------------------------------------
// --- GENERACIÓN PDF (ELEGANCIA Y LEGIBILIDAD CORREGIDA) ---
// ------------------------------------------------------------------
elseif ($formato == 'pdf') {
    require('../fpdf/fpdf.php'); 

    // --- FUNCIÓN AUXILIAR DE CABECERA CON ESTILO ---
    function dibujarCabecera($pdf, $COLOR_FONDO_CABECERA, $COLOR_TEXTO_CABECERA)
    {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor($COLOR_TEXTO_CABECERA[0], $COLOR_TEXTO_CABECERA[1], $COLOR_TEXTO_CABECERA[2]);
        $pdf->SetFillColor($COLOR_FONDO_CABECERA[0], $COLOR_FONDO_CABECERA[1], $COLOR_FONDO_CABECERA[2]);
        
        $pdf->Cell(15, 7, 'Nro', 0, 0, 'C', true);
        $pdf->Cell(40, 7, 'Usuario', 0, 0, 'C', true);
        $pdf->Cell(95, 7, utf8_decode('Acción realizada'), 0, 0, 'C', true);
        $pdf->Cell(40, 7, 'Fecha', 0, 0, 'C', true);
        $pdf->Ln();
    }
    // --- FIN FUNCIÓN AUXILIAR ---

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(true, 10);
    $ancho_pagina = 190; 
    
    $logo_ancho = 25; 
    $logo_alto = 20; 

    // 1. INCLUIR LOGO Y TÍTULO
    $logo_path = __DIR__ . '/../img/logo.png'; 
    
    if (file_exists($logo_path)) {
        $pdf->SetX(10);
        $pdf->Image($logo_path, 10, 10, $logo_ancho, $logo_alto);
        $pdf->SetXY(10 + $logo_ancho + 5, 12); 
    } else {
         $pdf->SetY(12);
    }
    
    // TÍTULO PRINCIPAL
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->SetTextColor($COLOR_FONDO_CABECERA[0], $COLOR_FONDO_CABECERA[1], $COLOR_FONDO_CABECERA[2]);
    $pdf->Cell($ancho_pagina - ($logo_ancho + 5), 10, utf8_decode('REPORTE DE AUDITORÍA'), 0, 1, 'C');

    // SUBTÍTULO
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor($COLOR_SUBTITULO[0], $COLOR_SUBTITULO[1], $COLOR_SUBTITULO[2]); 
    $pdf->Cell($ancho_pagina, 7, utf8_decode("Generado el " . date('d/m/Y') . " a las " . date('H:i')), 0, 1, 'C');
    $pdf->Ln(5);
    
    $pdf->SetX(10);
    $pdf->SetTextColor($COLOR_TEXTO_DATOS[0], $COLOR_TEXTO_DATOS[1], $COLOR_TEXTO_DATOS[2]); 

    dibujarCabecera($pdf, $COLOR_FONDO_CABECERA, $COLOR_TEXTO_CABECERA);

    // Variables de configuración de tabla
    $pdf->SetFont('Arial', '', 9);
    $line_height = 6; 
    $w_id = 15;
    $w_nombre = 40;
    $w_accion = 95;
    $w_fecha = 40;
    $margen_inferior_fpdf = 10;
    $espacio_seguro = 20;
    $limite_y_seguro = $pdf->GetPageHeight() - $margen_inferior_fpdf - $espacio_seguro;
    $contador = 0;
    $fondo_fila = false; 

    foreach ($all_results as $row) { 
        $contador++; 
        
        if ($pdf->GetY() > $limite_y_seguro) {
            $pdf->AddPage();
            dibujarCabecera($pdf, $COLOR_FONDO_CABECERA, $COLOR_TEXTO_CABECERA);
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor($COLOR_TEXTO_DATOS[0], $COLOR_TEXTO_DATOS[1], $COLOR_TEXTO_DATOS[2]);
        }

        // --- ESTILO CEBRA (Relleno de fondo) ---
        if ($fondo_fila) {
            $pdf->SetFillColor($COLOR_FONDO_CEBRA[0], $COLOR_FONDO_CEBRA[1], $COLOR_FONDO_CEBRA[2]);
        } else {
            $pdf->SetFillColor(255, 255, 255);
        }
        $fondo_fila = !$fondo_fila;

        $y_pos_inicio_fila = $pdf->GetY();
        $x_pos_inicio_fila = $pdf->GetX();

        // --- PASO 1: CALCULAR LA ALTURA (usando MultiCell sin relleno) ---
        $pdf->SetTextColor($COLOR_TEXTO_DATOS[0], $COLOR_TEXTO_DATOS[1], $COLOR_TEXTO_DATOS[2]); 
        $pdf->SetXY($x_pos_inicio_fila + $w_id + $w_nombre, $y_pos_inicio_fila);
        $pdf->MultiCell($w_accion, $line_height, utf8_decode($row['accion']), 0, 'L', false); 
        $y_altura_accion = $pdf->GetY();
        $y_altura_otros = $y_pos_inicio_fila + $line_height;

        $y_final_fila = max($y_altura_accion, $y_altura_otros);
        $altura_fila = $y_final_fila - $y_pos_inicio_fila;
        
        if ($altura_fila < $line_height) {
            $altura_fila = $line_height;
        }

        // --- PASO 2: DIBUJAR EL FONDO (Cell con relleno TRUE) ---
        $pdf->SetXY($x_pos_inicio_fila, $y_pos_inicio_fila);
        // Dibuja un rectángulo de fondo con el color cebra
        $pdf->Cell($w_id + $w_nombre + $w_accion + $w_fecha, $altura_fila, '', 0, 0, 'L', true); 
        
        // --- PASO 3: DIBUJAR EL CONTENIDO (MultiCell sin relleno FALSE) ---
        $pdf->SetTextColor($COLOR_TEXTO_DATOS[0], $COLOR_TEXTO_DATOS[1], $COLOR_TEXTO_DATOS[2]); 

        $pdf->SetXY($x_pos_inicio_fila, $y_pos_inicio_fila);
        $pdf->MultiCell($w_id, $altura_fila, $contador, 0, 'C', false); // Contenido sin relleno
        
        $pdf->SetXY($x_pos_inicio_fila + $w_id, $y_pos_inicio_fila);
        $pdf->MultiCell($w_nombre, $altura_fila, utf8_decode($row['nombre']), 0, 'L', false); // Contenido sin relleno
        
        $pdf->SetXY($x_pos_inicio_fila + $w_id + $w_nombre, $y_pos_inicio_fila);
        $pdf->MultiCell($w_accion, $line_height, utf8_decode($row['accion']), 0, 'L', false); // Contenido sin relleno
        
        $pdf->SetXY($x_pos_inicio_fila + $w_id + $w_nombre + $w_accion, $y_pos_inicio_fila);
        $pdf->MultiCell($w_fecha, $altura_fila, $row['fecha'], 0, 'C', false); // Contenido sin relleno
        
        
        // C) Dibujar línea divisoria sutil
        $pdf->SetDrawColor(230, 230, 230); 
        $pdf->Line($x_pos_inicio_fila, $y_final_fila, $x_pos_inicio_fila + $w_id + $w_nombre + $w_accion + $w_fecha, $y_final_fila);

        // 4. Mover el cursor para la siguiente fila
        $pdf->SetY($y_final_fila);
    }
    
    $pdf->Output('D', $filename);
    exit;
}
?>