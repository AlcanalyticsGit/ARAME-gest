<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$spreadsheet ->getDefaultStyle()->getNumberFormat()->setFormatCode('#');

$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Año');
$sheet->setCellValue('B1', 'Socia');
$sheet->setCellValue('C1', 'Descripción');

$premios = $datos;
$fila=2;

foreach ($premios as $premio) {
    $sheet->setCellValue('A'.$fila, $premio->year);
    $sheet->setCellValue('B'.$fila, $premio->socia_nombre." ".$premio->socia_apellidos);
    $sheet->setCellValue('C'.$fila, $premio->descripcion);

    $fila++;
}

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Listado Premiadas ARAME.xlsx"');
$writer->save('php://output');

?>