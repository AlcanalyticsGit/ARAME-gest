<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$spreadsheet ->getDefaultStyle()->getNumberFormat()->setFormatCode('#');

$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Número de socia');
$sheet->setCellValue('B1', 'Nombre');
$sheet->setCellValue('C1', 'Apellidos');
$sheet->setCellValue('D1', 'DNI / NIE');
$sheet->setCellValue('E1', 'Correo electrónico');
$sheet->setCellValue('F1', 'Teléfono');
$sheet->setCellValue('G1', 'Móvil');
$sheet->setCellValue('H1', 'Fax');
$sheet->setCellValue('I1', 'Dirección postal');
$sheet->setCellValue('J1', 'Código postal');
$sheet->setCellValue('K1', 'Población');
$sheet->setCellValue('L1', 'Provincia');
$sheet->setCellValue('M1', 'País');
$sheet->setCellValue('N1', 'Razón social facturación');
$sheet->setCellValue('O1', 'Dirección postal facturación');
$sheet->setCellValue('P1', 'Código postal facturación');
$sheet->setCellValue('Q1', 'Población facturación');
$sheet->setCellValue('R1', 'Provincia facturación');
$sheet->setCellValue('S1', 'País facturación');
$sheet->setCellValue('T1', 'Cuota');
$sheet->setCellValue('U1', 'Método de pago');
$sheet->setCellValue('V1', 'IBAN');
$sheet->setCellValue('W1', 'Empresa');
$sheet->setCellValue('X1', 'Notas');

$socias = $datos;
$fila = 2;

foreach ($socias as $socia) {
    if($socia->alta == 1) {
        $sheet->setCellValue('A'.$fila, $socia->cod);
        $sheet->setCellValue('B'.$fila, $socia->nombre);
        $sheet->setCellValue('C'.$fila, $socia->apellidos);
        $sheet->setCellValue('D'.$fila, $socia->nif);
        $sheet->setCellValue('E'.$fila, $socia->email);
        $sheet->setCellValue('F'.$fila, $socia->tlf);
        $sheet->setCellValue('G'.$fila, $socia->movil);
        $sheet->setCellValue('H'.$fila, $socia->fax);
        $sheet->setCellValue('I'.$fila, $socia->dir);
        $sheet->setCellValue('J'.$fila, $socia->cp);
        $sheet->setCellValue('K'.$fila, $socia->poblacion);
        $sheet->setCellValue('L'.$fila, $socia->provincia);
        $sheet->setCellValue('M'.$fila, $socia->pais);
        $sheet->setCellValue('N'.$fila, $socia->fact_nombre);
        $sheet->setCellValue('O'.$fila, $socia->fact_dir);
        $sheet->setCellValue('P'.$fila, $socia->fact_cp);
        $sheet->setCellValue('Q'.$fila, $socia->fact_poblacion);
        $sheet->setCellValue('R'.$fila, $socia->fact_provincia);
        $sheet->setCellValue('S'.$fila, $socia->fact_pais);
        $spreadsheet->getActiveSheet()->getStyle('T'.$fila)->getNumberFormat()
        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->setCellValue('T'.$fila, $socia->cuota_cuantia);
        $sheet->setCellValue('U'.$fila, $socia->metodo_pago);
        $spreadsheet->getActiveSheet()->getCell('V'.$fila)
        ->setValueExplicit(
            $socia->iban,
            \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
        );

        $txtEmpresas = '';

        foreach ($socia->empresas as $empresa) {
            $txtEmpresas .= '['.$empresa->empresa . '] ';
        }

        $sheet->setCellValue('W'.$fila, $txtEmpresas);
        $sheet->setCellValue('X'.$fila, $socia->notas);


    $fila++;
    }

    
}

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Listado Socias ARAME.xlsx"');
$writer->save('php://output');

?>