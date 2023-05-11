<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$spreadsheet ->getDefaultStyle()->getNumberFormat()->setFormatCode('#');

$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'Nombre');
$sheet->setCellValue('B1', 'NIF');
$sheet->setCellValue('C1', 'Teléfono');
$sheet->setCellValue('D1', 'Fax');
$sheet->setCellValue('E1', 'Dirección postal');
$sheet->setCellValue('F1', 'Código postal');
$sheet->setCellValue('G1', 'Población');
$sheet->setCellValue('H1', 'Provincia');
$sheet->setCellValue('I1', 'País');
$sheet->setCellValue('J1', 'IBAN');
$sheet->setCellValue('K1', 'Número de trabajadores');
$sheet->setCellValue('L1', 'Sectores');
$sheet->setCellValue('M1', 'Socias');
$sheet->setCellValue('N1', 'Notas');

$empresas = $datos;

// echo '<pre>';
// print_r($empresas);
// die;
$fila=2;

foreach ($empresas as $empresa) {
    $socias = $empresa->socias;
    $sheet->setCellValue('A'.$fila, $empresa->nombre);
    $sheet->setCellValue('B'.$fila, $empresa->nif);
    $spreadsheet->getActiveSheet()->getCell('C'.$fila)
    ->setValueExplicit(
        $empresa->telefono,
        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
    );
    $spreadsheet->getActiveSheet()->getCell('D'.$fila)
    ->setValueExplicit(
        $empresa->fax,
        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
    );
    $sheet->setCellValue('E'.$fila, $empresa->dir);
    $spreadsheet->getActiveSheet()->getCell('F'.$fila)
    ->setValueExplicit(
        $empresa->cp,
        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
    );
    $sheet->setCellValue('G'.$fila, $empresa->poblacion);
    $sheet->setCellValue('H'.$fila, $empresa->provincia);
    $sheet->setCellValue('I'.$fila, $empresa->pais);
    $spreadsheet->getActiveSheet()->getCell('J'.$fila)
    ->setValueExplicit(
        $empresa->iban,
        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2
    );    
    $sheet->setCellValue('K'.$fila, $empresa->num_trabajadores);


    // Cargar sectores de la empresa
    $txtSectores='';
    $empresa->sectores = $this->empresaModelo->obtenerSectoresEmpresa($empresa->nif);
    foreach ($empresa->sectores as $sector) {
        $txtSectores .= '['.$sector->nombre . '] ';
    }

    $sheet->setCellValue('L'.$fila, $txtSectores);

    $txtSocias = '';
    foreach ($socias as $socia) {
        $txtSocias .= "[{$socia->nombre} {$socia->apellidos}] ";
    }
    $sheet->setCellValue('M'.$fila, $txtSocias);
    $sheet->setCellValue('N'.$fila, $empresa->notas);

    $fila++;
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

$writer = new Xlsx($spreadsheet);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Listado Empresas ARAME.xlsx"');
$writer->save('php://output');

?>