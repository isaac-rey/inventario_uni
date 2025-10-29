<?php
// =================================================================
// REPORTE DE AUDITORÍA - CÓDIGO FINAL COMPLETO Y MEJORADO
// =================================================================
require __DIR__ . '/../init.php';
require_login();

require __DIR__ . '/../vendor/autoload.php';

// ------------------------------------------------------------------
// CORRECCIÓN CLAVE: ESTABLECER LA ZONA HORARIA PARA PARAGUAY
date_default_timezone_set('America/Asuncion');
// ------------------------------------------------------------------

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

$rol = user()['rol'];
if ($rol !== 'admin') {
    die("Acceso denegado.");
}

$COLOR_FONDO_CABECERA = [52, 73, 94];
$COLOR_FONDO_CEBRA = [245, 245, 245];
$COLOR_TEXTO_CABECERA = [255, 255, 255];
$COLOR_TEXTO_DATOS = [30, 30, 30];
$COLOR_SUBTITULO = [100, 100, 100];

$COLOR_AZUL_OSCURO_HEX = '34495E';
$COLOR_TEXTO_CABECERA_HEX = 'FFFFFF';
$COLOR_FONDO_CEBRA_HEX = 'F5F5F5';
$COLOR_BORDE_TABLA_HEX = 'AAAAAA';

$search = trim($_GET['q'] ?? '');
$fecha_inicio = trim($_GET['fecha_inicio'] ?? '');
$fecha_fin = trim($_GET['fecha_fin'] ?? '');
$tipo_accion = trim($_GET['tipo'] ?? '');

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
    $where_clauses[] = "a.tipo_accion = ?";
    $params[] = $tipo_accion;
    $types .= 's';
}

$where = '';
if (!empty($where_clauses)) {
    $where = " WHERE " . implode(" AND ", $where_clauses);
}

$sql = "SELECT a.id, a.accion, a.fecha, u.nombre
        FROM auditoria a
        JOIN usuarios u ON a.usuario_id = u.id 
        " . $where . "
        ORDER BY a.fecha DESC";

$result = null;
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

$all_results = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $all_results[] = $row;
    }
}

$formato = $_GET['formato'] ?? 'csv';
$filename = "Reporte_Auditoria_" . date('Y-m-d') . "." . $formato;

$nombre_tipo = [
    'sesión' => 'Sesiones',
    'préstamo' => 'Prestamos',
    'devolución' => 'Devoluciones',
    'reporte' => 'Reportes_De_Equipos',
    'mantenimiento' => 'Mantenimientos',
    'acción_equipo' => 'Equipos',
    'acción_componente' => 'Componentes',
    'acción_sala' => 'Salas',
    'acción_usuario' => 'Usuarios',
    'acción_estudiante' => 'Estudiantes',
    'acción_docentes' => 'Docentes',
];

$nombre_base = 'auditoria_general';
if (!empty($tipo_accion) && isset($nombre_tipo[$tipo_accion])) {
    $nombre_base = $nombre_tipo[$tipo_accion];
}

$filename = "Reporte_{$nombre_base}_" . date('Y-m-d') . "." . $formato;

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
        $fecha_larga = strftime('%d/%m/%Y %H:%M:%S', $timestamp);
        fputcsv($output, [
            $contador,
            $row['nombre'],
            $row['accion'],
            utf8_decode($fecha_larga)
        ], $delimitador);
    }
    fclose($output);
    exit;
} elseif ($formato == 'xlsx') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Auditoría');

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
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D0D0D0']]],
    ];

    $data_style = [
        'font' => ['color' => ['rgb' => sprintf('%02x%02x%02x', $COLOR_TEXTO_DATOS[0], $COLOR_TEXTO_DATOS[1], $COLOR_TEXTO_DATOS[2])]],
        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E9E9E9']]],
    ];

    $zebra_style = ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $COLOR_FONDO_CEBRA_HEX]]];

    $sheet->setCellValue('A1', 'REPORTE DE AUDITORÍA DEL SISTEMA');
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->applyFromArray($title_style);
    $sheet->getRowDimension(1)->setRowHeight(35);

    // Aquí se usa la hora del servidor con la zona horaria ya ajustada
    $sheet->setCellValue('A2', 'Generado el ' . date('d/m/Y') . ' a las ' . date('H:i'));
    $sheet->mergeCells('A2:D2');
    $sheet->getStyle('A2')->applyFromArray($subtitle_style);
    $sheet->getRowDimension(2)->setRowHeight(20);

    $start_row = 4;
    $columnas = ['Nro', 'Usuario', 'Acción realizada', 'Fecha'];
    $sheet->fromArray($columnas, NULL, 'A' . $start_row);
    $sheet->getStyle('A' . $start_row . ':D' . $start_row)->applyFromArray($header_style);
    $sheet->getRowDimension($start_row)->setRowHeight(28);

    $row_num = $start_row + 1;
    $contador = 0;
    foreach ($all_results as $row) {
        $contador++;
        $excel_date = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(strtotime($row['fecha']));
        $data = [$contador, $row['nombre'], $row['accion'], $excel_date];
        $current_range = 'A' . $row_num . ':D' . $row_num;
        $sheet->fromArray($data, NULL, 'A' . $row_num);
        $sheet->getStyle($current_range)->applyFromArray($data_style);
        if ($contador % 2 != 0) $sheet->getStyle($current_range)->applyFromArray($zebra_style);
        $sheet->getStyle('A' . $row_num)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row_num)->setRowHeight(-1);
        $row_num++;
    }

    $final_data_row = $row_num - 1;
    $sheet->getColumnDimension('A')->setWidth(8);
    $sheet->getColumnDimension('B')->setWidth(25);
    $sheet->getColumnDimension('C')->setWidth(75);
    $sheet->getColumnDimension('D')->setWidth(30);
    $sheet->getStyle('D' . ($start_row + 1) . ':D' . $final_data_row)
        ->getNumberFormat()->setFormatCode('dd/mm/yyyy hh:mm:ss');
    $sheet->getStyle('A' . $start_row . ':D' . $final_data_row)
        ->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM)
        ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($COLOR_AZUL_OSCURO_HEX));

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} elseif ($formato == 'pdf') {
    require('../fpdf/fpdf.php');

    function dibujarCabeceraPDF($pdf, $COLOR_FONDO_CABECERA, $COLOR_TEXTO_CABECERA)
    {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor($COLOR_TEXTO_CABECERA[0], $COLOR_TEXTO_CABECERA[1], $COLOR_TEXTO_CABECERA[2]);
        $pdf->SetFillColor($COLOR_FONDO_CABECERA[0], $COLOR_FONDO_CABECERA[1], $COLOR_FONDO_CABECERA[2]);
        $pdf->Cell(15, 7, 'Nro', 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Usuario', 1, 0, 'C', true);
        $pdf->Cell(95, 7, utf8_decode('Acción realizada'), 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Fecha', 1, 0, 'C', true);
        $pdf->Ln();
    }

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(true, 10);

    $logo_path = __DIR__ . '/../img/escudo.png';
    if (file_exists($logo_path)) {
        $pdf->SetX(10);
        $pdf->Image($logo_path, 10, 10, 25, 20);
        $pdf->SetXY(35 + 5, 12);
    } else {
        $pdf->SetY(12);
    }

    $pdf->SetFont('Arial', 'B', 20);
    $pdf->SetTextColor($COLOR_FONDO_CABECERA[0], $COLOR_FONDO_CABECERA[1], $COLOR_FONDO_CABECERA[2]);
    $pdf->Cell(190 - 30, 10, utf8_decode('REPORTE DE AUDITORÍA'), 0, 1, 'C');

    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor($COLOR_SUBTITULO[0], $COLOR_SUBTITULO[1], $COLOR_SUBTITULO[2]);
    // Aquí se usa la hora del servidor con la zona horaria ya ajustada
    $pdf->Cell(190, 7, utf8_decode("Generado el " . date('d/m/Y') . " a las " . date('H:i')), 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetX(10);
    dibujarCabeceraPDF($pdf, $COLOR_FONDO_CABECERA, $COLOR_TEXTO_CABECERA);

    $pdf->SetTextColor($COLOR_TEXTO_DATOS[0], $COLOR_TEXTO_DATOS[1], $COLOR_TEXTO_DATOS[2]);

    $pdf->SetFont('Arial', '', 9);
    $line_height = 5;
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
            dibujarCabeceraPDF($pdf, $COLOR_FONDO_CABECERA, $COLOR_TEXTO_CABECERA);
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor($COLOR_TEXTO_DATOS[0], $COLOR_TEXTO_DATOS[1], $COLOR_TEXTO_DATOS[2]);
        }

        $y_pos_inicio_fila = $pdf->GetY();
        $x_pos_inicio_fila = $pdf->GetX();

        $pdf->SetXY($x_pos_inicio_fila + $w_id + $w_nombre, $y_pos_inicio_fila);
        $pdf->MultiCell($w_accion, $line_height, utf8_decode($row['accion']), 0, 'L', false, 1, true);
        $y_altura_accion = $pdf->GetY();
        $altura_fila = max($line_height, $y_altura_accion - $y_pos_inicio_fila);

        $pdf->SetFillColor(
            $fondo_fila ? $COLOR_FONDO_CEBRA[0] : 255,
            $fondo_fila ? $COLOR_FONDO_CEBRA[1] : 255,
            $fondo_fila ? $COLOR_FONDO_CEBRA[2] : 255
        );
        $fondo_fila = !$fondo_fila;

        $pdf->SetXY($x_pos_inicio_fila, $y_pos_inicio_fila);
        $pdf->Cell($w_id + $w_nombre + $w_accion + $w_fecha, $altura_fila, '', 0, 0, 'L', true);

        $borde = 1;
        $pdf->SetDrawColor(230, 230, 230);

        $pdf->SetXY($x_pos_inicio_fila, $y_pos_inicio_fila);
        $pdf->Cell($w_id, $altura_fila, $contador, $borde, 0, 'C', true);

        $pdf->SetXY($x_pos_inicio_fila + $w_id, $y_pos_inicio_fila);
        $pdf->Cell($w_nombre, $altura_fila, utf8_decode($row['nombre']), $borde, 0, 'L', true);

        $pdf->SetXY($x_pos_inicio_fila + $w_id + $w_nombre, $y_pos_inicio_fila);
        $pdf->MultiCell($w_accion, $line_height, utf8_decode($row['accion']), $borde, 'L', true);

        $pdf->SetXY($x_pos_inicio_fila + $w_id + $w_nombre + $w_accion, $y_pos_inicio_fila);
        $pdf->Cell($w_fecha, $altura_fila, $row['fecha'], $borde, 0, 'C', true);

        $pdf->SetY($y_pos_inicio_fila + $altura_fila);
    }

    $pdf->Output('D', $filename);
    exit;
}
?>