<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$spreadsheet ->getDefaultStyle()->getNumberFormat()->setFormatCode('#');

$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Referencia');
$sheet->setCellValue('B1', 'Nombre librado');
$sheet->setCellValue('C1', 'Importe');
$sheet->setCellValue('D1', 'Cuenta de adeudo');

$recibos = $datos;

$fila = 2;

foreach ($recibos as $rec) {
    $recibo = $rec['recibo'];
    $socia = $rec['socia'];

    $sheet->setCellValue('A'.$fila, sprintf('1'.'%06d', $recibo->cod));
    $sheet->setCellValue('B'.$fila, mb_strtoupper($socia->nombre." ".$socia->apellidos));
    $spreadsheet->getActiveSheet()->getStyle('C'.$fila)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    $sheet->setCellValue('C'.$fila, $recibo->cuantia);
    $spreadsheet->getActiveSheet()->getCell('D'.$fila)->setValueExplicit($recibo->iban,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

    $fila++;
}

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Listado Socias ARAME.xlsx"');
$writer->save('php://output');

?>